<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1,shrink-to-fit=no"/>
  <title><?= $titulo ?? 'Tienda de Libros' ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    .text-gradient{background:linear-gradient(45deg,#6a11cb,#2575fc);-webkit-background-clip:text;-webkit-text-fill-color:transparent}
    .btn-gradient{background:linear-gradient(45deg,#6a11cb,#2575fc);border:none;color:#fff}
    .btn-gradient:hover{background:linear-gradient(45deg,#5b0ea6,#1d5fd6);color:#fff}
  </style>
</head>
<body>
<header>
  <nav class="navbar navbar-expand-sm navbar-light bg-light">
    <div class="container">
      <a class="navbar-brand" href="<?= RUTA ?>">INICIO</a>
      <ul class="navbar-nav me-auto">
        <li class="nav-item"><a class="nav-link" href="<?= RUTA;?>tienda">Catálogo</a></li>
        <li class="nav-item"><a class="nav-link" href="<?= RUTA;?>carrito/misCompras">Mis Compras</a></li>
    </li>
      </ul>
      
      <ul class="navbar-nav ms-auto">
        
        <li class="nav-item me-2">
          <a class="btn btn-outline-primary" href="<?= RUTA;?>carrito/ver">
            <i class="bi bi-cart"></i>
            Carrito <span class="badge bg-primary"><?= carritoCount(); ?></span>
          </a>
        </li>
        <li class="nav-item">
          <a class="btn btn-danger" href="<?= RUTA; ?>auth/logout">Cerrar sesión</a>
        </li>
      </ul>
    </div>
  </nav>
</header>
<main>
  <div class="container mt-4">
    <?= $contenidoVista ?? '' ?>
  </div>
</main>
<footer class="py-4"></footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
