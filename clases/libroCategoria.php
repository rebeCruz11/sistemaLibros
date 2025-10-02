<?php

class LibroCategoria {
    private $id_libro;
    private $id_categoria;

    public function __construct($id_libro = null, $id_categoria = null) {
        $this->id_libro = $id_libro;
        $this->id_categoria = $id_categoria;
    }

    public function getId_libro() {
        return $this->id_libro;
    }

    public function setId_libro($id_libro) {
        $this->id_libro = $id_libro;
    }

    public function getId_categoria() {
        return $this->id_categoria;
    }

    public function setId_categoria($id_categoria) {
        $this->id_categoria = $id_categoria;
    }
}
?>