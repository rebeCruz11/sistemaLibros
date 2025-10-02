<?php
// ======================
// CLASE USUARIO
// ======================
class Usuario {
    private $id_usuario;
    private $nombre;
    private $apellido;
    private $correo;
    private $contrasena;
    private $rol;

    public function __construct($id_usuario = null, $nombre = null, $apellido = null, $correo = null, $contrasena = null, $rol = 'usuario') {
        $this->id_usuario = $id_usuario;
        $this->nombre = $nombre;
        $this->apellido = $apellido;
        $this->correo = $correo;
        $this->contrasena = $contrasena;
        $this->rol = $rol;
    }

    // Getters
    public function getId_usuario() {
        return $this->id_usuario;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function getApellido() {
        return $this->apellido;
    }

    public function getCorreo() {
        return $this->correo;
    }

    public function getContrasena() {
        return $this->contrasena;
    }

    public function getRol() {
        return $this->rol;
    }

    // Setters
    public function setId_usuario($id_usuario) {
        $this->id_usuario = $id_usuario;
    }

    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    public function setApellido($apellido) {
        $this->apellido = $apellido;
    }

    public function setCorreo($correo) {
        $this->correo = $correo;
    }

    public function setContrasena($contrasena) {
        $this->contrasena = $contrasena;
    }

    public function setRol($rol) {
        $this->rol = $rol;
    }
}
?>