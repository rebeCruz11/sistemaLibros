<?php
// reportes/usuarios_excel.php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/cn.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$db = new CNpdo();
$usuarios = $db->consulta("SELECT id_usuario, nombre, apellido, correo, rol FROM usuario");

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Usuarios');

// Cabeceras
$sheet->setCellValue('A1','ID');
$sheet->setCellValue('B1','Nombre');
$sheet->setCellValue('C1','Apellido');
$sheet->setCellValue('D1','Correo');
$sheet->setCellValue('E1','Rol');

// Datos
$row = 2;
foreach ($usuarios as $u) {
    $sheet->setCellValue('A'.$row, $u['id_usuario']);
    $sheet->setCellValue('B'.$row, $u['nombre']);
    $sheet->setCellValue('C'.$row, $u['apellido']);
    $sheet->setCellValue('D'.$row, $u['correo']);
    $sheet->setCellValue('E'.$row, $u['rol']);
    $row++;
}

$filename = 'usuarios_reporte.xlsx';
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment; filename=\"$filename\"");
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
