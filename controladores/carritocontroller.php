<?php
require_once 'helpers/auth.php';
require_once 'modelos/libromodel.php';
require_once 'modelos/ventamodel.php';        // Modelo de ventas
require_once 'modelos/detalleventamodel.php'; // Modelo de detalles de ventas

class CarritoController {
    private $libroModel;
    private $ventaModel;
    private $detalleModel;

    public function __construct() {
        $this->libroModel = new LibroModel();
        $this->ventaModel = new VentaModel();
        $this->detalleModel = new DetalleVentaModel();
        if (session_status() === PHP_SESSION_NONE) session_start();
        $_SESSION['carrito'] = $_SESSION['carrito'] ?? [];  // Iniciar carrito si no existe
    }

    // Ver el carrito
    public function ver() {
        requireLogin(RUTA.'carrito/ver');  // Requiere login
        requireRole(['usuario','admin']); // Solo accesible para usuarios y administradores

        $items = $_SESSION['carrito']; // Obtener los items en el carrito
        include 'vistas/carrito/index.php'; // Mostrar la vista del carrito
    }

    // Generar factura PDF de una venta
    public function facturaPDF($idVenta) {
        requireLogin(RUTA . 'carrito/facturaPDF/' . $idVenta);
        requireRole(['usuario','admin']);

        require_once __DIR__ . '/../reportes/factura_pdf.php';
        generarFacturaPDF((int)$idVenta);
    }


    // Agregar un libro al carrito
    public function agregar($id) {
        requireLogin(RUTA.'tienda');  // Requiere login
        requireRole(['usuario','admin']);  // Solo accesible para usuarios y administradores

        // Obtener el libro desde el modelo
        $libro = $this->libroModel->getById($id);

        if (!$libro || !$libro->getDisponible()) {
            // Si el libro no existe o no está disponible
            header("Location: " . RUTA . "tienda");
            exit();
        }

        // Obtener el precio directamente desde la base de datos
        $precioUnitario = $libro->getPrecio();  // Aquí obtenemos el precio del libro directamente

        if (!isset($_SESSION['carrito'][$id])) {
            $_SESSION['carrito'][$id] = [
                'id_libro' => $id,
                'titulo'   => $libro->getTitulo(),
                'portada'  => $libro->getPortada(),
                'cantidad' => 1,
                'precio'   => $precioUnitario,
                'stock'    => $libro->getStock()
            ];
        } else {
            // Si el libro ya está en el carrito, aumentamos la cantidad respetando el stock
            $cantidadActual = $_SESSION['carrito'][$id]['cantidad'];
            $stockDisponible = $libro->getStock();
            if ($cantidadActual < $stockDisponible) {
                $_SESSION['carrito'][$id]['cantidad']++;
            }
        }

        // Redirigir al carrito
        header("Location: " . RUTA . "carrito/ver");
        exit();
    }

    // Quitar un libro del carrito
    public function quitar($id) {
        requireLogin(RUTA.'carrito/ver');    // Requiere login
        requireRole(['usuario','admin']);    // Solo accesible para usuarios y administradores
        
        unset($_SESSION['carrito'][$id]);  // Eliminar el libro del carrito

        // Redirigir al carrito
        header("Location: " . RUTA . "carrito/ver");
        exit();
    }

    // Actualizar la cantidad de los productos en el carrito
    public function actualizar() {
        requireLogin(RUTA.'carrito/ver');    // Requiere login
        requireRole(['usuario','admin']);    // Solo accesible para usuarios y administradores
        
        foreach (($_POST['cant'] ?? []) as $id => $cant) {
            // Validar y actualizar la cantidad
            $cant = max(1, (int)$cant);
            if (isset($_SESSION['carrito'][$id])) {
                $stock = (int)$_SESSION['carrito'][$id]['stock'];
                $_SESSION['carrito'][$id]['cantidad'] = min($cant, $stock);
            }
        }

        // Redirigir al carrito
        header("Location: " . RUTA . "carrito/ver");
        exit();
    }

    // Vaciar el carrito
    public function vaciar() {
        requireLogin(RUTA.'carrito/ver');  // Requiere login
        requireRole(['usuario','admin']);  // Solo accesible para usuarios y administradores

        $_SESSION['carrito'] = [];  // Vaciar el carrito

        // Redirigir al carrito
        header("Location: " . RUTA . "carrito/ver");
        exit();
    }

    // Realizar el checkout (confirmar compra)
    public function checkout() {
        requireLogin(RUTA.'carrito/ver');   // Requiere login
        requireRole(['usuario','admin']);   // Solo accesible para usuarios y administradores

        $carrito = $_SESSION['carrito'];
        if (empty($carrito)) {
            // Si el carrito está vacío, redirigir al carrito
            header("Location: " . RUTA . "carrito/ver");
            exit();
        }

        // Obtener el nombre del cliente o usar el nombre de sesión
        $clienteNombre = trim($_POST['cliente_nombre'] ?? '');
        if ($clienteNombre === '') $clienteNombre = $_SESSION['usuario_nombre'] ?? 'Cliente';

        // Calcular el total de la compra
        $total = 0;
        foreach ($carrito as $it) {
            $total += ($it['precio'] * $it['cantidad']);
        }

        // Crear venta en la base de datos
        $idUsuario = (int)($_SESSION['usuario_id'] ?? 0);
        $idVenta = $this->ventaModel->crearVenta($idUsuario, $clienteNombre, $total);

        // Crear los detalles de la venta
        foreach ($carrito as $it) {
            $this->detalleModel->crearDetalle($idVenta, (int)$it['id_libro'], (int)$it['cantidad'], (float)$it['precio']);

            // Opcional: Disminuir el stock de los libros vendidos
            $libro = $this->libroModel->getById((int)$it['id_libro']);
            if ($libro) {
                $libro->setStock(max(0, $libro->getStock() - (int)$it['cantidad']));
                $this->libroModel->update($libro);
            }
        }

        // Vaciar el carrito después de la compra
        $_SESSION['carrito'] = [];

        // Redirigir a la página de "gracias" o al detalle de la venta
        header("Location: " . RUTA . "carrito/gracias/" . $idVenta);
        exit();
    }
    // CarritoController.php
    public function misCompras() {
        requireLogin(RUTA . 'carrito/misCompras');  // Requiere login
        requireRole(['usuario', 'admin']);  // Solo accesible para usuarios y administradores

        // Obtener las compras del usuario
        $idUsuario = $_SESSION['usuario_id'];
        $compras = $this->ventaModel->getComprasByUsuario($idUsuario);

        // Mostrar la vista de Mis Compras
        include 'vistas/carrito/misCompras.php';  
    }


    // Página de agradecimiento después del checkout
    public function gracias($idVenta) {
        requireLogin(RUTA.'tienda');  // Requiere login
        requireRole(['usuario','admin']);  // Solo accesible para usuarios y administradores

        $ventaId = (int)$idVenta;  // Obtener el ID de la venta
        include 'vistas/carrito/gracias.php';  // Mostrar la vista de agradecimiento
    }
   // CarritoController.php
public function detalle($idVenta) {
    requireLogin(RUTA . 'carrito/detalle/' . $idVenta);  // Requiere login
    requireRole(['usuario', 'admin']);  // Solo accesible para usuarios y administradores

    // Obtener la venta y los detalles
    $venta = $this->ventaModel->getById($idVenta);
    $detalles = $this->detalleModel->getDetallesByVenta($idVenta);

    // Mostrar la vista con los detalles de la compra
    include 'vistas/carrito/detalle.php';  
}


    
}
