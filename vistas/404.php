<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Error 404 - Página no encontrada</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f8f9fa;
    }
    .error-container {
      height: 100vh;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      text-align: center;
    }
    .error-code {
      font-size: 8rem;
      font-weight: bold;
      color: #dc3545;
    }
    .error-message {
      font-size: 1.5rem;
      margin-bottom: 1rem;
    }
  </style>
</head>
<body>
  <div class="error-container">
    <div class="error-code">404</div>
    <div class="error-message">Oops... Página no encontrada</div>
    <p class="text-muted">La página que buscas no existe o ha sido movida.</p>
    <a href="index.php" class="btn btn-primary mt-3">Volver al inicio</a>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>