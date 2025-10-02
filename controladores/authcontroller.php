<?php
require_once 'modelos/usuariomodel.php';

class Authcontroller {
    private $usuarioModel;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $this->usuarioModel = new UsuarioModel();
    }

    public function index() {
        if (isset($_SESSION['usuario_id'])) {
            $this->redirigirSegunRol();
        } else {
            $this->mostrarLogin();
        }
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->procesarLogin();
        } else {
            $this->mostrarLogin();
        }
    }

    public function registro() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->procesarRegistro();
        } else {
            $this->mostrarRegistro();
        }
    }

    private function procesarLogin() {
        $correo = trim($_POST['correo'] ?? '');
        $contrasena = trim($_POST['contrasena'] ?? '');
        $errores = [];

        if (empty($correo)) {
            $errores[] = "El correo es obligatorio.";
        } elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            $errores[] = "El correo no es válido.";
        }
        if (empty($contrasena)) {
            $errores[] = "La contraseña es obligatoria.";
        }

        if (empty($errores)) {
            $usuario = $this->usuarioModel->verificarCredenciales($correo, $contrasena);
            if ($usuario) {
                $_SESSION['usuario_id'] = $usuario->getId_usuario();
                $_SESSION['usuario_nombre'] = $usuario->getNombre();
                $_SESSION['usuario_rol'] = $usuario->getRol();
                header("Location: " . RUTA);
                exit();
            } else {
                $errores[] = "Correo o contraseña incorrectos.";
            }
        }
        $_SESSION['error_login'] = implode("<br>", $errores);
        $this->mostrarLogin();
    }

    private function procesarRegistro() {
        $nombre = trim($_POST['nombre'] ?? '');
        $apellido = trim($_POST['apellido'] ?? '');
        $correo = trim($_POST['correo'] ?? '');
        $contrasena = trim($_POST['contrasena'] ?? '');
        $confirmar = trim($_POST['confirmar_contrasena'] ?? '');
        $errores = [];

        if (empty($nombre) || !preg_match('/^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+$/', $nombre)) {
            $errores[] = "Nombre inválido.";
        }
        if (empty($apellido) || !preg_match('/^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+$/', $apellido)) {
            $errores[] = "Apellido inválido.";
        }
        if (empty($correo) || !filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            $errores[] = "Correo inválido.";
        }
        if ($this->usuarioModel->correoExiste($correo)) {
            $errores[] = "El correo ya está registrado.";
        }
        if (empty($contrasena) || strlen($contrasena) < 6) {
            $errores[] = "La contraseña debe tener al menos 6 caracteres.";
        }
        if ($contrasena !== $confirmar) {
            $errores[] = "Las contraseñas no coinciden.";
        }

        if (empty($errores)) {
            $usuario = new Usuario(null, $nombre, $apellido, $correo, $contrasena, 'usuario');
            if ($this->usuarioModel->insert($usuario)) {
                $_SESSION['success'] = "Registro exitoso. Ahora puedes iniciar sesión.";
                header("Location: " . RUTA);
                exit();
            } else {
                $errores[] = "Error al registrar usuario.";
            }
        }
        $_SESSION['errores_registro'] = $errores;
        $this->mostrarRegistro();
    }

    private function mostrarLogin() {
        require_once "vistas/login.php";
    }

    private function mostrarRegistro() {
        require_once "vistas/auth/registro.php";
    }

    private function redirigirSegunRol() {
        header("Location: " . RUTA . "inicio");
        exit();
    }

    public function logout() {
        session_destroy();
        header("Location: " . RUTA);
        exit();
    }
}
?>