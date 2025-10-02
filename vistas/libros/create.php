<?php
ob_start();
?>
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="fw-bold text-gradient">Agregar Libro</h1>
        <a href="<?= RUTA; ?>libro" class="btn btn-secondary">← Volver</a>
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

            <form method="POST" action="<?= RUTA; ?>libro/store" class="needs-validation" novalidate>
                
                <!-- 📖 Título -->
                <div class="mb-3">
                    <label for="titulo" class="form-label">Título</label>
                    <input 
                        type="text" 
                        name="titulo" 
                        id="titulo" 
                        class="form-control shadow-sm" 
                        required>
                    <div class="invalid-feedback">El título es obligatorio.</div>
                </div>

                <!-- 👤 Autor -->
                <div class="mb-3">
                    <label for="id_autor" class="form-label">Autor</label>
                    <select 
                        name="id_autor" 
                        id="id_autor" 
                        class="form-select shadow-sm" 
                        required 
                        <?= isset($id_autor_seleccionado) && $id_autor_seleccionado ? 'disabled' : '' ?>>
                        <option value="">Seleccione un autor</option>
                        <?php foreach ($autores as $autor): ?>
                            <option value="<?= $autor->getId_autor(); ?>"
                                <?= (isset($id_autor_seleccionado) && $autor->getId_autor() == $id_autor_seleccionado) ? 'selected' : ''; ?>>
                                <?= $autor->getNombre(); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div class="invalid-feedback">Debes seleccionar un autor.</div>
                </div>

                <!-- Campo hidden si el autor viene desde la tabla -->
                <?php if (isset($id_autor_seleccionado) && $id_autor_seleccionado): ?>
                    <input type="hidden" name="id_autor" value="<?= $id_autor_seleccionado ?>">
                <?php endif; ?>

                <!-- 🖼️ Portada -->
                <div class="mb-3">
                    <label for="portada" class="form-label">Portada (URL)</label>
                    <input 
                        type="text" 
                        name="portada" 
                        id="portada" 
                        class="form-control shadow-sm">
                </div>

                <!-- 📦 Stock -->
                <div class="mb-3">
                    <label for="stock" class="form-label">Stock</label>
                    <input 
                        type="number" 
                        name="stock" 
                        id="stock" 
                        class="form-control shadow-sm" 
                        value="1" 
                        min="1" 
                        required>
                    <div class="invalid-feedback">El stock debe ser al menos 1.</div>
                </div>

                <!-- ✅ Disponible -->
                <div class="form-check mb-3">
                    <input 
                        type="checkbox" 
                        class="form-check-input" 
                        name="disponible" 
                        id="disponible" 
                        checked>
                    <label class="form-check-label" for="disponible">Disponible</label>
                </div>

                <button type="submit" class="btn btn-gradient px-4">Guardar</button>
            </form>
        </div>
    </div>
</div>
<?php
$contenidoVista = ob_get_clean();
$titulo = "Agregar Libro";
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
