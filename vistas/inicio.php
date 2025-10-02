<?php
ob_start();
?>
<div class="text-center mt-5">
    <h1>Bienvenido Administrador</h1>
    <p>Use el menú para gestionar Autores, Categorías y Libros.</p>
    <img src="https://cdn-icons-png.flaticon.com/512/29/29302.png" alt="Libros" width="150">
</div>
<?php
$contenidoVista = ob_get_clean();
$titulo = "Inicio Admin";
include 'vistas/layout.php';
?>