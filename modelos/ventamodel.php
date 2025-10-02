<?php
// modelos/ventamodel.php
require_once __DIR__ . '/../config/cn.php';

class VentaModel {
    private $cn;
    public function __construct() { $this->cn = new CNpdo(); }

    public function crearVenta(int $idUsuario, string $clienteNombre, float $total): int {
        // 1. Realizar la inserción
        $sql = "INSERT INTO venta (id_usuario, cliente_nombre, total) VALUES (?,?,?)";
        $this->cn->ejecutar($sql, [$idUsuario, $clienteNombre, $total]);
        
        // 2. Obtener el último ID insertado
        return (int)$this->cn->getConexion()->lastInsertId(); // Usamos la conexión para obtener el ID
    }
        // VentaModel.php
    public function getComprasByUsuario($idUsuario) {
        $sql = "SELECT * FROM venta WHERE id_usuario = ?";
        return $this->cn->consulta($sql, [$idUsuario]);
    }
    // VentaModel.php
    public function getById($idVenta) {
        $sql = "SELECT * FROM venta WHERE id_venta = ?";
        return $this->cn->consulta($sql, [$idVenta])[0];
    }

}



?>