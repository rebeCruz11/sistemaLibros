<?php
// ======================
// CLASE AUTORES
// ======================
class Autor {
    private $id_autor;
    private $nombre;
    private $nacionalidad;

    public function __construct($id_autor = null, $nombre = null, $nacionalidad = null) {
        $this->id_autor = $id_autor;
        $this->nombre = $nombre;
        $this->nacionalidad = $nacionalidad;
    }

    public function getId_autor() {
        return $this->id_autor;
    }

    public function setId_autor($id_autor) {
        $this->id_autor = $id_autor;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    public function getNacionalidad() {
        return $this->nacionalidad;
    }

    public function setNacionalidad($nacionalidad) {
        $this->nacionalidad = $nacionalidad;
    }
}

?>