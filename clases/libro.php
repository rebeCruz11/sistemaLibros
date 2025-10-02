<?php
class Libro {
    private $id_libro;
    private $titulo;
    private $id_autor;
    private $portada;
    private $stock;
    private $disponible;
    private $qr;
    private $precio; // Asegúrate de agregar esta propiedad.

    // Constructor
    public function __construct($id_libro = null, $titulo = null, $id_autor = null, $portada = null, $stock = 1, $disponible = 1, $qr = null, $precio = 0.00) {
        $this->id_libro = $id_libro;
        $this->titulo = $titulo;
        $this->id_autor = $id_autor;
        $this->portada = $portada;
        $this->stock = $stock;
        $this->disponible = $disponible;
        $this->qr = $qr;
        $this->precio = $precio; // Asigna el precio en el constructor.
    }

    // Métodos Getters
    public function getId_libro() {
        return $this->id_libro;
    }

    public function getTitulo() {
        return $this->titulo;
    }

    public function getId_autor() {
        return $this->id_autor;
    }

    public function getPortada() {
        return $this->portada;
    }

    public function getStock() {
        return $this->stock;
    }

    public function getDisponible() {
        return $this->disponible;
    }

    public function getQr() {
        return $this->qr;
    }

    // Método para obtener el precio
    public function getPrecio() {
        return $this->precio;
    }

    // Métodos Setters
    public function setId_libro($id_libro) {
        $this->id_libro = $id_libro;
    }

    public function setTitulo($titulo) {
        $this->titulo = $titulo;
    }

    public function setId_autor($id_autor) {
        $this->id_autor = $id_autor;
    }

    public function setPortada($portada) {
        $this->portada = $portada;
    }

    public function setStock($stock) {
        $this->stock = $stock;
    }

    public function setDisponible($disponible) {
        $this->disponible = $disponible;
    }

    public function setQr($qr) {
        $this->qr = $qr;
    }

    // Método para establecer el precio
    public function setPrecio($precio) {
        $this->precio = $precio;
    }
}
?>
