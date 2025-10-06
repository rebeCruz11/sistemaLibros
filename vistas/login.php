<?php
ob_start();
?>
<div class="login-container">
    <div class="login-card">
        <h2 class="text-center mb-4 text-gradient fw-bold">Iniciar Sesión</h2>
        <?php if (isset($_SESSION['error_login'])): ?>
            <div class="alert alert-danger">
                <?= $_SESSION['error_login']; ?>
                <?php unset($_SESSION['error_login']); ?>
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
            <button type="submit" class="btn bg-primary btn-login mb-3">Iniciar Sesión</button>
        </form>
        <div class="text-center">
            <small>¿No tienes cuenta? <a href="<?= RUTA; ?>auth/registro">Regístrate aquí</a></small>
        </div>
    </div>
</div>
<?php
$contenidoVista = ob_get_clean();
$titulo = "Iniciar Sesión";
$ocultarNavbar = true;
include 'vistas/layout.php';
?>