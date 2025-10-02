<?php
require_once 'config/cn.php';
require_once 'clases/usuario.php';

class UsuarioModel {
    private $cn;

    public function __construct() {
        $this->cn = new CNpdo();
    }

    public function getAll() {
        $sql = "SELECT * FROM usuario";
        $results = $this->cn->consulta($sql);
        $usuarios = [];
        foreach ($results as $row) {
            $usuarios[] = new Usuario(
                $row['id_usuario'],
                $row['nombre'],
                $row['apellido'],
                $row['correo'],
                $row['contrasena'],
                $row['rol']
            );
        }
        return $usuarios;
    }

    public function getById($id) {
        $sql = "SELECT * FROM usuario WHERE id_usuario = ?";
        $results = $this->cn->consulta($sql, [$id]);
        if (!empty($results)) {
            $row = $results[0];
            return new Usuario(
                $row['id_usuario'],
                $row['nombre'],
                $row['apellido'],
                $row['correo'],
                $row['contrasena'],
                $row['rol']
            );
        }
        return null;
    }

    public function getByCorreo($correo) {
        $sql = "SELECT * FROM usuario WHERE correo = ?";
        $results = $this->cn->consulta($sql, [$correo]);
        if (!empty($results)) {
            $row = $results[0];
            return new Usuario(
                $row['id_usuario'],
                $row['nombre'],
                $row['apellido'],
                $row['correo'],
                $row['contrasena'],
                $row['rol']
            );
        }
        return null;
    }

    public function verificarCredenciales($correo, $contrasena) {
        $usuario = $this->getByCorreo($correo);
        
        if ($usuario && password_verify($contrasena, $usuario->getContrasena())) {
            return $usuario;
        }
        return false;
    }

    public function insert($usuarioObj) {
        // Hashear la contraseña antes de guardar
        $contrasenaHash = password_hash($usuarioObj->getContrasena(), PASSWORD_DEFAULT);
        
        $sql = "INSERT INTO usuario (nombre, apellido, correo, contrasena, rol) VALUES (?, ?, ?, ?, ?)";
        return $this->cn->ejecutar($sql, [
            $usuarioObj->getNombre(),
            $usuarioObj->getApellido(),
            $usuarioObj->getCorreo(),
            $contrasenaHash,
            $usuarioObj->getRol()
        ]);
    }

    public function update($usuarioObj) {
        $sql = "UPDATE usuario SET nombre = ?, apellido = ?, correo = ?, rol = ? WHERE id_usuario = ?";
        return $this->cn->ejecutar($sql, [
            $usuarioObj->getNombre(),
            $usuarioObj->getApellido(),
            $usuarioObj->getCorreo(),
            $usuarioObj->getRol(),
            $usuarioObj->getId_usuario()
        ]);
    }

    public function delete($usuarioObj) {
        $sql = "DELETE FROM usuario WHERE id_usuario = ?";
        return $this->cn->ejecutar($sql, [$usuarioObj->getId_usuario()]);
    }

    public function correoExiste($correo, $excluirId = null) {
        $sql = "SELECT COUNT(*) as count FROM usuario WHERE correo = ?";
        $params = [$correo];
        
        if ($excluirId) {
            $sql .= " AND id_usuario != ?";
            $params[] = $excluirId;
        }
        
        $results = $this->cn->consulta($sql, $params);
        return $results[0]['count'] > 0;
    }
}
?>