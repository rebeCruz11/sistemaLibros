<?php
require_once 'config/cn.php';
require_once 'clases/libroCategoria.php';
require_once 'modelos/categoriamodel.php';

class LibroCategoriaModel {
    private $cn;
    private $categoriaModel;

    public function __construct() {
        $this->cn = new CNpdo();
        $this->categoriaModel = new CategoriaModel();
    }

    public function getAll() {
        $sql = "SELECT * FROM libros_categorias";
        $results = $this->cn->consulta($sql);
        $libroCategorias = [];
        foreach ($results as $row) {
            $libroCategorias[] = new LibroCategoria($row['id_libro'], $row['id_categoria']);
        }
        return $libroCategorias;
    }

    public function getCategoriasByLibro($id_libro) {
        $sql = "SELECT c.* FROM categorias c
                INNER JOIN libros_categorias lc ON c.id_categoria = lc.id_categoria
                WHERE lc.id_libro = ?";
        $results = $this->cn->consulta($sql, [$id_libro]);
        $categorias = [];
        foreach ($results as $row) {
            $categorias[] = new Categoria($row['id_categoria'], $row['nombre']);
        }
        return $categorias;
    }

    public function insert($id_libro, $id_categoria) {
        $sql = "INSERT INTO libros_categorias (id_libro, id_categoria) VALUES (?, ?)";
        return $this->cn->ejecutar($sql, [$id_libro, $id_categoria]);
    }

    public function deleteByLibro($id_libro) {
        $sql = "DELETE FROM libros_categorias WHERE id_libro = ?";
        return $this->cn->ejecutar($sql, [$id_libro]);
    }
}

?>
