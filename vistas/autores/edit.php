<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="fw-bold text-gradient">Editar Autor</h1>
        <a href="<?= RUTA; ?>autor" class="btn btn-secondary">← Volver</a>
    </div>

    <div class="card shadow border-0">
        <div class="card-body p-4">
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger"><?= $error; ?></div>
            <?php endif; ?>

            <form method="POST" action="<?= RUTA;?>autor/update/<?= $autor->getId_autor(); ?>" class="needs-validation" novalidate>
                <input type="hidden" name="id_autor" value="<?= $autor->getId_autor(); ?>">

                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre</label>
                    <input type="text" name="nombre" id="nombre" 
                           class="form-control shadow-sm" 
                           value="<?= $autor->getNombre(); ?>" 
                           required 
                           pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+">
                    <div class="invalid-feedback">El nombre solo puede contener letras y espacios.</div>
                </div>

                <div class="mb-3">
                    <label for="nacionalidad" class="form-label">Nacionalidad</label>
                    <input type="text" name="nacionalidad" id="nacionalidad" 
                           class="form-control shadow-sm" 
                           value="<?= $autor->getNacionalidad(); ?>" 
                           required 
                           pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+">
                    <div class="invalid-feedback">La nacionalidad solo puede contener letras y espacios.</div>
                </div>

                <button type="submit" class="btn btn-gradient px-4">Actualizar</button>
            </form>
        </div>
    </div>
</div>

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
