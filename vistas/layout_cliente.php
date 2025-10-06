<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1,shrink-to-fit=no"/>
  <title><?= $titulo ?? 'Tienda de Libros' ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    /* Estilo para el gradiente de texto */
    .text-gradient {
      background: linear-gradient(45deg, #6a11cb, #2575fc);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
    }

    /* Botón con gradiente */
    .btn-gradient {
      background: linear-gradient(45deg, #6a11cb, #2575fc);
      border: none;
      color: #fff;
      transition: 0.3s ease;
    }

    .btn-gradient:hover {
      background: linear-gradient(45deg, #5b0ea6, #1d5fd6);
      color: #fff;
    }

    /* Estilo para el navbar */
    .navbar {
      background-color: #ffffff;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    .navbar-brand {
      font-weight: bold;
      color: #6a11cb;
    }

    .navbar-nav .nav-link {
      color: #333;
    }

    .navbar-nav .nav-link:hover {
      color: #2575fc;
    }

    /* Asegura que el navbar sea completamente responsive */
    .navbar-toggler-icon {
      background-color: #6a11cb;
    }

    /* Estilo para el carrito y botones de sesión */
    .nav-item .btn-outline-primary {
      color: #6a11cb;
      border: 1px solid #6a11cb;
    }

    .nav-item .btn-outline-primary:hover {
      background-color: #6a11cb;
      color: #fff;
    }

    .nav-item .btn-danger {
      background-color: #e03e3e;
      border-color: #e03e3e;
    }

    .nav-item .btn-danger:hover {
      background-color: #c13a3a;
      border-color: #c13a3a;
    }
  </style>
</head>
<body>
<header>
  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-light">
    <div class="container">
      <!-- Logo o Nombre del sitio -->
      <a class="navbar-brand" href="<?= RUTA ?>">INICIO</a>
      
      <!-- Botón para móviles -->
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <!-- Enlaces del menú -->
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav me-auto">
          <li class="nav-item">
            <a class="nav-link" href="<?= RUTA; ?>tienda">Catálogo</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?= RUTA; ?>carrito/misCompras">Mis Compras</a>
          </li>
        </ul>

        <!-- Botones del carrito y logout -->
        <ul class="navbar-nav ms-auto">
          <li class="nav-item me-2">
            <a class="btn btn-outline-primary" href="<?= RUTA; ?>carrito/ver">
              <i class="bi bi-cart"></i>
              Carrito <span class="badge bg-primary"><?= carritoCount(); ?></span>
            </a>
          </li>
          <li class="nav-item">
            <a class="btn btn-danger" href="<?= RUTA; ?>auth/logout">Cerrar sesión</a>
          </li>
        </ul>
      </div>
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