<?php
define("RUTA", "http://192.168.1.9/Lab1P2_2022CP602_2022HZ651/");
require_once "config/rutas.php";
$contenido = new Contenido();

if (session_status() === PHP_SESSION_NONE) session_start();

$url = $_GET["url"] ?? "";

if (!isset($_SESSION['usuario_id'])) {
    $datos = explode("/", $url);
    $pagina = $datos[0] ?? '';
    $accion = $datos[1] ?? '';
    if ($pagina === "auth") {
        require_once $contenido->obtenerContenido($pagina);
        $nombreClase = $pagina . "controller";
        if (class_exists($nombreClase)) {
            $controlador = new $nombreClase();
            if (method_exists($controlador, $accion)) {
                $controlador->{$accion}();
            } else {
                $controlador->index();
            }
        }
        exit();
    } else {
        require_once "vistas/login.php";
        exit();
    }
}

if (isset($_GET["url"])) {               
    $datos = explode("/", $_GET["url"]);
    $pagina = $datos[0];
    $accion = $datos[1] ?? "index";
    require_once $contenido->obtenerContenido($pagina);
    $nombreClase = $pagina . "controller";
    if (class_exists($nombreClase)) {
        $controlador = new $nombreClase();
        if (method_exists($controlador, $accion)) {
            if (isset($datos[2])) {
                $controlador->{$accion}($datos[2]);
            } else {
                $controlador->{$accion}();
            }
        }
    } else {
        require_once "vistas/404.php";
    }                                   
} else {
    // Mostrar inicio según el rol
    if ($_SESSION['usuario_rol'] === 'admin') {
        require_once "vistas/inicio.php";
    } else {
        require_once "vistas/inicio_cliente.php";
    }
}
?>