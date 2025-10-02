<?php
require_once 'modelos/libromodel.php';
require_once 'modelos/autormodel.php';
require_once 'helpers/auth.php';  // Asegúrate de incluir el archivo que contiene la función

class LibroController {
    private $libroModel;
    private $autorModel;

    public function __construct() {
        $this->libroModel = new LibroModel();
        $this->autorModel = new AutorModel();
    }

    // Catálogo de libros (requiere login; rol: usuario o admin)
    public function index() {
        requireLogin(RUTA.'tienda');
        requireRole(['usuario', 'admin']);

        $libros = $this->libroModel->getAll();
        $data = [];
        foreach ($libros as $libro) {
            $autor = $this->autorModel->getById($libro->getId_autor());
            $data[] = [
                'id_libro'   => $libro->getId_libro(),
                'titulo'     => $libro->getTitulo(),
                'autor'      => $autor ? $autor->getNombre() : 'Desconocido',
                'portada'    => $libro->getPortada(),
                'stock'      => $libro->getStock(),
                'disponible' => $libro->getDisponible(),
                'qr'         => $libro->getQr(),
                'precio'     => $libro->getPrecio(), // Añadimos el precio aquí
            ];
        }
        include 'vistas/libros/index.php';
    }

    // Crear un nuevo libro
    public function create() {
        $autores = $this->autorModel->getAll();
        $id_autor_seleccionado = isset($_GET['id_autor']) ? $_GET['id_autor'] : null;
        include 'vistas/libros/create.php';
    }

    // Almacenar un libro nuevo
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $titulo = trim($_POST['titulo']);
            $id_autor = $_POST['id_autor'];
            $portada = trim($_POST['portada']);
            $stock = isset($_POST['stock']) ? (int)$_POST['stock'] : 1;
            $disponible = isset($_POST['disponible']) ? 1 : 0;
            $precio = isset($_POST['precio']) ? (float)$_POST['precio'] : 0.00; // Obtenemos el precio del formulario

            $errores = [];
            if ($stock < 1) {
                $errores[] = "El stock debe ser mayor que cero.";
            }
            if (empty($titulo)) $errores[] = "El título es obligatorio.";
            if (empty($id_autor)) $errores[] = "Debes seleccionar un autor.";

            if (!empty($errores)) {
                $errors = $errores;
                $autores = $this->autorModel->getAll();
                include 'vistas/libros/create.php';
                return;
            }

            // Crear el libro con precio
            $nuevoLibro = new Libro(null, $titulo, $id_autor, $portada, $stock, $disponible, null, $precio);
            $this->libroModel->insert($nuevoLibro);

            // Obtener el ID del libro recién creado
            $libros = $this->libroModel->getAll();
            $ultimoLibro = end($libros);
            $id_libro = $ultimoLibro->getId_libro();

            // Generar la URL para el QR que redirige a la tienda
            $urlDetalle = RUTA . "tienda/show/" . $id_libro;

            // Generar el QR y guardar la imagen
            require_once 'libs/phpqrcode/qrlib.php';
            $qrPath = "public/qrs/libro_" . $id_libro . ".png";
            \QRcode::png($urlDetalle, $qrPath, QR_ECLEVEL_L, 4);

            // Actualizar el libro con la ruta del QR
            $ultimoLibro->setQr($qrPath);
            $this->libroModel->update($ultimoLibro);
        }
        $this->index();
    }

    // Editar un libro
    public function edit($id) {
        $libro = $this->libroModel->getById($id);
        $autores = $this->autorModel->getAll();
        include 'vistas/libros/edit.php';
    }

    // Mostrar un libro en detalle
    public function show($id) {
        requireLogin(RUTA . "libro/show/" . $id); // Requiere login

        // Verificar el rol del usuario y redirigirlo
        if ($_SESSION['usuario_rol'] === 'usuario') {
            // Si es un usuario, llevarlo a la vista de detalles del libro en Tienda
            $libro = $this->libroModel->getById($id);
            $autor = $this->autorModel->getById($libro->getId_autor());
            include 'vistas/tienda/show.php'; // Vista del libro para usuarios
        } else {
            // Si es admin, mostrar la vista de admin
            $libro = $this->libroModel->getById($id);
            $autor = $this->autorModel->getById($libro->getId_autor());
            include 'vistas/libros/show.php'; // Vista del libro para admins
            include 'vistas/layout.php'; // Si es admin, mostrar layout de admin
        }
    }

    // Actualizar un libro
    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $titulo = trim($_POST['titulo']);
            $id_autor = $_POST['id_autor'];
            $portada = trim($_POST['portada']);
            $stock = isset($_POST['stock']) ? (int)$_POST['stock'] : 1;
            $disponible = isset($_POST['disponible']) ? 1 : 0;
            $precio = isset($_POST['precio']) ? (float)$_POST['precio'] : 0.00; // Obtenemos el precio del formulario

            $errores = [];
            if ($stock < 1) {
                $errores[] = "El stock debe ser mayor que cero.";
            }
            if (empty($titulo)) $errores[] = "El título es obligatorio.";
            if (empty($id_autor)) $errores[] = "Debes seleccionar un autor.";

            if (!empty($errores)) {
                $errors = $errores;
                $libro = $this->libroModel->getById($id);
                $autores = $this->autorModel->getAll();
                include 'vistas/libros/edit.php';
                return;
            }

            $libro = new Libro($id, $titulo, $id_autor, $portada, $stock, $disponible, null, $precio);

            // Regenerar QR solo si el checkbox fue marcado
            if (isset($_POST['regenerar_qr'])) {
                $urlDetalle = RUTA . "tienda/show/" . $id; // Siempre redirigir a 'tienda/show/{id_libro}'
                require_once 'libs/phpqrcode/qrlib.php';
                $qrPath = "public/qrs/libro_" . $id . ".png";
                \QRcode::png($urlDetalle, $qrPath, QR_ECLEVEL_L, 4);
                $libro->setQr($qrPath);
            }

            $this->libroModel->update($libro);
        }
        $this->index();
    }

    // Eliminar un libro
    public function delete($id) {
        $libro = $this->libroModel->getById($id);
        if ($libro) {
            $this->libroModel->delete($libro);
        }
        $this->index();
    }
}
