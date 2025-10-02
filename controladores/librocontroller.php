<?php 
require_once 'modelos/libromodel.php';
require_once 'modelos/autormodel.php';

class LibroController {
    private $libroModel;
    private $autorModel;

    public function __construct() {
        $this->libroModel = new LibroModel();
        $this->autorModel = new AutorModel();
    }

    public function index() {
        $libros = $this->libroModel->getAll();
        $data = [];
        foreach ($libros as $libro) {
            $autor = $this->autorModel->getById($libro->getId_autor());
            $data[] = [
                'id_libro' => $libro->getId_libro(),
                'titulo' => $libro->getTitulo(),
                'autor' => $autor ? $autor->getNombre() : 'Desconocido',
                'portada' => $libro->getPortada(),
                'stock' => $libro->getStock(),
                'disponible' => $libro->getDisponible()
            ];
        }
        include 'vistas/libros/index.php';
    }

    public function create() {
        $autores = $this->autorModel->getAll();
        $id_autor_seleccionado = isset($_GET['id_autor']) ? $_GET['id_autor'] : null;
        include 'vistas/libros/create.php';
    }


    public function store() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $titulo = trim($_POST['titulo']);
            $id_autor = $_POST['id_autor'];
            $portada = trim($_POST['portada']);
            $stock = isset($_POST['stock']) ? (int)$_POST['stock'] : 1;
            $disponible = isset($_POST['disponible']) ? 1 : 0;

            $errores = [];
            if (empty($titulo)) $errores[] = "El título es obligatorio.";
            if (empty($id_autor)) $errores[] = "Debes seleccionar un autor.";

            if (!empty($errores)) {
                $errors = $errores;
                $autores = $this->autorModel->getAll();
                include 'vistas/libros/create.php';
                return;
            }

            $nuevoLibro = new Libro(null, $titulo, $id_autor, $portada, $stock, $disponible);
            $this->libroModel->insert($nuevoLibro);
        }
        $this->index();
    }

    public function edit($id) {
        $libro = $this->libroModel->getById($id);
        $autores = $this->autorModel->getAll();
        include 'vistas/libros/edit.php';
    }
    public function show($id) {
        $libro = $this->libroModel->getById($id);
        $autor = $this->autorModel->getById($libro->getId_autor());
        include 'vistas/libros/show.php';
    }
    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $titulo = trim($_POST['titulo']);
            $id_autor = $_POST['id_autor'];
            $portada = trim($_POST['portada']);
            $stock = isset($_POST['stock']) ? (int)$_POST['stock'] : 1;
            $disponible = isset($_POST['disponible']) ? 1 : 0;

            $errores = [];
            if (empty($titulo)) $errores[] = "El título es obligatorio.";
            if (empty($id_autor)) $errores[] = "Debes seleccionar un autor.";

            if (!empty($errores)) {
                $errors = $errores;
                $libro = $this->libroModel->getById($id);
                $autores = $this->autorModel->getAll();
                include 'vistas/libros/edit.php';
                return;
            }

            $libro = new Libro($id, $titulo, $id_autor, $portada, $stock, $disponible);
            $this->libroModel->update($libro);
        }
        $this->index();
    }

    public function delete($id) {
        $libro = $this->libroModel->getById($id);
        if ($libro) {
            $this->libroModel->delete($libro);
        }
        $this->index();
    }
}
?>
