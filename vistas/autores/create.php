<?php
ob_start();
?>
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="fw-bold text-gradient">Agregar Autor</h1>
        <a href="<?= RUTA; ?>autor" class="btn btn-secondary">← Volver</a>
    </div>

    <div class="card shadow border-0">
        <div class="card-body p-4">
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger"><?= $error; ?></div>
            <?php endif; ?>

            <form method="POST" action="<?= RUTA;?>autor/store" class="needs-validation" novalidate>
                
                <!-- Nombre -->
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre</label>
                    <input 
                        type="text" 
                        name="nombre" 
                        id="nombre" 
                        class="form-control shadow-sm" 
                        required
                        pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+"
                        title="Solo se permiten letras y espacios">
                    <div class="invalid-feedback">El nombre solo puede contener letras y espacios.</div>
                </div>

                <!-- Nacionalidad -->
                <div class="mb-3">
                    <label for="nacionalidad" class="form-label">Nacionalidad</label>
                    <input 
                        type="text" 
                        name="nacionalidad" 
                        id="nacionalidad" 
                        class="form-control shadow-sm" 
                        required
                        pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+"
                        title="Solo se permiten letras y espacios">
                    <div class="invalid-feedback">La nacionalidad solo puede contener letras y espacios.</div>
                </div>

                <button type="submit" class="btn btn-gradient px-4">Guardar</button>
            </form>
        </div>
    </div>
</div>
<?php
$contenidoVista = ob_get_clean();
$titulo = "Agregar Autor";
include 'vistas/layout.php'; // O layout_cliente.php según el rol
?>

<script>
// Validación Bootstrap + bloquear números en vivo
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

  // Bloquear números en tiempo real
  document.querySelectorAll("#nombre, #nacionalidad").forEach(input => {
    input.addEventListener("input", function() {
      this.value = this.value.replace(/[^a-zA-ZÁÉÍÓÚáéíóúÑñ\s]/g, "");
    });
  });
})();
</script>
