<?php
ob_start();
?>
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="fw-bold text-gradient">Editar Categoría</h1>
        <a href="<?= RUTA; ?>categoria" class="btn btn-secondary">← Volver</a>
    </div>

    <div class="card shadow border-0">
        <div class="card-body p-4">
            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        <?php foreach ($errors as $e): ?>
                            <li><?= $e; ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form method="POST" action="<?= RUTA;?>categoria/update/<?= $categoria->getId_categoria(); ?>" class="needs-validation" novalidate>
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre</label>
                    <input 
                        type="text" 
                        name="nombre" 
                        id="nombre" 
                        value="<?= $categoria->getNombre(); ?>" 
                        class="form-control shadow-sm" 
                        required>
                    <div class="invalid-feedback">El nombre es obligatorio.</div>
                </div>

                <button type="submit" class="btn btn-gradient px-4">Actualizar</button>
            </form>
        </div>
    </div>
</div>
<?php
$contenidoVista = ob_get_clean();
$titulo = "Editar Categoría";
include 'vistas/layout.php'; // O layout_cliente.php según el rol
?>
<script>
// Validación Bootstrap
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
})()
</script>
