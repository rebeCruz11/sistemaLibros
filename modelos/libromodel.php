<?php
/*
CREATE TABLE libros (
    id_libro INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(150) NOT NULL,
    id_autor INT NOT NULL,
    portada VARCHAR(255),
    stock INT DEFAULT 1,         -- cantidad disponible
    disponible TINYINT(1) DEFAULT 1, -- 1=disponible, 0=no disponible
    FOREIGN KEY (id_autor) REFERENCES autores(id_autor)
        ON UPDATE CASCADE
        ON DELETE CASCADE
);*/
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
            $libros[] = new Libro($row['id_libro'], $row['titulo'], $row['id_autor'], $row['portada'], $row['stock'], $row['disponible'], $row['qr']);
        }
        return $libros;
    }

    public function getById($id) {
        $sql = "SELECT * FROM libros WHERE id_libro = ?";
        $results = $this->cn->consulta($sql, [$id]);
        if (!empty($results)) {
            $row = $results[0];
            return new Libro($row['id_libro'], $row['titulo'], $row['id_autor'], $row['portada'], $row['stock'], $row['disponible'], $row['qr']);
        }
        return null;
    }

    public function insert($libroObj) {
        $sql = "INSERT INTO libros (titulo, id_autor, portada, stock, disponible, qr) VALUES (?, ?, ?, ?, ?, ?)";
        return $this->cn->ejecutar($sql, [
            $libroObj->getTitulo(),
            $libroObj->getId_autor(),
            $libroObj->getPortada(),
            $libroObj->getStock(),
            $libroObj->getDisponible(),
            $libroObj->getQr()
        ]);
    }

    public function update($libroObj) {
        $sql = "UPDATE libros SET titulo = ?, id_autor = ?, portada = ?, stock = ?, disponible = ?, qr = ? WHERE id_libro = ?";
        return $this->cn->ejecutar($sql, [
            $libroObj->getTitulo(),
            $libroObj->getId_autor(),
            $libroObj->getPortada(),
            $libroObj->getStock(),
            $libroObj->getDisponible(),
            $libroObj->getQr(),
            $libroObj->getId_libro()
        ]);
    }



    public function delete($libroObj) {
        $sql = "DELETE FROM libros WHERE id_libro = ?";
        return $this->cn->ejecutar($sql, [$libroObj->getId_libro()]);
    }
}
?>