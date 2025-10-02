<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="fw-bold text-gradient">Agregar Categorías a: <?= $libro->getTitulo(); ?></h1>
        <a href="<?= RUTA; ?>libroCategoria" class="btn btn-secondary">← Volver</a>
    </div>

    <div class="card shadow border-0">
        <div class="card-body p-4">

            <!-- 🔎 Buscador -->
            <div class="mb-3">
                <input type="text" id="buscarCategoria" class="form-control shadow-sm"
                       placeholder="Buscar categoría..." onkeyup="filtrarCategorias()">
            </div>

            <form method="POST" action="<?= RUTA; ?>libroCategoria/store" id="formCategorias">
                <input type="hidden" name="id_libro" value="<?= $libro->getId_libro(); ?>">

                <div class="mb-3" id="listadoCategorias" style="max-height: 400px; overflow-y: auto;">
                    <?php foreach ($categorias as $categoria): ?>
                        <div class="form-check categoria-item">
                            <input class="form-check-input" type="checkbox" 
                                   name="categorias[]" 
                                   value="<?= $categoria->getId_categoria(); ?>"
                                   <?= in_array($categoria->getId_categoria(), $idsCategoriasSeleccionadas) ? 'checked' : ''; ?>>
                            <label class="form-check-label"><?= $categoria->getNombre(); ?></label>
                        </div>
                    <?php endforeach; ?>
                </div>

                <button type="submit" class="btn btn-gradient px-4 mt-3">Guardar</button>
            </form>
        </div>
    </div>
</div>

<!-- 🔧 Script filtrado dinámico -->
<script>
function filtrarCategorias() {
    let filtro = document.getElementById('buscarCategoria').value.toLowerCase();
    let items = document.querySelectorAll('#listadoCategorias .categoria-item');
    items.forEach(item => {
        let texto = item.innerText.toLowerCase();
        item.style.display = texto.includes(filtro) ? '' : 'none';
    });
}
</script>

<!-- 🎨 Estilos opcionales -->
<style>
.categoria-item { margin-bottom: 0.5rem; }
#listadoCategorias { border: 1px solid #ddd; border-radius: 5px; padding: 10px; }
</style>
