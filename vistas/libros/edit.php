<?php
ob_start();
?>
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="fw-bold text-gradient">Editar Libro</h1>
        <a href="<?= RUTA; ?>libro" class="btn btn-secondary">← Volver</a>
    </div>

    <div class="card shadow border-0">
        <div class="card-body p-4">
            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        <?php foreach ($errors as $e): ?><li><?= $e; ?></li><?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form method="POST" action="<?= RUTA;?>libro/update/<?= $libro->getId_libro(); ?>" class="needs-validation" novalidate>
                <div class="mb-3">
                    <label for="titulo" class="form-label">Título</label>
                    <input type="text" name="titulo" id="titulo" class="form-control shadow-sm" value="<?= $libro->getTitulo(); ?>" required>
                    <div class="invalid-feedback">El título es obligatorio.</div>
                </div>

                <div class="mb-3">
                    <label for="id_autor" class="form-label">Autor</label>
                    <select name="id_autor" id="id_autor" class="form-select shadow-sm" required>
                        <option value="">Seleccione un autor</option>
                        <?php foreach ($autores as $autor): ?>
                            <option value="<?= $autor->getId_autor(); ?>" <?= $libro->getId_autor() == $autor->getId_autor() ? 'selected' : ''; ?>>
                                <?= $autor->getNombre(); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div class="invalid-feedback">Debe seleccionar un autor.</div>
                </div>

                <div class="mb-3">
                    <label for="portada" class="form-label">URL de Portada</label>
                    <input type="text" name="portada" id="portada" value="<?= $libro->getPortada(); ?>" class="form-control shadow-sm">
                </div>

                <div class="mb-3">
                    <label for="stock" class="form-label">Stock</label>
                    <input type="number" name="stock" id="stock" value="<?= $libro->getStock(); ?>" min="1" class="form-control shadow-sm">
                </div>

                <div class="mb-3">
                    <label for="precio" class="form-label">Precio</label>
                    <input type="number" name="precio" id="precio" value="<?= $libro->getPrecio(); ?>" class="form-control shadow-sm" step="0.01" min="0" required>
                </div>

                <div class="form-check mb-3">
                    <input type="checkbox" name="disponible" id="disponible" class="form-check-input" <?= $libro->getDisponible() ? 'checked' : ''; ?>>
                    <label for="disponible" class="form-check-label">Disponible</label>
                </div>
                <div class="form-check mb-3">
                    <input type="checkbox" name="regenerar_qr" id="regenerar_qr" class="form-check-input">
                    <label for="regenerar_qr" class="form-check-label">Regenerar código QR</label>
                </div>
                <button type="submit" class="btn btn-gradient px-4">Actualizar</button>
            </form>
        </div>
    </div>
</div>
<?php
$contenidoVista = ob_get_clean();
$titulo = "Editar Libro";
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
