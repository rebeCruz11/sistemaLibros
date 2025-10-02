<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

<div class="container py-5">

    <?php
    // Estadísticas
    $totalCategorias = count($categorias);
    ?>

    <!-- 🔝 Estadísticas -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card text-center shadow-sm border-0">
                <div class="card-body">
                    <h5 class="card-title text-muted">Total de Categorías</h5>
                    <h2 class="card-text text-gradient"><?= $totalCategorias; ?></h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Encabezado y botón -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="fw-bold text-gradient">Categorías</h1>
        <a href="<?= RUTA; ?>categoria/create" class="btn btn-gradient px-4">
            + Agregar Categoría
        </a>
    </div>

    <!-- 🔎 Buscador -->
    <div class="mb-4">
        <input type="text" id="search" class="form-control form-control-lg shadow-sm"
               placeholder="Buscar en toda la tabla..." onkeyup="filtrarTabla()">
    </div>

    <!-- 🎯 Filtros separados -->
    <div class="row mb-4">
        <div class="col-md-6">
            <input type="text" id="filtroId" class="form-control shadow-sm"
                   placeholder="Filtrar por ID" onkeyup="filtrarPorColumna(0, this.value)">
        </div>
        <div class="col-md-6">
            <input type="text" id="filtroNombre" class="form-control shadow-sm"
                   placeholder="Filtrar por Nombre" onkeyup="filtrarPorColumna(1, this.value)">
        </div>
    </div>

    <!-- 📊 Tabla -->
    <div class="card shadow border-0">
        <div class="table-responsive">
            <table class="table align-middle mb-0" id="tablaCategorias">
                <thead class="table-gradient text-white">
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($categorias as $categoria): ?>
                    <tr>
                        <td><?= $categoria->getId_categoria(); ?></td>
                        <td><?= $categoria->getNombre(); ?></td>
                        <td>
                            <a href="<?= RUTA; ?>categoria/edit/<?= $categoria->getId_categoria(); ?>" class="btn btn-warning">
                              <i class="bi bi-pencil-square"></i>
                            </a>
                            <a href="<?= RUTA; ?>categoria/delete/<?= $categoria->getId_categoria(); ?>" 
                              onclick="return confirm('¿Seguro que deseas eliminar esta categoría?');" 
                              class="btn btn-danger">
                              <i class="bi bi-trash"></i>
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

<!-- 🎨 Estilos personalizados -->
<style>
i.bi {
    font-size: 1.2rem;
    vertical-align: middle;
}
.text-gradient {
    background: linear-gradient(45deg, #6a11cb, #2575fc);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}
.btn-gradient {
    background: linear-gradient(45deg, #6a11cb, #2575fc);
    border: none;
    color: #fff;
    border-radius: 8px;
    transition: 0.3s;
}
.btn-gradient:hover {
    background: linear-gradient(45deg, #5b0ea6, #1d5fd6);
    color: #fff;
}
.table-gradient {
    background: linear-gradient(45deg, #6a11cb, #2575fc);
}
.card .card-body h2 { font-size: 2rem; }
</style>

<!-- 🔧 Scripts filtros y paginación -->
<script>
function filtrarTabla() {
    let input = document.getElementById("search").value.toLowerCase();
    let filas = document.querySelectorAll("#tablaCategorias tbody tr");
    filas.forEach(fila => {
        let texto = fila.innerText.toLowerCase();
        fila.style.display = texto.includes(input) ? "" : "none";
    });
}

function filtrarPorColumna(index, value) {
    value = value.toLowerCase();
    let filas = document.querySelectorAll("#tablaCategorias tbody tr");
    filas.forEach(fila => {
        let texto = fila.cells[index].innerText.toLowerCase();
        fila.style.display = texto.includes(value) ? "" : "none";
    });
}

// 📑 Paginación
document.addEventListener("DOMContentLoaded", () => {
    let rows = document.querySelectorAll("#tablaCategorias tbody tr");
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
            btn.onclick = (e) => {
                e.preventDefault();
                mostrarPagina(i);
            };
            li.appendChild(btn);
            pagDiv.appendChild(li);
        }
    }

    mostrarPagina(1);
});
</script>
