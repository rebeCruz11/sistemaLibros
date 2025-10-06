<?php ob_start(); ?>
<div class="container py-5">
    <div class="text-center mb-5">
        <h2 class="text-gradient fw-bold">📊 Reportes del Sistema de Libros</h2>
        <p class="text-muted">Visualiza los datos en gráficos interactivos o descarga los reportes en PDF o Excel.</p>

        <!-- Botones de descarga -->
        <div class="d-flex flex-wrap justify-content-center gap-2 mt-3">
            <a class="btn btn-primary btn-lg" href="<?= RUTA; ?>reportes/usuarios_pdf">📄 Usuarios (PDF)</a>
            <a class="btn btn-outline-primary btn-lg" href="<?= RUTA; ?>reportes/usuarios_excel">📊 Usuarios (Excel)</a>
            <a class="btn btn-primary btn-lg" href="<?= RUTA; ?>reportes/libros_pdf">📄 Libros (PDF)</a>
            <a class="btn btn-outline-primary btn-lg" href="<?= RUTA; ?>reportes/libros_excel">📊 Libros (Excel)</a>
            <a class="btn btn-primary btn-lg" href="<?= RUTA; ?>reportes/autores_pdf">📄 Autores (PDF)</a>
            <a class="btn btn-outline-primary btn-lg" href="<?= RUTA; ?>reportes/autores_excel">📊 Autores (Excel)</a>
        </div>
    </div>

    <div class="row g-4">
        <!-- Usuarios por Rol -->
        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-primary text-white fw-bold">
                    👤 Usuarios por Rol
                </div>
                <div class="card-body">
                    <canvas id="usuariosChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Libros por Autor -->
        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-info text-white fw-bold">
                    📚 Libros por Autor
                </div>
                <div class="card-body">
                    <canvas id="librosChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Top 10 Autores por Total de Libros -->
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-warning text-dark fw-bold">
                    ✍️ Top 10 Autores por Total de Libros
                </div>
                <div class="card-body">
                    <canvas id="autoresChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Usuarios por Rol
    const usuariosData = {
        labels: <?= json_encode(array_unique(array_column($usuarios,'rol'))); ?>,
        datasets: [{
            label: 'Cantidad de Usuarios',
            data: <?= json_encode(array_values(array_count_values(array_column($usuarios,'rol')))); ?>,
            backgroundColor: ['#4e73df','#1cc88a','#36b9cc','#f6c23e','#e74a3b']
        }]
    };
    new Chart(document.getElementById('usuariosChart'), {
        type: 'bar',
        data: usuariosData,
        options: {
            responsive: true,
            plugins: { legend: { position: 'top' }, title: { display: false } }
        }
    });

    // Libros por Autor
    const librosData = {
        labels: <?= json_encode(array_column($libros,'autor')); ?>,
        datasets: [{
            label: 'Cantidad de Libros',
            data: <?= json_encode(array_column($libros,'stock')); ?>,
            backgroundColor: '#36b9cc'
        }]
    };
    new Chart(document.getElementById('librosChart'), {
        type: 'bar',
        data: librosData,
        options: {
            responsive: true,
            plugins: { legend: { display: false }, title: { display: false } }
        }
    });

    // Top 10 Autores por Libros
    const autoresData = {
        labels: <?= json_encode(array_column($autores,'nombre')); ?>,
        datasets: [{
            label: 'Total de Libros',
            data: <?= json_encode(array_column($autores,'total_libros')); ?>,
            backgroundColor: '#f6c23e'
        }]
    };
    new Chart(document.getElementById('autoresChart'), {
        type: 'bar',
        data: autoresData,
        options: {
            responsive: true,
            plugins: { legend: { display: false }, title: { display: false } }
        }
    });
</script>

<?php
$contenidoVista = ob_get_clean();
$titulo = "Reportes";
include 'vistas/layout.php';
?>
