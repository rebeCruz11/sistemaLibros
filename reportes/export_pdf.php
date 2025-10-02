<?php
require '../vendor/autoload.php';
require '../vendor/setasign/fpdf/fpdf.php';

// Conexión a la BD
$conexion = new mysqli("localhost", "root", "", "libros");
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

$result = $conexion->query("SELECT id_usuario, nombre, apellido, correo FROM usuarios");

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, 'Reporte de Usuarios', 0, 1, 'C');
$pdf->Ln(10);

// Cabeceras
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(20, 10, 'ID', 1);
$pdf->Cell(50, 10, 'Nombre', 1);
$pdf->Cell(50, 10, 'Apellido', 1);
$pdf->Cell(70, 10, 'Correo', 1);
$pdf->Ln();

// Datos
$pdf->SetFont('Arial', '', 12);
while ($row = $result->fetch_assoc()) {
    $pdf->Cell(20, 10, $row['id_usuario'], 1);
    $pdf->Cell(50, 10, $row['nombre'], 1);
    $pdf->Cell(50, 10, $row['apellido'], 1);
    $pdf->Cell(70, 10, $row['correo'], 1);
    $pdf->Ln();
}

$conexion->close();

$pdf->Output('D', 'usuarios.pdf');
exit;
