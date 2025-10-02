<?php
require_once 'config/cn.php';
require_once 'clases/libro.php';

class LibroModel {
    private $cn;

    public function __construct() {
        $this->cn = new CNpdo();
    }

    public function getAll() {
        $sql = "SELECT * FROM libros";
        $results = $this->cn->consulta($sql);
        $libros = [];
        foreach ($results as $row) {
            // Agregar el precio al crear el objeto Libro
            $libros[] = new Libro(
                $row['id_libro'], 
                $row['titulo'], 
                $row['id_autor'], 
                $row['portada'], 
                $row['stock'], 
                $row['disponible'], 
                $row['qr'], 
                $row['precio']  // Agregar el precio aquí
            );
        }
        return $libros;
    }

    public function getById($id) {
        $sql = "SELECT * FROM libros WHERE id_libro = ?";
        $results = $this->cn->consulta($sql, [$id]);
        if (!empty($results)) {
            $row = $results[0];
            // Agregar el precio al crear el objeto Libro
            return new Libro(
                $row['id_libro'], 
                $row['titulo'], 
                $row['id_autor'], 
                $row['portada'], 
                $row['stock'], 
                $row['disponible'], 
                $row['qr'], 
                $row['precio']  // Agregar el precio aquí
            );
        }
        return null;
    }

    public function insert($libroObj) {
        $sql = "INSERT INTO libros (titulo, id_autor, portada, stock, disponible, qr, precio) VALUES (?, ?, ?, ?, ?, ?, ?)";
        return $this->cn->ejecutar($sql, [
            $libroObj->getTitulo(),
            $libroObj->getId_autor(),
            $libroObj->getPortada(),
            $libroObj->getStock(),
            $libroObj->getDisponible(),
            $libroObj->getQr(),
            $libroObj->getPrecio()  // Insertar el precio aquí
        ]);
    }

    public function update($libroObj) {
        $sql = "UPDATE libros SET titulo = ?, id_autor = ?, portada = ?, stock = ?, disponible = ?, qr = ?, precio = ? WHERE id_libro = ?";
        return $this->cn->ejecutar($sql, [
            $libroObj->getTitulo(),
            $libroObj->getId_autor(),
            $libroObj->getPortada(),
            $libroObj->getStock(),
            $libroObj->getDisponible(),
            $libroObj->getQr(),
            $libroObj->getPrecio(),  // Actualizar el precio aquí
            $libroObj->getId_libro()
        ]);
    }

    public function delete($libroObj) {
        $sql = "DELETE FROM libros WHERE id_libro = ?";
        return $this->cn->ejecutar($sql, [$libroObj->getId_libro()]);
    }
}
?>
