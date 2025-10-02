<?php
// reportes/libros_pdf.php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/cn.php';
require_once __DIR__ . '/../vendor/setasign/fpdf/fpdf.php';

$db = new CNpdo();

// Consulta: libros con autor nombre
$libros = $db->consulta("SELECT l.id_libro, l.titulo, a.nombre AS autor, l.stock, l.disponible
                         FROM libros l
                         JOIN autores a ON l.id_autor = a.id_autor");

// Datos para gráfica: libros por autor (top 10)
$counts = [];
foreach ($libros as $r) {
    $a = $r['autor'] ?? 'Desconocido';
    if (!isset($counts[$a])) $counts[$a] = 0;
    $counts[$a]++;
}
arsort($counts);
$counts = array_slice($counts, 0, 10, true);

// Generar imagen PNG con GD (barras)
$imgW = 800; $imgH = 350;
$img = imagecreatetruecolor($imgW, $imgH);
$white = imagecolorallocate($img,255,255,255);
$black = imagecolorallocate($img,0,0,0);
$barColor = imagecolorallocate($img, 60,130,200);
$gray = imagecolorallocate($img, 230,230,230);
imagefill($img,0,0,$white);

imagestring($img, 5, 10, 8, "Libros por Autor (Top 10)", $black);

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
    // label rotated not easy -> use short author label
    $label = (strlen($author) > 18) ? substr($author,0,15).'...' : $author;
    imagestring($img, 3, $x1, $y2+4, $label . " ({$val})", $black);
    $x += $barW + $gap;
}

$tmp = sys_get_temp_dir() . '/libros_chart_' . uniqid() . '.png';
imagepng($img, $tmp);
imagedestroy($img);

// PDF
$pdf = new FPDF('P','mm','A4');
$pdf->AddPage();
$pdf->SetFont('Arial','B',16);
$pdf->Cell(0,10,'Reporte Libros',0,1,'C');
$pdf->Ln(4);
$pdf->Image($tmp, ($pdf->GetPageWidth()-170)/2, null, 170);
$pdf->Ln(100);

// Tabla
$pdf->SetFont('Arial','B',12);
$pdf->Cell(12,10,'ID',1);
$pdf->Cell(85,10,'Titulo',1);
$pdf->Cell(55,10,'Autor',1);
$pdf->Cell(20,10,'Stock',1);
$pdf->Cell(18,10,'Disp',1);
$pdf->Ln();

$pdf->SetFont('Arial','',11);
foreach ($libros as $l) {
    $pdf->Cell(12,8,$l['id_libro'],1);
    $pdf->Cell(85,8,utf8_decode($l['titulo']),1);
    $pdf->Cell(55,8,utf8_decode($l['autor']),1);
    $pdf->Cell(20,8,$l['stock'],1);
    $pdf->Cell(18,8,($l['disponible'] ? 'Si' : 'No'),1);
    $pdf->Ln();
}

$pdffile = 'libros_reporte.pdf';
$pdf->Output('D', $pdffile);
@unlink($tmp);
exit;
