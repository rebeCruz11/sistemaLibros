<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    <title><?= $titulo ?? 'Sistema de Libros' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .text-gradient { background: linear-gradient(45deg, #6a11cb, #2575fc); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .btn-gradient { background: linear-gradient(45deg, #6a11cb, #2575fc); border: none; color: #fff !important; transition: 0.3s; }
        .btn-gradient:hover { background: linear-gradient(45deg, #5b0ea6, #1d5fd6); color: #fff !important; }
        .table-gradient { background: linear-gradient(45deg, #6a11cb, #2575fc); color: #fff; }
    </style>
</head>
<body>
    <header>
        <?php if (!isset($ocultarNavbar) || !$ocultarNavbar): ?>
        <nav class="navbar navbar-expand-sm navbar-light bg-light">
            <div class="container">
                <a class="navbar-brand" href="<?= RUTA ?>">INICIO</a>
                <button class="navbar-toggler d-lg-none" type="button" data-bs-toggle="collapse" data-bs-target="#collapsibleNavId">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="collapsibleNavId">
                    <ul class="navbar-nav me-auto mt-2 mt-lg-0">
                        <li class="nav-item"><a class="nav-link active" href="<?= RUTA;?>autor">Autor</a></li>
                        <li class="nav-item"><a class="nav-link active" href="<?= RUTA;?>categoria">Categoría</a></li>
                        <li class="nav-item"><a class="nav-link active" href="<?= RUTA;?>libro">Libro</a></li>
                        <li class="nav-item"><a class="nav-link active" href="<?= RUTA;?>libroCategoria">Libro-Categoría</a></li>
                        <li class="nav-item"><a class="nav-link active" href="<?= RUTA;?>usuario/crearAdmin">Crear Administrador</a></li>
                        <li class="nav-item"><a class="nav-link active" href="<?= RUTA; ?>reportes/index">📊 Reportes</a></li>
                    </ul>
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="btn btn-danger" href="<?= RUTA; ?>auth/logout">Cerrar sesión</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <?php endif; ?>
    </header>
    <main>
        <div class="container mt-4">
            <?= $contenidoVista ?? '' ?> <!-- Aquí se insertará el contenido del controlador -->
        </div>
    </main>
    <footer></footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
