<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
<?php
ob_start();
echo "<!-- show.php cargado -->";
?>
?>
<div class="container py-5">
    <div class="card shadow-lg border-0">
        <div class="row g-0">
            <!-- Portada -->
            <div class="col-md-5 d-flex align-items-center justify-content-center bg-light">
                <?php if (!empty($libro->getPortada())): ?>
                    <img src="<?= $libro->getPortada(); ?>" 
                         alt="Portada de <?= htmlspecialchars($libro->getTitulo()); ?>" 
                         class="img-fluid rounded shadow" 
                         style="max-height: 500px; object-fit: contain;">
                <?php else: ?>
                    <div class="d-flex flex-column align-items-center justify-content-center text-muted"
                         style="height: 500px; width: 100%;">
                        <i class="bi bi-book text-secondary" style="font-size: 5rem;"></i>
                        <p class="mt-3">Sin portada disponible</p>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Información -->
            <div class="col-md-7">
                <div class="card-body p-4">
                    <h1 class="card-title text-gradient fw-bold mb-3">
                        <?= htmlspecialchars($libro->getTitulo()); ?>
                    </h1>
                    <h4 class="text-muted mb-4">
                        <i class="bi bi-person-fill"></i> 
                        <?= $autor ? htmlspecialchars($autor->getNombre()) : "Autor desconocido"; ?>
                    </h4>

                    <p class="mb-3">
                        <strong>Stock:</strong> <?= $libro->getStock(); ?>
                    </p>
                    <p class="mb-3">
                        <strong>Disponible:</strong> 
                        <?= $libro->getDisponible() ? '<span class="badge bg-success">Sí</span>' : '<span class="badge bg-danger">No</span>'; ?>
                    </p>

                    <?php if ($libro->getQr()): ?>
                        <div class="mt-4">
                            <strong>QR para ver este libro:</strong><br>
                            <img src="<?= RUTA . $libro->getQr(); ?>" alt="QR del libro" width="150">
                        </div>
                    <?php endif; ?>

                    <?php if (isset($_SESSION['usuario_rol']) && $_SESSION['usuario_rol'] === 'admin'): ?>
                    <div class="mt-4 d-flex gap-2">
                        <a href="<?= RUTA; ?>libro/edit/<?= $libro->getId_libro(); ?>" class="btn btn-warning">
                            <i class="bi bi-pencil-square"></i> Editar
                        </a>
                        <a href="<?= RUTA; ?>libro/delete/<?= $libro->getId_libro(); ?>" 
                           onclick="return confirm('¿Seguro que deseas eliminar este libro?');" 
                           class="btn btn-danger">
                            <i class="bi bi-trash"></i> Eliminar
                        </a>
                    </div>
                    <?php endif; ?>
                    <a href="<?= RUTA; ?>libro/index" class="btn btn-secondary mt-3">
                        <i class="bi bi-arrow-left"></i> Volver
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
$contenidoVista = ob_get_clean();
$titulo = "Detalle Libro";
// No incluyas el layout aquí
?>
<!-- 🎨 Estilos -->
<style>
.text-gradient {
    background: linear-gradient(45deg, #6a11cb, #2575fc);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}
</style>