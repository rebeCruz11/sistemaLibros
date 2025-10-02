<?php
require_once 'modelos/autormodel.php';

class AutorController {
    private $autorModel;

    public function __construct() {
        $this->autorModel = new AutorModel();
    }

    public function index() {
        $autores = $this->autorModel->getAll();
        include 'vistas/autores/index.php';
    }

    public function create() {
        include 'vistas/autores/create.php';
    }

    public function edit($id) {
        $autor = $this->autorModel->getById($id);
        include 'vistas/autores/edit.php';
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nombre = trim($_POST['nombre']);
            $nacionalidad = trim($_POST['nacionalidad']);

            $errores = [];
            if (empty($nombre)) $errores[] = "El nombre es obligatorio.";
            
            if (empty($nacionalidad)) $errores[] = "La nacionalidad es obligatoria.";

            if (!preg_match("/^[a-zA-Z\sáéíóúÁÉÍÓÚñÑ]+$/", $nombre)) $errores[] = "El nombre solo debe contener letras y espacios.";
            if (!preg_match("/^[a-zA-Z\sáéíóúÁÉÍÓÚñÑ]+$/", $nacionalidad)) $errores[] = "La nacionalidad solo debe contener letras y espacios.";

            if (!empty($errores)) {
                $errors = $errores;
                include 'vistas/autores/create.php';
                return;
            }

            $autor = new Autor(null, $nombre, $nacionalidad);
            $this->autorModel->insert($autor);

            // 👇 Recargar lista antes de enviar a la vista
            $autores = $this->autorModel->getAll();
            include 'vistas/autores/index.php';
        }
    }

    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nombre = trim($_POST['nombre']);
            $nacionalidad = trim($_POST['nacionalidad']);

            $errores = [];
            if (empty($nombre)) $errores[] = "El nombre es obligatorio.";
            if (empty($nacionalidad)) $errores[] = "La nacionalidad es obligatoria.";
            if (!preg_match("/^[a-zA-Z\sáéíóúÁÉÍÓÚñÑ]+$/", $nombre)) $errores[] = "El nombre solo debe contener letras.";
            if (!preg_match("/^[a-zA-Z\sáéíóúÁÉÍÓÚñÑ]+$/", $nacionalidad)) $errores[] = "La nacionalidad solo debe contener letras.";

            if (!empty($errores)) {
                $autor = $this->autorModel->getById($id);
                $errors = $errores;
                include 'vistas/autores/edit.php';
                return;
            }

            $autor = new Autor($id, $nombre, $nacionalidad);
            $this->autorModel->update($autor);

            // 👇 Recargar lista
            $autores = $this->autorModel->getAll();
            include 'vistas/autores/index.php';
        }
    }

    public function delete($id) {
        $autor = $this->autorModel->getById($id);
        if ($autor) {
            $this->autorModel->delete($autor);
        }

        // 👇 Recargar lista
        $autores = $this->autorModel->getAll();
        include 'vistas/autores/index.php';
    }

}
?>
