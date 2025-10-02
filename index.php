
<?php 
define("RUTA", "http://192.168.1.9/Lab1P2_2022CP602_2022HZ651/");
//archivos de configuracion
require_once "config/rutas.php";

//objetos 

$contenido = new Contenido();

?>
<style>
.text-gradient {
    background: linear-gradient(45deg, #6a11cb, #2575fc);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}
.btn-gradient {
    background: linear-gradient(45deg, #6a11cb, #2575fc);
    border: none;
    color: #fff !important;
    transition: 0.3s;
}
.btn-gradient:hover {
    background: linear-gradient(45deg, #5b0ea6, #1d5fd6);
    color: #fff !important;
}
.table-gradient {
    background: linear-gradient(45deg, #6a11cb, #2575fc);
    color: #fff;
}

</style>

<!doctype html>
<html lang="en">
    <head>
        <title>Title</title>
        <!-- Required meta tags -->
        <meta charset="utf-8" />
        <meta
            name="viewport"
            content="width=device-width, initial-scale=1, shrink-to-fit=no"
        />

        <!-- Bootstrap CSS v5.2.1 -->
        <link
            href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
            rel="stylesheet"
            integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN"
            crossorigin="anonymous"
        />
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    </head>

    <body>
        <header>
            <!-- place navbar here -->
             <nav
                class="navbar navbar-expand-sm navbar-light bg-light"
             >
                <div class="container">
                    <a class="navbar-brand" href="index.php">INICIO</a>
                    <button
                        class="navbar-toggler d-lg-none"
                        type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#collapsibleNavId"
                        aria-controls="collapsibleNavId"
                        aria-expanded="false"
                        aria-label="Toggle navigation"
                    >
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="collapsibleNavId">
                        <ul class="navbar-nav me-auto mt-2 mt-lg-0">
                            <li class="nav-item">
                                <a class="nav-link active" href="<?= RUTA;?>autor" aria-current="page"
                                    >Autor
                                    <span class="visually-hidden">(current)</span></a
                                >
                            </li>
                            <li class="nav-item">
                                <a class="nav-link active" href="<?= RUTA;?>categoria" aria-current="page"
                                    >Categoría
                                    <span class="visually-hidden">(current)</span></a
                                >
                            </li>
                            <li class="nav-item">
                                <a class="nav-link active" href="<?= RUTA;?>libro" aria-current="page"
                                    >Libro
                                    <span class="visually-hidden">(current)</span></a
                                >
                            </li>
                            <li class="nav-item">
                                <a class="nav-link active" href="<?= RUTA;?>libroCategoria" aria-current="page"
                                    >Libro-Categoría
                                    <span class="visually-hidden">(current)</span></a
                                >
                            </li>
                            <!--
                            <li class="nav-item dropdown">
                                <a
                                    class="nav-link dropdown-toggle"
                                    href="#"
                                    id="dropdownId"
                                    data-bs-toggle="dropdown"
                                    aria-haspopup="true"
                                    aria-expanded="false"
                                    >Dropdown</a
                                >
                                <div
                                    class="dropdown-menu"
                                    aria-labelledby="dropdownId"
                                >
                                    <a class="dropdown-item" href="#"
                                        >Action 1</a
                                    >
                                    <a class="dropdown-item" href="#"
                                        >Action 2</a
                                    >
                                </div>
                            </li>
                            -->
                        </ul>
                        
                    </div>
                </div>
             </nav>
             
        </header>
        <main>

            <div class="container mt-4">
<!-- place main content here -->
            <?php 
            if (isset($_GET["url"])) {               

                $datos=explode("/",$_GET["url"]) ;
                $pagina=$datos[0];
                $accion=$datos[1] ?? "index";


                //return;
                require_once $contenido->obtenerContenido($pagina);
                
                $nombreClase= $pagina."controller";
                if(class_exists($nombreClase)){
                    $controlador= new $nombreClase();

                    if(method_exists($controlador,$accion)){

                        if(isset($datos[2])){
                            $controlador->{$accion}($datos[2]);
                        }else{
                            $controlador->{$accion}();
                        }
                    }
                }else{
                    require_once "vistas/404.php";
                }                                   
            }else{
                require_once "vistas/inicio.php";
            }
            ?>
            </div>

            

        </main>
        <footer>
            <!-- place footer here -->
        </footer>
        <!-- Bootstrap JavaScript Libraries -->
        <script
            src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
            integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
            crossorigin="anonymous"
        ></script>

        <script
            src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
            integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+"
            crossorigin="anonymous"
        ></script>
    </body>
</html>