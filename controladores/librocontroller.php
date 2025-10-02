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
                'disponible' => $libro->getDisponible(),
                'qr' => $libro->getQr()
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

            // 1. Crear el libro sin QR
            $nuevoLibro = new Libro(null, $titulo, $id_autor, $portada, $stock, $disponible, null);
            $this->libroModel->insert($nuevoLibro);

            // 2. Obtener el ID del libro recién creado
            $libros = $this->libroModel->getAll();
            $ultimoLibro = end($libros);
            $id_libro = $ultimoLibro->getId_libro();

            // 3. Generar la URL para el QR
            $urlDetalle = RUTA . "libro/show/" . $id_libro;

            // 4. Generar el QR y guardar la imagen
            require_once 'libs/phpqrcode/qrlib.php';
            $qrPath = "public/qrs/libro_" . $id_libro . ".png";
            \QRcode::png($urlDetalle, $qrPath, QR_ECLEVEL_L, 4);

            // 5. Actualizar el libro con la ruta del QR
            $ultimoLibro->setQr($qrPath);
            $this->libroModel->update($ultimoLibro);
        }
        $this->index();
    }

    public function edit($id) {
        $libro = $this->libroModel->getById($id);
        $autores = $this->autorModel->getAll();
        include 'vistas/libros/edit.php';
    }

    public function show($id) {
         if (!isset($_SESSION['usuario_id'])) {
        $_SESSION['redirigir_a'] = RUTA . "libro/show/" . $id;
        header("Location: " . RUTA . "auth/login");
        exit();
    }
        $libro = $this->libroModel->getById($id);
        $autor = $this->autorModel->getById($libro->getId_autor());
        include 'vistas/libros/show.php';
        if ($_SESSION['usuario_rol'] === 'admin') {
        include 'vistas/layout.php';
    } else {
        include 'vistas/layout_cliente.php';
    }
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

            // Regenerar QR solo si el checkbox fue marcado
            if (isset($_POST['regenerar_qr'])) {
                $urlDetalle = RUTA . "libro/show/" . $id;
                require_once 'libs/phpqrcode/qrlib.php';
                $qrPath = "public/qrs/libro_" . $id . ".png";
                \QRcode::png($urlDetalle, $qrPath, QR_ECLEVEL_L, 4);
                $libro->setQr($qrPath);
            }

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