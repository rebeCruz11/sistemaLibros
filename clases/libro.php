<?php
class Libro {
    private $id_libro;
    private $titulo;
    private $id_autor;
    private $portada;
    private $stock;
    private $disponible;

    public function __construct($id_libro = null, $titulo = null, $id_autor = null, $portada = null, $stock = 1, $disponible = 1) {
        $this->id_libro = $id_libro;
        $this->titulo = $titulo;
        $this->id_autor = $id_autor;
        $this->portada = $portada;
        $this->stock = $stock;
        $this->disponible = $disponible;
    }

    public function getId_libro() {
        return $this->id_libro;
    }

    public function setId_libro($id_libro) {
        $this->id_libro = $id_libro;
    }

    public function getTitulo() {
        return $this->titulo;
    }

    public function setTitulo($titulo) {
        $this->titulo = $titulo;
    }

    public function getId_autor() {
        return $this->id_autor;
    }

    public function setId_autor($id_autor) {
        $this->id_autor = $id_autor;
    }

    public function getPortada() {
        return $this->portada;
    }

    public function setPortada($portada) {
        $this->portada = $portada;
    }

    public function getStock() {
        return $this->stock;
    }

    public function setStock($stock) {
        $this->stock = $stock;
    }

    public function getDisponible() {
        return $this->disponible;
    }

    public function setDisponible($disponible) {
        $this->disponible = $disponible;
    }
}
?>