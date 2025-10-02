<?php
// reportes/autores_excel.php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/cn.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$db = new CNpdo();
$autores = $db->consulta("SELECT a.id_autor, a.nombre, a.nacionalidad, COUNT(l.id_libro) AS total_libros
                          FROM autores a
                          LEFT JOIN libros l ON l.id_autor = a.id_autor
                          GROUP BY a.id_autor, a.nombre, a.nacionalidad");

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Autores');

$sheet->setCellValue('A1','ID');
$sheet->setCellValue('B1','Nombre');
$sheet->setCellValue('C1','Nacionalidad');
$sheet->setCellValue('D1','Total_Libros');

$row = 2;
foreach ($autores as $a) {
    $sheet->setCellValue('A'.$row, $a['id_autor']);
    $sheet->setCellValue('B'.$row, $a['nombre']);
    $sheet->setCellValue('C'.$row, $a['nacionalidad']);
    $sheet->setCellValue('D'.$row, $a['total_libros']);
    $row++;
}

$filename = 'autores_reporte.xlsx';
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment; filename=\"$filename\"");
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
