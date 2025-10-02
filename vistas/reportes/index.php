<?php ob_start(); ?>
<div class="text-center mb-4">
    <h2 class="text-gradient">Reportes</h2>
    <div class="btn-group" role="group">
        <a class="btn btn-gradient btn-danger" href="<?= RUTA; ?>reportes/usuarios_pdf">📄 Usuarios (PDF)</a>
        <a class="btn btn-gradient btn-success" href="<?= RUTA; ?>reportes/usuarios_excel">📊 Usuarios (Excel)</a>
    </div>
    <div class="btn-group ms-2" role="group">
        <a class="btn btn-gradient btn-danger" href="<?= RUTA; ?>reportes/libros_pdf">📄 Libros (PDF)</a>
        <a class="btn btn-gradient btn-success" href="<?= RUTA; ?>reportes/libros_excel">📊 Libros (Excel)</a>
    </div>
    <div class="btn-group ms-2" role="group">
        <a class="btn btn-gradient btn-danger" href="<?= RUTA; ?>reportes/autores_pdf">📄 Autores (PDF)</a>
        <a class="btn btn-gradient btn-success" href="<?= RUTA; ?>reportes/autores_excel">📊 Autores (Excel)</a>
    </div>
</div>
<?php
$contenidoVista = ob_get_clean();
$titulo = "Reportes";
include 'vistas/layout.php';  // O layout_cliente.php según el rol
?>
