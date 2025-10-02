<?php
require_once 'modelos/libroCategoriamodel.php';
require_once 'modelos/libromodel.php';
require_once 'modelos/categoriamodel.php';

class LibroCategoriaController {
    private $libroCategoriaModel;
    private $libroModel;
    private $categoriaModel;

    public function __construct() {
        $this->libroCategoriaModel = new LibroCategoriaModel();
        $this->libroModel = new LibroModel();
        $this->categoriaModel = new CategoriaModel();
    }

    // Mostrar todos los libros con sus categorías
    public function index() {
        $libros = $this->libroModel->getAll();
        $data = [];

        foreach ($libros as $libro) {
            $categorias = $this->libroCategoriaModel->getCategoriasByLibro($libro->getId_libro());
            $nombresCategorias = array_map(fn($c) => $c->getNombre(), $categorias);

            $data[] = [
                'libro' => $libro,
                'categorias' => $categorias,
                'nombres' => implode(', ', $nombresCategorias)
            ];
        }

        include 'vistas/librosCategorias/index.php';
    }

    // Formulario para asignar categorías a un libro
    public function create($id_libro) {
        $libro = $this->libroModel->getById($id_libro);
        if (!$libro) {
            die("Libro no encontrado");
        }

        $categorias = $this->categoriaModel->getAll();
        $categoriasLibro = $this->libroCategoriaModel->getCategoriasByLibro($id_libro);

        $idsCategoriasSeleccionadas = array_map(fn($c) => $c->getId_categoria(), $categoriasLibro);

        include 'vistas/librosCategorias/create.php';
    }

    // Guardar categorías
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_libro = $_POST['id_libro'];
            $ids_categorias = $_POST['categorias'] ?? [];

            // eliminar categorías actuales
            $this->libroCategoriaModel->deleteByLibro($id_libro);

            // insertar nuevas categorías
            foreach ($ids_categorias as $id_categoria) {
                $this->libroCategoriaModel->insert($id_libro, $id_categoria);
            }
        }
        $this->index();
    }
}

?>
