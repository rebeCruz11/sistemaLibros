<?php
require_once 'modelos/usuariomodel.php';

class Usuariocontroller {
    private $usuarioModel;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $this->usuarioModel = new UsuarioModel();
    }

    public function crearAdmin() {
        // Solo admin puede acceder
        if (!isset($_SESSION['usuario_rol']) || $_SESSION['usuario_rol'] !== 'admin') {
            header("Location: " . RUTA);
            exit();
        }

        $errores = [];
        $exito = "";

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = trim($_POST['nombre'] ?? '');
            $apellido = trim($_POST['apellido'] ?? '');
            $correo = trim($_POST['correo'] ?? '');
            $contrasena = trim($_POST['contrasena'] ?? '');
            $confirmar = trim($_POST['confirmar_contrasena'] ?? '');

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
                $usuario = new Usuario(null, $nombre, $apellido, $correo, $contrasena, 'admin');
                if ($this->usuarioModel->insert($usuario)) {
                    $exito = "Administrador creado correctamente.";
                } else {
                    $errores[] = "Error al crear el administrador.";
                }
            }
        }

        require "vistas/usuario/crearAdmin.php";
    }
}
?>