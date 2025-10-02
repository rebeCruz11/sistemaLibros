<?php

class Contenido {
    public static $contenido = [        
        "libro" => "controladores/librocontroller.php",
        "categoria" => "controladores/categoriacontroller.php",
        "libroCategoria" => "controladores/libroCategoriacontroller.php",
        "autor" => "controladores/autorcontroller.php",

    ];    

    public static function obtenerContenido($clave) {
        $vista=self::$contenido[$clave] ?? null;
        return $vista ?: "vistas/404.php";                
    }

}

?>