<?php
ob_start();
?>
<div class="text-center mt-5">
    <h1>Bienvenido a la Tienda de Libros</h1>
    <p>Explora nuestro catálogo y realiza tus compras.</p>
    <img src="https://cdn-icons-png.flaticon.com/512/29/29302.png" alt="Libros" width="150">
</div>
<?php
$contenidoVista = ob_get_clean();
$titulo = "Inicio Cliente";
include 'vistas/layout_cliente.php';
?>