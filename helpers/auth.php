<?php
// helpers/auth.php
if (session_status() === PHP_SESSION_NONE) session_start();

function requireLogin($redir = null) {
    if (empty($_SESSION['usuario_id'])) {
        if ($redir) $_SESSION['redirigir_a'] = $redir;
        header("Location: " . RUTA . "auth/login");
        exit();
    }
}

function requireRole(array $rolesAceptados) {
    $rol = $_SESSION['usuario_rol'] ?? null;
    if (!$rol || !in_array($rol, $rolesAceptados)) {
        header("Location: " . RUTA); // o 403
        exit();
    }
}

function carritoCount(): int {
    $c = $_SESSION['carrito'] ?? [];
    $total = 0;
    foreach ($c as $item) $total += $item['cantidad'];
    return $total;
}
