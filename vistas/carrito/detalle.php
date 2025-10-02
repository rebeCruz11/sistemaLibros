<?php ob_start(); ?><!-- vistas/carrito/misCompras.php -->
<!-- vistas/carrito/detalle.php -->
<div class="container mt-4">
    <h2>Detalles de la Compra</h2>

    <p><strong>Nombre del Cliente:</strong> <?= $venta['cliente_nombre']; ?></p>
    <p><strong>Total de la Compra:</strong> $<?= number_format($venta['total'], 2); ?></p>
    <p><strong>Fecha de Compra:</strong> <?= date('d-m-Y', strtotime($venta['fecha'])); ?></p>

    <h3>Productos Comprados</h3>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Título</th>
                <th>Cantidad</th>
                <th>Precio Unitario</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($detalles as $detalle): ?>
                <?php $libro = $this->libroModel->getById($detalle['id_libro']); ?>
                <tr>
                    <td><?= $libro->getTitulo(); ?></td>
                    <td><?= $detalle['cantidad']; ?></td>
                    <td>$<?= number_format($detalle['precio_unitario'], 2); ?></td>
                    <td>$<?= number_format($detalle['cantidad'] * $detalle['precio_unitario'], 2); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php
$contenidoVista = ob_get_clean();
$titulo = "detalle";
include 'vistas/layout_cliente.php';
?>
