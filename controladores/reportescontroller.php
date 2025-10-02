<?php
require_once "config/cn.php";
require_once "vendor/autoload.php"; // para Excel (PhpSpreadsheet)
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class reportescontroller {
    private $db;

    public function __construct() {
        $this->db = new CNpdo();
    }

    // Vista principal
    public function index() {
        $usuarios = $this->db->consulta("SELECT * FROM usuario");
        $libros = $this->db->consulta("SELECT l.id_libro, l.titulo, a.nombre AS autor, l.stock, l.disponible 
                                       FROM libros l 
                                       JOIN autores a ON l.id_autor = a.id_autor");
        $ventas = $this->db->consulta("SELECT v.id_venta, v.fecha, v.cliente_nombre, v.total, u.nombre AS usuario
                                       FROM venta v 
                                       JOIN usuario u ON v.id_usuario = u.id_usuario");

        require_once "vistas/reportes.php";
    }

    // 📄 Exportar a PDF
    public function pdf() {
        require_once "vendor/autoload.php";
        require_once "fpdf/fpdf.php";

        $usuarios = $this->db->consulta("SELECT * FROM usuario");
        $libros = $this->db->consulta("SELECT l.titulo, a.nombre AS autor, l.stock 
                                       FROM libros l JOIN autores a ON l.id_autor = a.id_autor");
        $ventas = $this->db->consulta("SELECT v.id_venta, v.fecha, v.cliente_nombre, v.total 
                                       FROM venta v");

        $pdf = new FPDF();
        $pdf->AddPage();

        // Título
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 10, '📊 Reporte General', 0, 1, 'C');
        $pdf->Ln(5);

        // USUARIOS
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->Cell(0, 10, 'Usuarios', 0, 1, 'L');
        $pdf->SetFont('Arial', '', 11);
        foreach ($usuarios as $u) {
            $pdf->Cell(0, 8, $u['id_usuario'] . " - " . $u['nombre'] . " " . $u['apellido'] . " - " . $u['correo'] . " - " . $u['rol'], 0, 1);
        }
        $pdf->Ln(5);

        // LIBROS
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->Cell(0, 10, 'Libros', 0, 1, 'L');
        $pdf->SetFont('Arial', '', 11);
        foreach ($libros as $l) {
            $pdf->Cell(0, 8, $l['titulo'] . " - " . $l['autor'] . " - Stock: " . $l['stock'], 0, 1);
        }
        $pdf->Ln(5);

        // VENTAS
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->Cell(0, 10, 'Ventas', 0, 1, 'L');
        $pdf->SetFont('Arial', '', 11);
        foreach ($ventas as $v) {
            $pdf->Cell(0, 8, "Venta #" . $v['id_venta'] . " - " . $v['cliente_nombre'] . " - Total: $" . $v['total'], 0, 1);
        }

        $pdf->Output('D', 'reporte_general.pdf');
    }

    // 📊 Exportar a Excel
    public function excel() {
        $usuarios = $this->db->consulta("SELECT * FROM usuario");
        $libros = $this->db->consulta("SELECT l.titulo, a.nombre AS autor, l.stock 
                                       FROM libros l JOIN autores a ON l.id_autor = a.id_autor");
        $ventas = $this->db->consulta("SELECT v.id_venta, v.fecha, v.cliente_nombre, v.total 
                                       FROM venta v");

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // USUARIOS
        $sheet->setCellValue('A1', 'Usuarios');
        $sheet->fromArray(array_keys($usuarios[0] ?? []), NULL, 'A2');
        $sheet->fromArray($usuarios, NULL, 'A3');

        // LIBROS
        $librosStartRow = count($usuarios) + 5;
        $sheet->setCellValue("A$librosStartRow", 'Libros');
        $sheet->fromArray(array_keys($libros[0] ?? []), NULL, "A" . ($librosStartRow + 1));
        $sheet->fromArray($libros, NULL, "A" . ($librosStartRow + 2));

        // VENTAS
        $ventasStartRow = $librosStartRow + count($libros) + 5;
        $sheet->setCellValue("A$ventasStartRow", 'Ventas');
        $sheet->fromArray(array_keys($ventas[0] ?? []), NULL, "A" . ($ventasStartRow + 1));
        $sheet->fromArray($ventas, NULL, "A" . ($ventasStartRow + 2));

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="reporte_general.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }
}
