<?php ob_start(); ?>
<div class="container py-4">
    <div class="card shadow-lg border-0">
        <div class="row g-0">
            <div class="col-md-5 d-flex align-items-center justify-content-center bg-light">
                <?php if (!empty($libro->getPortada())): ?>
                    <img src="<?= $libro->getPortada(); ?>" alt="Portada" class="img-fluid rounded" style="max-height:500px;object-fit:contain">
                <?php else: ?>
                    <div class="text-muted d-flex flex-column align-items-center" style="height:500px;width:100%">
                        <i class="bi bi-book" style="font-size:5rem"></i><p class="mt-3">Sin portada</p>
                    </div>
                <?php endif; ?>
            </div>
            <div class="col-md-7">
                <div class="card-body p-4">
                    <h1 class="text-gradient fw-bold mb-2"><?= htmlspecialchars($libro->getTitulo()); ?></h1>
                    <h4 class="text-muted mb-3"><i class="bi bi-person-fill"></i> <?= $autor ? htmlspecialchars($autor->getNombre()) : 'Autor desconocido'; ?></h4>
                    <p class="mb-2"><strong>Stock:</strong> <?= (int)$libro->getStock(); ?></p>
                    <p class="mb-3"><strong>Disponible:</strong> <?= $libro->getDisponible() ? '<span class="badge bg-success">Sí</span>' : '<span class="badge bg-danger">No</span>'; ?></p>

                    <!-- Precio -->
                    <p class="mb-3"><strong>Precio:</strong> $<?= number_format($libro->getPrecio(), 2); ?></p>

                    <?php if ($libro->getQr()): ?>
                        <div class="mt-3">
                            <small class="text-muted d-block mb-1">QR de este libro</small>
                            <img src="<?= RUTA . $libro->getQr(); ?>" alt="QR" width="140">
                        </div>
                    <?php endif; ?>

                    <div class="mt-4 d-flex gap-2">
                        <a href="<?= RUTA; ?>tienda" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Volver</a>
                        <?php if ($libro->getDisponible() && $libro->getStock() > 0): ?>
                            <form action="<?= RUTA; ?>carrito/agregar/<?= $libro->getId_libro(); ?>" method="post">
                                <button class="btn btn-gradient"><i class="bi bi-cart-plus"></i> Agregar al carrito</button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
$contenidoVista = ob_get_clean();
$titulo = "Detalle";
include 'vistas/layout_cliente.php';
?>
