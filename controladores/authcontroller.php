<?php
require_once 'modelos/usuariomodel.php';

class AuthController {
    private $usuarioModel;

    public function __construct() {
        error_log("AuthController instanciado");
        $this->usuarioModel = new UsuarioModel();
    }

    public function index() {
        error_log("AuthController index llamado");
        // Redirigir al login si acceden directamente a auth
        header("Location: " . RUTA);
        exit();
    }

    public function login() {
        error_log("AuthController login llamado");
        error_log("Método: " . $_SERVER['REQUEST_METHOD']);
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            error_log("Datos POST: " . print_r($_POST, true));
            
            $correo = trim($_POST['correo'] ?? '');
            $contrasena = trim($_POST['contrasena'] ?? '');

            $errores = [];
            
            // Validaciones
            if (empty($correo)) {
                $errores[] = "El correo es obligatorio.";
            } elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
                $errores[] = "El formato del correo no es válido.";
            }

            if (empty($contrasena)) {
                $errores[] = "La contraseña es obligatoria.";
            }

            if (empty($errores)) {
                $usuario = $this->usuarioModel->verificarCredenciales($correo, $contrasena);
                
                if ($usuario) {
                    error_log("Login exitoso para: $correo");
                    // Iniciar sesión
                    $_SESSION['usuario_id'] = $usuario->getId_usuario();
                    $_SESSION['nombre'] = $usuario->getNombre() . ' ' . $usuario->getApellido();
                    $_SESSION['correo'] = $usuario->getCorreo();
                    $_SESSION['rol'] = $usuario->getRol();

                    // Redirigir según el rol
                    if ($usuario->getRol() == 'admin') {
                        header("Location: " . RUTA);
                    } else {
                        header("Location: " . RUTA . "catalogo");
                    }
                    exit();
                } else {
                    error_log("Credenciales incorrectas para: $correo");
                    $errores[] = "Credenciales incorrectas.";
                }
            }

            // Si hay errores, mostrar el formulario de login con errores
            $_SESSION['error_login'] = implode("<br>", $errores);
            header("Location: " . RUTA);
            exit();
        } else {
            error_log("Método no POST, redirigiendo");
            // Si no es POST, redirigir al login
            header("Location: " . RUTA);
            exit();
        }
    }

    public function registro() {
        error_log("AuthController registro llamado");
        error_log("Método: " . $_SERVER['REQUEST_METHOD']);
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            error_log("Datos POST registro: " . print_r($_POST, true));
            
            $nombre = trim($_POST['nombre'] ?? '');
            $apellido = trim($_POST['apellido'] ?? '');
            $correo = trim($_POST['correo'] ?? '');
            $contrasena = trim($_POST['contrasena'] ?? '');
            $confirmar_contrasena = trim($_POST['confirmar_contrasena'] ?? '');

            $errores = [];

            // Validaciones
            if (empty($nombre)) $errores[] = "El nombre es obligatorio.";
            if (empty($apellido)) $errores[] = "El apellido es obligatorio.";
            if (empty($correo)) $errores[] = "El correo es obligatorio.";
            if (empty($contrasena)) $errores[] = "La contraseña es obligatoria.";
            if (empty($confirmar_contrasena)) $errores[] = "Confirmar contraseña es obligatorio.";

            if (!preg_match("/^[a-zA-Z\sáéíóúÁÉÍÓÚñÑ]+$/", $nombre)) $errores[] = "El nombre solo debe contener letras y espacios.";
            if (!preg_match("/^[a-zA-Z\sáéíóúÁÉÍÓÚñÑ]+$/", $apellido)) $errores[] = "El apellido solo debe contener letras y espacios.";
            
            if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) $errores[] = "El formato del correo no es válido.";
            
            if ($this->usuarioModel->correoExiste($correo)) $errores[] = "El correo ya está registrado.";
            
            if (strlen($contrasena) < 6) $errores[] = "La contraseña debe tener al menos 6 caracteres.";
            if ($contrasena !== $confirmar_contrasena) $errores[] = "Las contraseñas no coinciden.";

            if (empty($errores)) {
                $usuario = new Usuario(null, $nombre, $apellido, $correo, $contrasena, 'usuario');
                $resultado = $this->usuarioModel->insert($usuario);

                if ($resultado) {
                    $_SESSION['success'] = "Registro exitoso. Ahora puedes iniciar sesión.";
                    header("Location: " . RUTA);
                    exit();
                } else {
                    $errores[] = "Error al registrar el usuario. Intenta nuevamente.";
                }
            }

            // Si hay errores, mostrar el formulario de registro con errores
            include 'vistas/auth/registro.php';
            return;
        }

        // Mostrar formulario de registro (GET)
        error_log("Mostrando formulario de registro");
        include 'vistas/auth/registro.php';
    }

    public function logout() {
        error_log("AuthController logout llamado");
        // Destruir todas las variables de sesión
        $_SESSION = array();

        // Destruir la sesión
        session_destroy();

        // Redirigir al login
        header("Location: " . RUTA);
        exit();
    }
}
?>