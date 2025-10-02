<?php

require_once "config/cn.php";
require_once "vendor/autoload.php"; // Para Excel (PhpSpreadsheet)
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ReportesController {
    private $db;

    public function __construct() {
        $this->db = new CNpdo();
    }

    // Vista principal para mostrar los botones de descarga
    public function index() {
        include 'vistas/reportes/index.php';  // Cargar la vista con los botones de descarga
    }

    // 📄 Exportar a PDF (Usuarios)
    public function usuarios_pdf() {
        require_once "fpdf/fpdf.php";  // FPDF para generar el PDF

        // Consultar datos de usuarios
        $usuarios = $this->db->consulta("SELECT * FROM usuario");

        // Crear PDF
        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 10, '📊 Reporte de Usuarios', 0, 1, 'C');
        $pdf->Ln(10);

        // Insertar los datos de usuarios en el PDF
        $pdf->SetFont('Arial', '', 12);
        foreach ($usuarios as $u) {
            $pdf->Cell(0, 10, $u['id_usuario'] . " - " . $u['nombre'] . " " . $u['apellido'] . " - " . $u['correo'] . " - " . $u['rol'], 0, 1);
        }

        // Salida del PDF
        $pdf->Output('D', 'reporte_usuarios.pdf');
    }

    // 📊 Exportar a Excel (Usuarios)
    public function usuarios_excel() {
        // Consultar datos de usuarios
        $usuarios = $this->db->consulta("SELECT * FROM usuario");

        // Crear objeto de Excel
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Usuarios');

        // Encabezados
        $sheet->setCellValue('A1', 'ID');
        $sheet->setCellValue('B1', 'Nombre');
        $sheet->setCellValue('C1', 'Apellido');
        $sheet->setCellValue('D1', 'Correo');
        $sheet->setCellValue('E1', 'Rol');

        // Insertar datos
        $row = 2;
        foreach ($usuarios as $u) {
            $sheet->setCellValue('A' . $row, $u['id_usuario']);
            $sheet->setCellValue('B' . $row, $u['nombre']);
            $sheet->setCellValue('C' . $row, $u['apellido']);
            $sheet->setCellValue('D' . $row, $u['correo']);
            $sheet->setCellValue('E' . $row, $u['rol']);
            $row++;
        }

        // Salida del archivo Excel
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="reporte_usuarios.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    // 📄 Exportar a PDF (Libros)
    public function libros_pdf() {
        // Consultar datos de libros
        $libros = $this->db->consulta("SELECT l.titulo, a.nombre AS autor, l.stock 
                                       FROM libros l 
                                       JOIN autores a ON l.id_autor = a.id_autor");

        // Crear PDF
        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 10, '📊 Reporte de Libros', 0, 1, 'C');
        $pdf->Ln(10);

        // Insertar los datos de libros en el PDF
        $pdf->SetFont('Arial', '', 12);
        foreach ($libros as $l) {
            $pdf->Cell(0, 10, $l['titulo'] . " - " . $l['autor'] . " - Stock: " . $l['stock'], 0, 1);
        }

        // Salida del PDF
        $pdf->Output('D', 'reporte_libros.pdf');
    }

    // 📊 Exportar a Excel (Libros)
    public function libros_excel() {
        // Consultar datos de libros
        $libros = $this->db->consulta("SELECT l.titulo, a.nombre AS autor, l.stock 
                                       FROM libros l 
                                       JOIN autores a ON l.id_autor = a.id_autor");

        // Crear objeto de Excel
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Libros');

        // Encabezados
        $sheet->setCellValue('A1', 'Título');
        $sheet->setCellValue('B1', 'Autor');
        $sheet->setCellValue('C1', 'Stock');

        // Insertar datos
        $row = 2;
        foreach ($libros as $l) {
            $sheet->setCellValue('A' . $row, $l['titulo']);
            $sheet->setCellValue('B' . $row, $l['autor']);
            $sheet->setCellValue('C' . $row, $l['stock']);
            $row++;
        }

        // Salida del archivo Excel
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="reporte_libros.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    // 📄 Exportar a PDF (Autores)
    public function autores_pdf() {
        require_once "fpdf/fpdf.php";  // FPDF para generar el PDF

        // Consultar datos de autores
        $autores = $this->db->consulta("SELECT a.id_autor, a.nombre, a.nacionalidad, COUNT(l.id_libro) AS total_libros
                                       FROM autores a
                                       LEFT JOIN libros l ON l.id_autor = a.id_autor
                                       GROUP BY a.id_autor, a.nombre, a.nacionalidad");

        // Crear PDF
        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 10, '📊 Reporte de Autores', 0, 1, 'C');
        $pdf->Ln(10);

        // Insertar los datos de autores en el PDF
        $pdf->SetFont('Arial', '', 12);
        foreach ($autores as $a) {
            $pdf->Cell(0, 10, $a['id_autor'] . " - " . $a['nombre'] . " - " . $a['nacionalidad'] . " - " . $a['total_libros'] . " Libros", 0, 1);
        }

        // Salida del PDF
        $pdf->Output('D', 'reporte_autores.pdf');
    }

    // 📊 Exportar a Excel (Autores)
    public function autores_excel() {
        // Consultar datos de autores
        $autores = $this->db->consulta("SELECT a.id_autor, a.nombre, a.nacionalidad, COUNT(l.id_libro) AS total_libros
                                       FROM autores a
                                       LEFT JOIN libros l ON l.id_autor = a.id_autor
                                       GROUP BY a.id_autor, a.nombre, a.nacionalidad");

        // Crear objeto de Excel
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Autores');

        // Encabezados
        $sheet->setCellValue('A1', 'ID');
        $sheet->setCellValue('B1', 'Nombre');
        $sheet->setCellValue('C1', 'Nacionalidad');
        $sheet->setCellValue('D1', 'Total Libros');

        // Insertar datos
        $row = 2;
        foreach ($autores as $a) {
            $sheet->setCellValue('A' . $row, $a['id_autor']);
            $sheet->setCellValue('B' . $row, $a['nombre']);
            $sheet->setCellValue('C' . $row, $a['nacionalidad']);
            $sheet->setCellValue('D' . $row, $a['total_libros']);
            $row++;
        }

        // Salida del archivo Excel
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="reporte_autores.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }
}
?>
