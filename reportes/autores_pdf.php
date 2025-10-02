<?php
// reportes/autores_pdf.php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/cn.php';
require_once __DIR__ . '/../vendor/setasign/fpdf/fpdf.php';

$db = new CNpdo();

// Autores y conteo de libros por autor
$autores = $db->consulta("SELECT a.id_autor, a.nombre, a.nacionalidad, COUNT(l.id_libro) AS total_libros
                          FROM autores a
                          LEFT JOIN libros l ON l.id_autor = a.id_autor
                          GROUP BY a.id_autor, a.nombre, a.nacionalidad");

// Datos para gráfica
$counts = [];
foreach ($autores as $a) {
    $counts[$a['nombre']] = (int)$a['total_libros'];
}
arsort($counts);
$counts = array_slice($counts, 0, 10, true);

// Generar PNG
$imgW = 800; $imgH = 350;
$img = imagecreatetruecolor($imgW, $imgH);
$white = imagecolorallocate($img,255,255,255);
$black = imagecolorallocate($img,0,0,0);
$barColor = imagecolorallocate($img, 120,80,200);
$gray = imagecolorallocate($img, 230,230,230);
imagefill($img,0,0,$white);

imagestring($img, 5, 10, 8, "Autores - Total de Libros (Top 10)", $black);

$margin = 60;
$maxVal = max(1, max($counts ?: [1]));
$gridLines = 5;
for ($i=0;$i<=$gridLines;$i++){
    $y = $margin + (($imgH - $margin*2) * $i / $gridLines);
    imageline($img, $margin, $y, $imgW-$margin, $y, $gray);
}

$keys = array_keys($counts);
$bars = count($keys) ?: 1;
$barW = ($imgW - $margin*2) / ($bars*1.6);
$gap = $barW / 2;
$x = $margin + $gap;
foreach ($counts as $author => $val) {
    $barH = ($imgH - $margin*2) * ($val / $maxVal);
    $x1 = (int)$x;
    $y1 = (int)($imgH - $margin - $barH);
    $x2 = (int)($x + $barW);
    $y2 = (int)($imgH - $margin);
    imagefilledrectangle($img, $x1, $y1, $x2, $y2, $barColor);
    $label = (strlen($author) > 18) ? substr($author,0,15).'...' : $author;
    imagestring($img, 3, $x1, $y2+4, $label . " ({$val})", $black);
    $x += $barW + $gap;
}

$tmp = sys_get_temp_dir() . '/autores_chart_' . uniqid() . '.png';
imagepng($img, $tmp);
imagedestroy($img);

// PDF
$pdf = new FPDF('P','mm','A4');
$pdf->AddPage();
$pdf->SetFont('Arial','B',16);
$pdf->Cell(0,10,'Reporte Autores',0,1,'C');
$pdf->Ln(4);
$pdf->Image($tmp, ($pdf->GetPageWidth()-170)/2, null, 170);
$pdf->Ln(100);

// Tabla
$pdf->SetFont('Arial','B',12);
$pdf->Cell(15,10,'ID',1);
$pdf->Cell(80,10,'Nombre',1);
$pdf->Cell(50,10,'Nacionalidad',1);
$pdf->Cell(30,10,'#Libros',1);
$pdf->Ln();

$pdf->SetFont('Arial','',11);
foreach ($autores as $a) {
    $pdf->Cell(15,8,$a['id_autor'],1);
    $pdf->Cell(80,8,utf8_decode($a['nombre']),1);
    $pdf->Cell(50,8,utf8_decode($a['nacionalidad']),1);
    $pdf->Cell(30,8,$a['total_libros'],1);
    $pdf->Ln();
}

$pdffile = 'autores_reporte.pdf';
$pdf->Output('D', $pdffile);
@unlink($tmp);
exit;
