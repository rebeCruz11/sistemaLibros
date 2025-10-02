<?php

class Contenido {
    public static $contenido = [        
        "libro" => "controladores/librocontroller.php",
        "categoria" => "controladores/categoriacontroller.php",
        "libroCategoria" => "controladores/libroCategoriacontroller.php",
        "autor" => "controladores/autorcontroller.php",
        "detalleVenta" => "controladores/detalleVentacontroller.php",
        "venta" => "controladores/ventacontroller.php",
        "usuario" => "controladores/usuariocontroller.php",
        'auth' => 'controladores/authcontroller.php',
        'login' => 'controladores/authcontroller.php',
        'registro' => 'controladores/authcontroller.php',
        "usuario" => "controladores/usuariocontroller.php",


    ];    

    public static function obtenerContenido($clave) {
        $vista=self::$contenido[$clave] ?? null;
        return $vista ?: "vistas/404.php";                
    }

}

?>