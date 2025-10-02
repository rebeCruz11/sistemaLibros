<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Sistema de Libros</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .login-container {
            min-height: 100vh;
            background: linear-gradient(45deg, #6a11cb, #2575fc);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .login-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            padding: 2rem;
            width: 100%;
            max-width: 400px;
        }
        .btn-login {
            background: linear-gradient(45deg, #6a11cb, #2575fc);
            border: none;
            color: white;
            padding: 10px 0;
            width: 100%;
        }
        .btn-login:hover {
            background: linear-gradient(45deg, #5b0ea6, #1d5fd6);
            color: white;
        }
        .text-gradient {
            background: linear-gradient(45deg, #6a11cb, #2575fc);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <h2 class="text-center mb-4 text-gradient fw-bold">Iniciar Sesión</h2>
            
            <?php if (isset($_SESSION['error_login'])): ?>
                <div class="alert alert-danger">
                    <?= $_SESSION['error_login']; ?>
                    <?php unset($_SESSION['error_login']); ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success">
                    <?= $_SESSION['success']; ?>
                    <?php unset($_SESSION['success']); ?>
                </div>
            <?php endif; ?>
            
            <form action="<?= RUTA; ?>auth/login" method="POST">
                <div class="mb-3">
                    <label for="correo" class="form-label">Correo Electrónico</label>
                    <input type="email" class="form-control" id="correo" name="correo" required>
                </div>
                
                <div class="mb-3">
                    <label for="contrasena" class="form-label">Contraseña</label>
                    <input type="password" class="form-control" id="contrasena" name="contrasena" required>
                </div>
                
                <button type="submit" class="btn btn-login mb-3">Iniciar Sesión</button>
            </form>
            
            <div class="text-center">
                <small>¿No tienes cuenta? <a href="<?= RUTA; ?>auth/registro">Regístrate aquí</a></small>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>