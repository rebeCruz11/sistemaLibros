<?php
require_once 'config/cn.php';
require_once 'clases/autor.php';

class AutorModel {
    private $cn;

    public function __construct() {
        $this->cn = new CNpdo();
    }

    public function getAll() {
        $sql = "SELECT * FROM autores";
        $results = $this->cn->consulta($sql);
        $autor = [];
        foreach ($results as $row) {
            $autor[] = new Autor($row['id_autor'], $row['nombre'], $row['nacionalidad']);
        }
        return $autor;
    }

    public function getById($id) {
        $sql = "SELECT * FROM autores WHERE id_autor = ?";
        $results = $this->cn->consulta($sql, [$id]);
        if (!empty($results)) {
            $row = $results[0];
            return new Autor($row['id_autor'], $row['nombre'], $row['nacionalidad']);
        }
        return null;
    }

    public function insert($autorobj) {
        $sql = "INSERT INTO autores (nombre, nacionalidad) VALUES (?, ?)";
        $autorobj->getNombre();
        return $this->cn->ejecutar($sql, [$autorobj->getNombre(), $autorobj->getNacionalidad()]);
    }

    public function update($autorobj) {
        $sql = "UPDATE autores SET nombre = ?, nacionalidad = ? WHERE id_autor = ?";
        return $this->cn->ejecutar($sql, [$autorobj->getNombre(), $autorobj->getNacionalidad(), $autorobj->getId_autor()]);
    }

    public function delete($autorobj) {
        $sql = "DELETE FROM autores WHERE id_autor = ?";
        return $this->cn->ejecutar($sql, [$autorobj->getId_autor()]);
    }
}
?>
