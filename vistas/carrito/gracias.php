<?php ob_start(); ?>
<div class="container py-5">
    <h1 class="text-gradient fw-bold mb-4">¡Gracias por tu compra!</h1>
    <p>Tu compra ha sido procesada exitosamente. El ID de tu venta es: <strong><?= $ventaId; ?></strong></p>
    <a href="<?= RUTA; ?>tienda" class="btn btn-gradient">Volver al catálogo</a>
</div>
<?php
$contenidoVista = ob_get_clean();
$titulo = "Gracias por tu compra";
include 'vistas/layout_cliente.php';
?>
