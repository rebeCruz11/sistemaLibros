<?php
require_once 'config/cn.php';
require_once 'clases/categoria.php';
class CategoriaModel {
    private $cn;

    public function __construct() {
        $this->cn = new CNpdo();
    }

    public function getAll() {
        $sql = "SELECT * FROM categorias";
        $results = $this->cn->consulta($sql);
        $categorias = [];
        foreach ($results as $row) {
            $categorias[] = new Categoria($row['id_categoria'], $row['nombre']);
        }
        return $categorias;
    }

    public function getById($id) {
        $sql = "SELECT * FROM categorias WHERE id_categoria = ?";
        $results = $this->cn->consulta($sql, [$id]);
        if (!empty($results)) {
            $row = $results[0];
            return new Categoria($row['id_categoria'], $row['nombre']);
        }
        return null;
    }

    public function insert($categoriaObj) {
        $sql = "INSERT INTO categorias (nombre) VALUES (?)";
        return $this->cn->ejecutar($sql, [$categoriaObj->getNombre()]);
    }

    public function update($categoriaObj) {
        $sql = "UPDATE categorias SET nombre = ? WHERE id_categoria = ?";
        return $this->cn->ejecutar($sql, [$categoriaObj->getNombre(), $categoriaObj->getId_categoria()]);
    }

    public function delete($categoriaObj) {
        $sql = "DELETE FROM categorias WHERE id_categoria = ?";
        return $this->cn->ejecutar($sql, [$categoriaObj->getId_categoria()]);
    }
}


?>