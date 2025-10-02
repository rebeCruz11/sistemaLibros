<?php
// modelos/detalleventamodel.php
require_once __DIR__ . '/../config/cn.php';

class DetalleVentaModel {
    private $cn;
    public function __construct() { $this->cn = new CNpdo(); }

    public function crearDetalle(int $idVenta, int $idLibro, int $cantidad, float $precio) {
        $sql = "INSERT INTO detalle_venta (id_venta, id_libro, cantidad, precio_unitario) VALUES (?,?,?,?)";
        $this->cn->consulta($sql, [$idVenta, $idLibro, $cantidad, $precio]);
    }
    // DetalleVentaModel.php
    public function getDetallesByVenta($idVenta) {
        $sql = "SELECT * FROM detalle_venta WHERE id_venta = ?";
        return $this->cn->consulta($sql, [$idVenta]);
    }

}
