<?php
require_once 'modelos/categoriamodel.php';

class CategoriaController {
    private $categoriaModel;

    public function __construct() {
        $this->categoriaModel = new CategoriaModel();
    }

    public function index() {
        $categorias = $this->categoriaModel->getAll();
        include 'vistas/categorias/index.php';
    }

    public function create() {
        include 'vistas/categorias/create.php';
    }
    public function edit($id) {
        $categoria = $this->categoriaModel->getById($id);
        include 'vistas/categorias/edit.php';
    }
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nombre = trim($_POST['nombre']);

            $errores = [];
            if (empty($nombre)) $errores[] = "El nombre es obligatorio.";
            if (!preg_match("/^[a-zA-Z\sáéíóúÁÉÍÓÚñÑ]+$/", $nombre)) $errores[] = "El nombre solo debe contener letras y espacios.";

            if (!empty($errores)) {
                $errors = $errores;
                include 'vistas/categorias/create.php';
                return;
            }

            $categoria = new Categoria(null, $nombre);
            $this->categoriaModel->insert($categoria);

            // 👇 Recargar lista antes de enviar a la vista
            $categorias = $this->categoriaModel->getAll();
            include 'vistas/categorias/index.php';
        }
    }
    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nombre = trim($_POST['nombre']);

            $errores = [];
            if (empty($nombre)) $errores[] = "El nombre es obligatorio.";
            if (!preg_match("/^[a-zA-Z\sáéíóúÁÉÍÓÚñÑ]+$/", $nombre)) $errores[] = "El nombre solo debe contener letras y espacios.";

            if (!empty($errores)) {
                $errors = $errores;
                $categoria = $this->categoriaModel->getById($id);
                include 'vistas/categorias/edit.php';
                return;
            }

            $categoria = new Categoria($id, $nombre);
            $this->categoriaModel->update($categoria);

            // 👇 Recargar lista antes de enviar a la vista
            $categorias = $this->categoriaModel->getAll();
            include 'vistas/categorias/index.php';
        }
    }
    public function delete($id) {
        $categoria = $this->categoriaModel->getById($id);
            if ($categoria) {
                $this->categoriaModel->delete($categoria);
            }
            // 👇 Recargar lista antes de enviar a la vista
            $categorias = $this->categoriaModel->getAll();
            include 'vistas/categorias/index.php';
        }
}
?>