<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<?php
ob_start();
?>
<div class="container py-5">

    <?php
    // Estadísticas
    $totalLibros = count($data);
    $disponibles = 0;
    $noDisponibles = 0;
    $librosPorAutor = [];

    foreach ($data as $libro) {
        if ($libro['disponible']) $disponibles++;
        else $noDisponibles++;

        $autor = $libro['autor'] ?? 'Desconocido';
        if (!isset($librosPorAutor[$autor])) $librosPorAutor[$autor] = 0;
        $librosPorAutor[$autor]++;
    }

    arsort($librosPorAutor);
    $topAutores = array_slice($librosPorAutor, 0, 5, true);
    ?>

    <!-- 🔝 Estadísticas -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card text-center shadow-sm border-0">
                <div class="card-body">
                    <h5 class="card-title text-muted">Total de Libros</h5>
                    <h2 class="card-text text-gradient"><?= $totalLibros; ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center shadow-sm border-0">
                <div class="card-body">
                    <h5 class="card-title text-muted">Disponibles / No Disponibles</h5>
                    <h2 class="card-text text-gradient"><?= $disponibles; ?> / <?= $noDisponibles; ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center shadow-sm border-0">
                <div class="card-body">
                    <h5 class="card-title text-muted">Top Autores con más Libros</h5>
                    <canvas id="topAutoresChart" height="80"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Encabezado y botón -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="fw-bold text-gradient">Libros</h1>
        <a href="<?= RUTA; ?>libro/create" class="btn btn-gradient px-4">
            + Agregar Libro
        </a>
    </div>

    <!-- 🔎 Buscador -->
    <div class="mb-4">
        <input type="text" id="search" class="form-control form-control-lg shadow-sm"
               placeholder="Buscar en toda la tabla..." onkeyup="filtrarTabla()">
    </div>

    <!-- 🎯 Filtros separados -->
    <div class="row mb-4">
        <div class="col-md-3">
            <input type="text" id="filtroId" class="form-control shadow-sm"
                   placeholder="Filtrar por ID" onkeyup="filtrarPorColumna(0, this.value)">
        </div>
        <div class="col-md-3">
            <input type="text" id="filtroTitulo" class="form-control shadow-sm"
                   placeholder="Filtrar por Título" onkeyup="filtrarPorColumna(1, this.value)">
        </div>
        <div class="col-md-3">
            <input type="text" id="filtroAutor" class="form-control shadow-sm"
                   placeholder="Filtrar por Autor" onkeyup="filtrarPorColumna(2, this.value)">
        </div>
        <div class="col-md-3">
            <input type="text" id="filtroDisponible" class="form-control shadow-sm"
                   placeholder="Disponible (Sí/No)" onkeyup="filtrarPorColumna(5, this.value)">
        </div>
    </div>

    <!-- 📊 Tabla -->
    <div class="card shadow border-0">
        <div class="table-responsive">
            <table class="table align-middle mb-0" id="tablaLibros">
                <thead class="table-gradient text-white">
                    <tr>
                        <th>ID</th>
                        <th>Título</th>
                        <th>Autor</th>
                        <th>Portada</th>
                        <th>Stock</th>
                        <th>Disponible</th>
                        <th>Precio</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data as $libro): ?>
                    <tr>
                        <td><?= $libro['id_libro']; ?></td>
                        <td><?= $libro['titulo']; ?></td>
                        <td><?= $libro['autor']; ?></td>
                        <td>
                            <?php if (!empty($libro['portada'])): ?>
                                <img src="<?= $libro['portada']; ?>" alt="Portada" width="50">
                            <?php else: ?>
                                <span class="text-muted">Sin portada</span>
                            <?php endif; ?>
                        </td>
                        <td><?= $libro['stock']; ?></td>
                        <td><?= $libro['disponible'] ? 'Sí' : 'No'; ?></td>
                        <td>$<?= number_format($libro['precio'], 2); ?></td> <!-- Aquí agregamos el precio -->
                        <td>
                            <a href="<?= RUTA; ?>libro/show/<?= $libro['id_libro']; ?>" class="btn btn-info">
                                <i class="bi bi-eye-fill"></i></i>
                            </a>
                            <a href="<?= RUTA; ?>libro/edit/<?= $libro['id_libro']; ?>" class="btn btn-warning">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <a href="<?= RUTA; ?>libro/delete/<?= $libro['id_libro']; ?>" 
                               onclick="return confirm('¿Seguro que deseas eliminar este libro?');" 
                               class="btn btn-danger">
                                <i class="bi bi-trash"></i>
                            </a>
                            <a href="<?= RUTA; ?>libroCategoria/create/<?= $libro['id_libro']; ?>" class="btn btn-success">
                                <i class="bi bi-plus-circle"></i>
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
$titulo = "Agregar Libro";
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
.card .card-body h2 { font-size: 2rem; }
</style>

<!-- 🔧 Scripts filtros, paginación y gráfico -->
<script>
function filtrarTabla() {
    let input = document.getElementById("search").value.toLowerCase();
    let filas = document.querySelectorAll("#tablaLibros tbody tr");
    filas.forEach(fila => {
        let texto = fila.innerText.toLowerCase();
        fila.style.display = texto.includes(input) ? "" : "none";
    });
}

function filtrarPorColumna(index, value) {
    value = value.toLowerCase();
    let filas = document.querySelectorAll("#tablaLibros tbody tr");
    filas.forEach(fila => {
        let texto = fila.cells[index].innerText.toLowerCase();
        fila.style.display = texto.includes(value) ? "" : "none";
    });
}

// Paginación
document.addEventListener("DOMContentLoaded", () => {
    let rows = document.querySelectorAll("#tablaLibros tbody tr");
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

    // Gráfico Top Autores
    const ctx = document.getElementById('topAutoresChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?= json_encode(array_keys($topAutores)); ?>,
            datasets: [{
                label: 'Cantidad de Libros',
                data: <?= json_encode(array_values($topAutores)); ?>,
                backgroundColor: 'rgba(38, 115, 255, 0.7)',
                borderColor: 'rgba(38, 115, 255, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false }, title: { display: false } },
            scales: { y: { beginAtZero: true, precision:0 } }
        }
    });
});
</script>
