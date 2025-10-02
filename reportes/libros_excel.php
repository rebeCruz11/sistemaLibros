<?php
// reportes/libros_excel.php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/cn.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$db = new CNpdo();
$libros = $db->consulta("SELECT l.id_libro, l.titulo, a.nombre AS autor, l.stock, l.disponible
                         FROM libros l
                         JOIN autores a ON l.id_autor = a.id_autor");

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Libros');

$sheet->setCellValue('A1','ID');
$sheet->setCellValue('B1','Titulo');
$sheet->setCellValue('C1','Autor');
$sheet->setCellValue('D1','Stock');
$sheet->setCellValue('E1','Disponible');

$row = 2;
foreach ($libros as $l) {
    $sheet->setCellValue('A'.$row, $l['id_libro']);
    $sheet->setCellValue('B'.$row, $l['titulo']);
    $sheet->setCellValue('C'.$row, $l['autor']);
    $sheet->setCellValue('D'.$row, $l['stock']);
    $sheet->setCellValue('E'.$row, ($l['disponible'] ? 'Si' : 'No'));
    $row++;
}

$filename = 'libros_reporte.xlsx';
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment; filename=\"$filename\"");
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
