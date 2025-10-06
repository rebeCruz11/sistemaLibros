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
    // Consultar datos de las tres tablas
    $usuarios = $this->db->consulta("SELECT * FROM usuario");
    $libros = $this->db->consulta("SELECT l.titulo, a.nombre AS autor, l.stock 
                                   FROM libros l 
                                   JOIN autores a ON l.id_autor = a.id_autor");
    $autores = $this->db->consulta("SELECT a.id_autor, a.nombre, a.nacionalidad, COUNT(l.id_libro) AS total_libros
                                   FROM autores a
                                   LEFT JOIN libros l ON l.id_autor = a.id_autor
                                   GROUP BY a.id_autor, a.nombre, a.nacionalidad");

    // Enviar los datos a la vista
    include 'vistas/reportes/index.php';
}


    // Exportar a PDF (Usuarios) con tabla dinámica
    public function usuarios_pdf() {
    require_once __DIR__ . "/../vendor/setasign/fpdf/fpdf.php";

    $usuarios = $this->db->consulta("SELECT * FROM usuario");

    // --- Datos para gráfica: Usuarios por Rol ---
    $roles = [];
    foreach ($usuarios as $u) {
        $roles[$u['rol']] = ($roles[$u['rol']] ?? 0) + 1;
    }
    arsort($roles);

    $pdf = new FPDF('P','mm','A4');
    $pdf->SetMargins(15,15,15);
    $pdf->AddPage();
    $pdf->SetFont('Arial','B',16);
    $pdf->Cell(0,10,'Reporte de Usuarios',0,1,'C');
    $pdf->Ln(5);

    // --- Gráfica ---
    if(!empty($roles)){
        $chartConfig = [
            "type"=>"bar",
            "data"=>[
                "labels"=>array_keys($roles),
                "datasets"=>[["label"=>"Usuarios por Rol","data"=>array_values($roles),
                    "backgroundColor"=>["#4e73df","#1cc88a","#36b9cc","#f6c23e","#e74a3b"]
                ]]
            ],
            "options"=>["plugins"=>["legend"=>["display"=>true,"position"=>"top"]],
                        "title"=>["display"=>true,"text"=>"Usuarios por Rol"]]
        ];
        $chartUrl = "https://quickchart.io/chart?c=".urlencode(json_encode($chartConfig));
        $tmpfile = tempnam(sys_get_temp_dir(),"chart").".png";
        file_put_contents($tmpfile, file_get_contents($chartUrl));
        $pdf->Image($tmpfile, ($pdf->GetPageWidth()-160)/2, null, 160, 90);
        $pdf->Ln(95);
        @unlink($tmpfile);
    }

    // --- Tabla dinámica centrada ---
    $pdf->SetFont('Arial','B',12);
    $pdf->SetFillColor(200,220,255);

    $headers = ['ID','Nombre','Apellido','Correo','Rol'];
    $pageWidth = $pdf->GetPageWidth() - 30; // Margen 15mm
    $widths = [15,35,35,$pageWidth - (15+35+35+20),20];

    // Cabecera
    $xStart = ($pdf->GetPageWidth() - array_sum($widths)) / 2; // centrar tabla
    $pdf->SetX($xStart);
    foreach($headers as $i=>$h){
        $pdf->Cell($widths[$i],10,$h,1,0,'C',true);
    }
    $pdf->Ln();

    $pdf->SetFont('Arial','',11);
    foreach($usuarios as $u){
        $yStart = $pdf->GetY();
        $pdf->SetX($xStart);

        $pdf->Cell($widths[0],8,$u['id_usuario'],1);
        $pdf->Cell($widths[1],8,utf8_decode($u['nombre']),1);
        $pdf->Cell($widths[2],8,utf8_decode($u['apellido']),1);

        $pdf->SetXY($xStart + $widths[0] + $widths[1] + $widths[2], $yStart);
        $pdf->MultiCell($widths[3],6,utf8_decode($u['correo']),1);

        $multiHeight = $pdf->GetY() - $yStart;

        $pdf->SetXY($xStart + $widths[0] + $widths[1] + $widths[2] + $widths[3], $yStart);
        $pdf->Cell($widths[4],$multiHeight,utf8_decode($u['rol']),1);

        $pdf->SetXY($xStart, $yStart + $multiHeight);
    }

    $pdf->Output('D','reporte_usuarios.pdf');
}



    // 📊 Exportar a Excel (Usuarios)
    public function usuarios_excel() {
        $usuarios = $this->db->consulta("SELECT * FROM usuario");

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Usuarios');

        // Encabezados con estilo
        $encabezados = ['ID', 'Nombre', 'Apellido', 'Correo', 'Rol'];
        $col = 'A';
        foreach ($encabezados as $titulo) {
            $sheet->setCellValue($col . '1', $titulo);
            $sheet->getStyle($col . '1')->getFont()->setBold(true);
            $sheet->getStyle($col . '1')->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setARGB('FFDDDDDD');
            $sheet->getColumnDimension($col)->setAutoSize(true);
            $col++;
        }

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

        // Descargar archivo
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="reporte_usuarios.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    // 📄 Exportar a PDF (Libros)
    // 📄 Exportar a PDF (Libros)
public function libros_pdf() {
    require_once __DIR__ . "/../vendor/setasign/fpdf/fpdf.php";

    $libros = $this->db->consulta("SELECT l.id_libro, l.titulo, a.nombre AS autor, l.stock, l.precio
                                   FROM libros l 
                                   JOIN autores a ON l.id_autor = a.id_autor");

    // --- Contar libros por autor para la gráfica ---
    $counts = [];
    foreach ($libros as $r) {
        $a = $r['autor'] ?? 'Desconocido';
        $counts[$a] = ($counts[$a] ?? 0) + 1;
    }
    arsort($counts);
    $counts = array_slice($counts, 0, 10, true);

    // --- PDF ---
    $pdf = new FPDF('P','mm','A4');
    $pdf->SetMargins(15,15,15);
    $pdf->AddPage();

    // Título
    $pdf->SetFont('Arial','B',18);
    $pdf->SetTextColor(33,37,41); // gris oscuro
    $pdf->Cell(0,10,'Reporte de Libros',0,1,'C');
    $pdf->Ln(3);

    // --- Gráfica ---
    $chartHeight = 70; // altura del gráfico
    if(!empty($counts)){
        $chartConfig = [
            "type"=>"bar",
            "data"=>[
                "labels"=>array_keys($counts),
                "datasets"=>[[
                    "label"=>"Libros por Autor",
                    "data"=>array_values($counts),
                    "backgroundColor"=>["#4e73df","#1cc88a","#36b9cc","#f6c23e","#e74a3b","#fd7e14","#6f42c1","#20c997","#ffc107","#dc3545"]
                ]]
            ],
            "options"=>[
                "plugins"=>["legend"=>["display"=>true,"position"=>"top"]],
                "title"=>["display"=>true,"text"=>"Top 10 Autores por Libros"]
            ]
        ];
        $chartUrl = "https://quickchart.io/chart?c=".urlencode(json_encode($chartConfig));
        $tmpfile = tempnam(sys_get_temp_dir(),"chart").".png";
        file_put_contents($tmpfile, file_get_contents($chartUrl));
        $pdf->Image($tmpfile, ($pdf->GetPageWidth()-160)/2, null, 160, $chartHeight); 
        $pdf->Ln($chartHeight + 5); // ahora dejamos solo 5 mm extra debajo del gráfico
        @unlink($tmpfile);
    }

    // --- Tabla centrada con colores ---
    $pdf->SetFont('Arial','B',12);
    $pdf->SetFillColor(230,245,255); // color de fondo de encabezado
    $pdf->SetDrawColor(200,220,255); // color de borde

    $headers = ['ID','Título','Autor','Stock','Precio'];
    $widths = [15,70,50,20,25];

    $xStart = ($pdf->GetPageWidth() - array_sum($widths)) / 2;
    $pdf->SetX($xStart);

    foreach($headers as $i=>$h){
        $pdf->Cell($widths[$i],9,$h,1,0,'C',true);
    }
    $pdf->Ln();

    // Datos de la tabla
    $pdf->SetFont('Arial','',11);
    $fill = false;
    foreach($libros as $l){
        $pdf->SetX($xStart);
        $pdf->SetFillColor($fill ? 245 : 255, $fill ? 245 : 255, 255); // filas alternadas
        $pdf->Cell($widths[0],8,$l['id_libro'],1,'','C',$fill);
        $pdf->Cell($widths[1],8,utf8_decode($l['titulo']),1,'','L',$fill);
        $pdf->Cell($widths[2],8,utf8_decode($l['autor']),1,'','L',$fill);
        $pdf->Cell($widths[3],8,$l['stock'],1,'','C',$fill);
        $pdf->Cell($widths[4],8,'$'.number_format($l['precio'],2),1,'','C',$fill);
        $pdf->Ln();
        $fill = !$fill; // alternar color
    }

    // Descargar PDF
    $pdf->Output('D','libros_reporte.pdf');
}


    // 📊 Exportar a Excel (Libros)
    public function libros_excel() {
        $libros = $this->db->consulta("
            SELECT l.id_libro, l.titulo, a.nombre AS autor, l.stock, l.disponible, l.precio
            FROM libros l
            JOIN autores a ON l.id_autor = a.id_autor
        ");

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Libros');

        // Encabezados
        $headers = ['ID','Título','Autor','Stock','Disponible','Precio'];
        $col = 'A';
        foreach ($headers as $h) {
            $sheet->setCellValue($col.'1',$h);
            $sheet->getStyle($col.'1')->getFont()->setBold(true);
            $sheet->getStyle($col.'1')->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setARGB('FFDDDDDD');
            $sheet->getColumnDimension($col)->setAutoSize(true);
            $col++;
        }

        // Datos
        $row = 2;
        foreach ($libros as $l) {
            $sheet->setCellValue('A'.$row,$l['id_libro']);
            $sheet->setCellValue('B'.$row,$l['titulo']);
            $sheet->setCellValue('C'.$row,$l['autor']);
            $sheet->setCellValue('D'.$row,$l['stock']);
            $sheet->setCellValue('E'.$row,($l['disponible'] ? 'Sí' : 'No'));
            $sheet->setCellValue('F'.$row,$l['precio']);
            
            $row++;
        }

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="reporte_libros.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }



    //  Exportar a PDF (Autores) 
    public function autores_pdf() {
        require_once __DIR__ . "/../vendor/setasign/fpdf/fpdf.php";

        $autores = $this->db->consulta("SELECT a.id_autor, a.nombre, a.nacionalidad, COUNT(l.id_libro) AS total_libros
                                        FROM autores a
                                        LEFT JOIN libros l ON l.id_autor = a.id_autor
                                        GROUP BY a.id_autor, a.nombre, a.nacionalidad");

        // --- Datos para gráfica: Total Libros por Autor ---
        $counts = [];
        foreach($autores as $a){
            $counts[$a['nombre']] = $a['total_libros'];
        }
        arsort($counts);
        $counts = array_slice($counts,0,10,true);

        if(!empty($counts)){
            $chartConfig = [
                "type"=>"bar",
                "data"=>["labels"=>array_keys($counts),"datasets"=>[["label"=>"Total Libros","data"=>array_values($counts),
                    "backgroundColor"=>["#4e73df","#1cc88a","#36b9cc","#f6c23e","#e74a3b"]]]],
                "options"=>["plugins"=>["legend"=>["display"=>true,"position"=>"top"]],
                            "title"=>["display"=>true,"text"=>"Top 10 Autores por Libros"]]
            ];
            $chartUrl = "https://quickchart.io/chart?c=".urlencode(json_encode($chartConfig));
            $tmpfile = tempnam(sys_get_temp_dir(),"chart").".png";
            file_put_contents($tmpfile, file_get_contents($chartUrl));
        }

        $pdf = new FPDF('P','mm','A4');
        
        $pdf->AddPage();
        $pdf->SetFont('Arial','B',16);
        $pdf->Cell(0,10,'Reporte de Autores',0,1,'C');
        $pdf->Ln(5);

        if(!empty($counts)){
            $pdf->Image($tmpfile, ($pdf->GetPageWidth()-160)/2, null, 160, 90);
            $pdf->Ln(95);
            @unlink($tmpfile);
        }

        // Tabla
        $pdf->SetFont('Arial','B',12);
        $pdf->SetFillColor(200,220,255);
        $headers = ['ID','Nombre','Nacionalidad','Total Libros'];
        $widths = [15,60,50,35];
        foreach($headers as $i=>$h){
            $pdf->Cell($widths[$i],10,$h,1,0,'C',true);
        }
        $pdf->Ln();

        $pdf->SetFont('Arial','',11);
        foreach($autores as $a){
            $pdf->Cell($widths[0],8,$a['id_autor'],1);
            $pdf->Cell($widths[1],8,utf8_decode($a['nombre']),1);
            $pdf->Cell($widths[2],8,utf8_decode($a['nacionalidad']),1);
            $pdf->Cell($widths[3],8,$a['total_libros'],1,0,'C');
            $pdf->Ln();
        }

        $pdf->Output('D','reporte_autores.pdf');
    }


    //  Exportar a Excel (Autores)
    public function autores_excel() {
        $autores = $this->db->consulta("SELECT a.id_autor, a.nombre, a.nacionalidad, COUNT(l.id_libro) AS total_libros
                                    FROM autores a
                                    LEFT JOIN libros l ON l.id_autor = a.id_autor
                                    GROUP BY a.id_autor, a.nombre, a.nacionalidad");

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Autores');

        $headers = ['ID','Nombre','Nacionalidad','Total Libros'];
        $col = 'A';
        foreach ($headers as $h) {
            $sheet->setCellValue($col.'1',$h);
            $sheet->getStyle($col.'1')->getFont()->setBold(true);
            $sheet->getStyle($col.'1')->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setARGB('FFDDDDDD');
            $sheet->getColumnDimension($col)->setAutoSize(true);
            $col++;
        }

        $row = 2;
        foreach ($autores as $a) {
            $sheet->setCellValue('A'.$row,$a['id_autor']);
            $sheet->setCellValue('B'.$row,$a['nombre']);
            $sheet->setCellValue('C'.$row,$a['nacionalidad']);
            $sheet->setCellValue('D'.$row,$a['total_libros']);
            $row++;
        }

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="reporte_autores.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

}
?>
