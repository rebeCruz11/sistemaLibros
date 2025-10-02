<?php ob_start(); ?>
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="fw-bold text-gradient">Catálogo</h1>
        <form class="d-flex" onsubmit="return false;">
            <input id="q" class="form-control form-control-lg me-2" placeholder="Buscar título o autor...">
        </form>
    </div>

    <div id="gridLibros" class="row g-4">
        <?php foreach ($data as $libro): ?>
            <div class="col-12 col-sm-6 col-md-4 col-lg-3 libro-card" 
                data-titulo="<?= htmlspecialchars(mb_strtolower($libro['titulo'])) ?>"
                data-autor="<?= htmlspecialchars(mb_strtolower($libro['autor'])) ?>">
                <div class="card h-100 shadow-sm">
                    <?php if (!empty($libro['portada'])): ?>
                        <img src="<?= $libro['portada']; ?>" class="card-img-top" alt="Portada"
                            style="height:220px;object-fit:cover">
                    <?php else: ?>
                        <div class="bg-light d-flex align-items-center justify-content-center" style="height:220px">
                            <i class="bi bi-book" style="font-size:3rem"></i>
                        </div>
                    <?php endif; ?>
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title mb-1"><?= htmlspecialchars($libro['titulo']); ?></h5>
                        <p class="text-muted mb-2"><i class="bi bi-person"></i> <?= htmlspecialchars($libro['autor']); ?></p>
                        <div class="mb-2">
                            <span class="badge bg-success">Stock: <?= (int)$libro['stock']; ?></span>
                        </div>

                        <!-- Precio -->
                        <p class="mb-3"><strong>Precio:</strong> $<?= number_format($libro['precio'], 2); ?></p>

                        <div class="mt-auto d-grid gap-2">
                            <a href="<?= RUTA; ?>tienda/show/<?= $libro['id_libro']; ?>" class="btn btn-outline-secondary">
                                <i class="bi bi-eye"></i> Ver detalle
                            </a>
                            <form action="<?= RUTA; ?>carrito/agregar/<?= $libro['id_libro']; ?>" method="post">
                                <button class="btn btn-gradient w-100" <?= $libro['stock'] <= 0 ? 'disabled' : '' ?>>
                                    <i class="bi bi-cart-plus"></i> Agregar al carrito
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <script>
    document.getElementById('q').addEventListener('input', function(){
        const term = this.value.trim().toLowerCase();
        document.querySelectorAll('.libro-card').forEach(card => {
            const t = card.dataset.titulo, a = card.dataset.autor;
            card.style.display = (t.includes(term) || a.includes(term)) ? '' : 'none';
        });
    });
    </script>
<?php
$contenidoVista = ob_get_clean();
$titulo = "Catálogo";
include 'vistas/layout_cliente.php';
?>
