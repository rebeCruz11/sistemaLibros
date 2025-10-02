<?php
require_once 'helpers/auth.php';
require_once 'modelos/libromodel.php';
require_once 'modelos/autormodel.php';

class TiendaController {
    private $libroModel;
    private $autorModel;

    public function __construct() {
        $this->libroModel = new LibroModel();
        $this->autorModel = new AutorModel();
    }

    // TiendaController.php

    public function index() {
        requireLogin(RUTA.'tienda');
        requireRole(['usuario','admin']);

        $libros = $this->libroModel->getAll();
        $data = [];
        foreach ($libros as $libro) {
            // Solo mostrar libros disponibles y con stock mayor a 0
            if (!$libro->getDisponible() || $libro->getStock() <= 0) continue; // Verifica que esté disponible y tenga stock

            $autor = $this->autorModel->getById($libro->getId_autor());
            $data[] = [
                'id_libro'   => $libro->getId_libro(),
                'titulo'     => $libro->getTitulo(),
                'autor'      => $autor ? $autor->getNombre() : 'Desconocido',
                'portada'    => $libro->getPortada(),
                'stock'      => $libro->getStock(),
                'disponible' => $libro->getDisponible(),
                'qr'         => $libro->getQr(),
                'precio'     => $libro->getPrecio(), // Agregar el precio
            ];
        }
        include 'vistas/tienda/index.php';
    }


    public function show($id) {
    requireLogin(RUTA . "tienda/show/" . $id); // Requiere login

    // Verificar el rol del usuario y redirigirlo
    if ($_SESSION['usuario_rol'] === 'usuario') {
        // Si es un usuario, llevarlo al detalle del libro
        $libro = $this->libroModel->getById($id);
        $autor = $this->autorModel->getById($libro->getId_autor());
        include 'vistas/tienda/show.php'; // Vista del libro
    } else {
        // Si es un admin, llevarlo al controlador adecuado
        header("Location: " . RUTA . "libro/show/" . $id);
        exit();
    }
}

}
