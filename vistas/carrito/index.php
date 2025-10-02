<?php ob_start(); ?>
<div class="container py-5">
    <h1 class="fw-bold text-gradient mb-4"><i class="bi bi-cart"></i> Carrito</h1>

    <?php if (empty($items)): ?>
        <div class="alert alert-info">Tu carrito está vacío.</div>
        <a href="<?= RUTA;?>tienda" class="btn btn-gradient">Ir al catálogo</a>
    <?php else: ?>
        <form action="<?= RUTA;?>carrito/actualizar" method="post">
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Libro</th>
                            <th>Título</th>
                            <th>Cantidad</th>
                            <th>Precio</th>
                            <th>Total</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $subtotal = 0; ?>
                        <?php foreach ($items as $id => $it): ?>
                            <?php $totalItem = $it['precio'] * $it['cantidad']; ?>
                            <tr>
                                <td style="width:90px">
                                    <?php if (!empty($it['portada'])): ?>
                                        <img src="<?= $it['portada'];?>" class="img-fluid rounded" style="height:70px;object-fit:cover">
                                    <?php else: ?>
                                        <div class="bg-light d-flex align-items-center justify-content-center" style="height:70px;width:70px"><i class="bi bi-book"></i></div>
                                    <?php endif; ?>
                                </td>
                                <td><?= htmlspecialchars($it['titulo']); ?></td>
                                <td style="width:140px">
                                    <input class="form-control" type="number" min="1" max="<?= (int)$it['stock']; ?>" name="cant[<?= (int)$id; ?>]" value="<?= (int)$it['cantidad']; ?>">
                                    <small class="text-muted">Stock: <?= (int)$it['stock']; ?></small>
                                </td>
                                <td>$<?= number_format($it['precio'], 2); ?></td>
                                <td>$<?= number_format($totalItem, 2); ?></td>
                                <td>
                                    <a class="btn btn-sm btn-outline-danger" href="<?= RUTA;?>carrito/quitar/<?= (int)$id; ?>" onclick="return confirm('Quitar del carrito?')">
                                        <i class="bi bi-x"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php $subtotal += $totalItem; ?>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="4" class="text-end">Subtotal</th>
                            <th>$<?= number_format($subtotal, 2); ?></th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-outline-secondary" type="submit"><i class="bi bi-arrow-repeat"></i> Actualizar</button>
                <a class="btn btn-outline-danger" href="<?= RUTA;?>carrito/vaciar" onclick="return confirm('Vaciar carrito?')"><i class="bi bi-trash"></i> Vaciar</a>
                <a class="btn btn-outline-primary ms-auto" href="<?= RUTA;?>tienda"><i class="bi bi-bag"></i> Seguir comprando</a>
            </div>
        </form>

        <hr>

        <form action="<?= RUTA;?>carrito/checkout" method="post" class="card p-3 shadow-sm">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Nombre del cliente</label>
                    <input name="cliente_nombre" class="form-control" value="<?= htmlspecialchars($_SESSION['usuario_nombre'] ?? '') ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Total a pagar</label>
                    <input class="form-control" value="$<?= number_format($subtotal, 2); ?>" disabled>
                </div>
            </div>
            <div class="mt-3 d-flex justify-content-end">
                <button class="btn btn-gradient btn-lg"><i class="bi bi-credit-card"></i> Confirmar compra</button>
            </div>
        </form>
    <?php endif; ?>
</div>
<?php
$contenidoVista = ob_get_clean();
$titulo = "Carrito";
include 'vistas/layout_cliente.php';
?>
