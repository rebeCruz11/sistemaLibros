<?php
class Categoria {
    private $id_categoria;
    private $nombre;

    public function __construct($id_categoria = null, $nombre = null) {
        $this->id_categoria = $id_categoria;
        $this->nombre = $nombre;
    }

    public function getId_categoria() {
        return $this->id_categoria;
    }

    public function setId_categoria($id_categoria) {
        $this->id_categoria = $id_categoria;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }
}
?>