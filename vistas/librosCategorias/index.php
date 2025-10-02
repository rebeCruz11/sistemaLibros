<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<?php
ob_start();
?>
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="fw-bold text-gradient">Libros y Categorías</h1>
        <a href="<?= RUTA; ?>libro" class="btn btn-gradient px-4">
            + Agregar Libro
        </a>
    </div>

    <?php
    // Estadísticas
    $totalLibros = count($data);
    $categoriasUnicas = [];
    $categoriasCount = [];
    foreach ($data as $item) {
        if (!empty($item['nombres'])) {
            $cats = explode(', ', $item['nombres']);
            foreach ($cats as $c) {
                $categoriasUnicas[] = $c;
                $categoriasCount[$c] = ($categoriasCount[$c] ?? 0) + 1;
            }
        }
    }
    $totalCategorias = count(array_unique($categoriasUnicas));

    // Top 5 categorías
    arsort($categoriasCount);
    $topCategorias = array_slice($categoriasCount, 0, 5, true);
    $labels = array_keys($topCategorias);
    $values = array_values($topCategorias);
    ?>

    <!-- 📊 Estadísticas -->
    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="card shadow-sm border-0 text-center py-3">
                <h5>Total Libros</h5>
                <p class="fs-4 fw-bold"><?= $totalLibros ?></p>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card shadow-sm border-0 text-center py-3">
                <h5>Total Categorías asignadas</h5>
                <p class="fs-4 fw-bold"><?= $totalCategorias ?></p>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card shadow-sm border-0 text-center py-3">
                <h5>Top 5 Categorías</h5>
                <canvas id="topCategoriasChart" height="80"></canvas>
            </div>
        </div>
    </div>

    <!-- 🔎 Buscador -->
    <div class="mb-4">
        <input type="text" id="search" class="form-control form-control-lg shadow-sm"
               placeholder="Buscar en toda la tabla..." onkeyup="filtrarTabla()">
    </div>

    <!-- 🎯 Filtros separados -->
    <div class="row mb-4">
        <div class="col-md-4">
            <input type="text" id="filtroLibro" class="form-control shadow-sm"
                   placeholder="Filtrar por Libro" onkeyup="filtrarPorColumna(1, this.value)">
        </div>
        <div class="col-md-8">
            <input type="text" id="filtroCategorias" class="form-control shadow-sm"
                   placeholder="Filtrar por Categorías" onkeyup="filtrarPorColumna(2, this.value)">
        </div>
    </div>

    <!-- 📊 Tabla -->
    <div class="card shadow border-0">
        <div class="table-responsive">
            <table class="table align-middle mb-0" id="tablaLibrosCategorias">
                <thead class="table-gradient text-white">
                    <tr>
                        <th>ID Libro</th>
                        <th>Libro</th>
                        <th>Categorías</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data as $item): ?>
                    <tr>
                        <td><?= $item['libro']->getId_libro(); ?></td>
                        <td><?= $item['libro']->getTitulo(); ?></td>
                        <td><?= !empty($item['nombres']) ? $item['nombres'] : '<span class="text-muted">Sin categorías</span>'; ?></td>
                        <td>
                            <a href="<?= RUTA; ?>libroCategoria/create/<?= $item['libro']->getId_libro(); ?>" class="btn btn-success">
                                <i class="bi bi-tags"></i> Asignar Categorías
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- 📑 Paginación -->
    <nav class="mt-4">
        <ul class="pagination justify-content-center" id="paginacion"></ul>
    </nav>
</div>
<?php
$contenidoVista = ob_get_clean();
$titulo = "Ver libro categoria";
include 'vistas/layout.php'; // O layout_cliente.php según el rol
?>
<!-- 🎨 Estilos -->
<style>
i.bi { font-size: 1.2rem; vertical-align: middle; }
.text-gradient {
    background: linear-gradient(45deg, #6a11cb, #2575fc);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}
.btn-gradient {
    background: linear-gradient(45deg, #6a11cb, #2575fc);
    border: none; color: #fff; border-radius: 8px; transition: 0.3s;
}
.btn-gradient:hover { background: linear-gradient(45deg, #5b0ea6, #1d5fd6); color: #fff; }
.table-gradient { background: linear-gradient(45deg, #6a11cb, #2575fc); }
</style>

<!-- 🔧 Scripts filtros, paginación y gráfico -->
<script>
function filtrarTabla() {
    let input = document.getElementById("search").value.toLowerCase();
    let filas = document.querySelectorAll("#tablaLibrosCategorias tbody tr");
    filas.forEach(fila => {
        let texto = fila.innerText.toLowerCase();
        fila.style.display = texto.includes(input) ? "" : "none";
    });
}

function filtrarPorColumna(index, value) {
    value = value.toLowerCase();
    let filas = document.querySelectorAll("#tablaLibrosCategorias tbody tr");
    filas.forEach(fila => {
        let texto = fila.cells[index].innerText.toLowerCase();
        fila.style.display = texto.includes(value) ? "" : "none";
    });
}

document.addEventListener("DOMContentLoaded", () => {
    // Paginación
    let rows = document.querySelectorAll("#tablaLibrosCategorias tbody tr");
    let rowsPerPage = 20;
    let totalPages = Math.ceil(rows.length / rowsPerPage);
    let currentPage = 1;

    function mostrarPagina(pagina) {
        currentPage = pagina;
        rows.forEach((row, index) => {
            row.style.display = (index >= (pagina - 1) * rowsPerPage && index < pagina * rowsPerPage) ? "" : "none";
        });
        dibujarPaginacion();
    }

    function dibujarPaginacion() {
        let pagDiv = document.getElementById("paginacion");
        pagDiv.innerHTML = "";
        for (let i = 1; i <= totalPages; i++) {
            let li = document.createElement("li");
            li.classList.add("page-item");
            if (i === currentPage) li.classList.add("active");
            let btn = document.createElement("a");
            btn.classList.add("page-link");
            btn.innerText = i;
            btn.href = "#";
            btn.onclick = (e) => { e.preventDefault(); mostrarPagina(i); };
            li.appendChild(btn);
            pagDiv.appendChild(li);
        }
    }

    mostrarPagina(1);

    // Gráfico Top 5 Categorías
    const ctx = document.getElementById('topCategoriasChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?= json_encode($labels); ?>,
            datasets: [{
                label: 'Cantidad de Libros',
                data: <?= json_encode($values); ?>,
                backgroundColor: 'rgba(38, 162, 255, 0.7)',
                borderColor: 'rgba(38, 162, 255, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                tooltip: { mode: 'index', intersect: false }
            },
            scales: {
                y: { beginAtZero: true, precision:0 }
            }
        }
    });
});
</script>
