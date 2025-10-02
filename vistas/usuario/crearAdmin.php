<?php
ob_start();
?>
<div class="row justify-content-center">
    <div class="col-md-6">
        <h2 class="mb-4 text-gradient fw-bold text-center">Crear Administrador</h2>
        <?php if (!empty($errores)): ?>
            <div class="alert alert-danger">
                <?php foreach ($errores as $error): ?>
                    <?= $error ?><br>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <?php if (!empty($exito)): ?>
            <div class="alert alert-success">
                <?= $exito ?>
            </div>
        <?php endif; ?>
        <form method="POST" action="<?= RUTA; ?>usuario/crearAdmin" class="needs-validation" novalidate>
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" name="nombre" id="nombre" class="form-control" required pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+">
            </div>
            <div class="mb-3">
                <label for="apellido" class="form-label">Apellido</label>
                <input type="text" name="apellido" id="apellido" class="form-control" required pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+">
            </div>
            <div class="mb-3">
                <label for="correo" class="form-label">Correo Electrónico</label>
                <input type="email" name="correo" id="correo" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="contrasena" class="form-label">Contraseña</label>
                <input type="password" name="contrasena" id="contrasena" class="form-control" required minlength="6">
            </div>
            <div class="mb-3">
                <label for="confirmar_contrasena" class="form-label">Confirmar Contraseña</label>
                <input type="password" name="confirmar_contrasena" id="confirmar_contrasena" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-gradient w-100">Crear Administrador</button>
        </form>
    </div>
</div>
<script>
(() => {
    'use strict'
    const forms = document.querySelectorAll('.needs-validation')
    Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
            if (!form.checkValidity()) {
                event.preventDefault()
                event.stopPropagation()
            }
            form.classList.add('was-validated')
        }, false)
    })
    document.querySelectorAll("#nombre, #apellido").forEach(input => {
        input.addEventListener("input", function() {
            this.value = this.value.replace(/[^a-zA-ZÁÉÍÓÚáéíóúÑñ\s]/g, "");
        });
    });
    const contrasena = document.getElementById('contrasena');
    const confirmarContrasena = document.getElementById('confirmar_contrasena');
    function validarContrasenas() {
        if (contrasena.value !== confirmarContrasena.value) {
            confirmarContrasena.setCustomValidity('Las contraseñas no coinciden');
        } else {
            confirmarContrasena.setCustomValidity('');
        }
    }
    contrasena.addEventListener('change', validarContrasenas);
    confirmarContrasena.addEventListener('keyup', validarContrasenas);
})();
</script>
<?php
$contenidoVista = ob_get_clean();
$titulo = "Crear Administrador";
include 'vistas/layout.php';
?>