<?php ob_start(); ?><!-- vistas/carrito/misCompras.php -->
<!-- vistas/carrito/misCompras.php -->
<div class="container mt-4">
    <h2>Mis Compras</h2>

    <!-- Filtro por fecha -->
    <form id="filtroForm" class="mb-3">
        <div class="row">
            <div class="col-md-4">
                <input type="date" id="fecha_inicio" class="form-control" placeholder="Desde" />
            </div>
            <div class="col-md-4">
                <input type="date" id="fecha_fin" class="form-control" placeholder="Hasta" />
            </div>
            <div class="col-md-4">
                <button type="button" class="btn btn-primary" onclick="filtrarPorFecha()">Filtrar</button>
            </div>
        </div>
    </form>

    <?php if (empty($compras)): ?>
        <p>No has realizado ninguna compra aún.</p>
    <?php else: ?>
        <table class="table table-striped" id="comprasTable">
            <thead>
                <tr>
                    <th>Fecha de Compra</th>
                    <th>Total</th>
                    <th>Detalles</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($compras as $compra): ?>
                    <tr class="compra-row" data-fecha="<?= $compra['fecha']; ?>">
                        <td><?= date('d-m-Y', strtotime($compra['fecha'])); ?></td>
                        <td>$<?= number_format($compra['total'], 2); ?></td>
                        <td>
                            <a href="<?= RUTA; ?>carrito/detalle/<?= $compra['id_venta']; ?>" class="btn btn-info">Ver Detalles</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<?php
$contenidoVista = ob_get_clean();
$titulo = "CarritoMis";
include 'vistas/layout_cliente.php';
?>
<script>
    function filtrarPorFecha() {
        // Obtener las fechas del formulario
        const fechaInicio = document.getElementById('fecha_inicio').value;
        const fechaFin = document.getElementById('fecha_fin').value;

        // Obtener todas las filas de compras
        const filas = document.querySelectorAll('.compra-row');

        // Iterar sobre todas las filas
        filas.forEach(fila => {
            const fechaCompra = fila.getAttribute('data-fecha'); // Fecha en formato YYYY-MM-DD

            // Verificar si la fecha está dentro del rango
            if (fechaCompra) {
                const mostrar = (fechaInicio === "" || fechaCompra >= fechaInicio) &&
                                (fechaFin === "" || fechaCompra <= fechaFin);

                // Mostrar o esconder la fila
                fila.style.display = mostrar ? '' : 'none';
            }
        });
    }
</script>

