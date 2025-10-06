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
$imgW = 800; $imgH = 250; // grafico más compacto
$img = imagecreatetruecolor($imgW, $imgH);

// Colores
$white = imagecolorallocate($img,255,255,255);
$black = imagecolorallocate($img,0,0,0);
$barColor = imagecolorallocate($img, 60,130,200);
$gray = imagecolorallocate($img, 220,220,220);
$labelColor = imagecolorallocate($img,50,50,50);

imagefill($img,0,0,$white);

imagestring($img, 5, 10, 8, "📚 Libros por Autor (Top 10)", $black);

// Líneas de cuadrícula
$margin = 50;
$maxVal = max(1, max($counts ?: [1]));
$gridLines = 4;
for ($i=0;$i<=$gridLines;$i++){
    $y = $margin + (($imgH - $margin*1.5) * $i / $gridLines);
    imageline($img, $margin, $y, $imgW-$margin, $y, $gray);
}

// Barras
$keys = array_keys($counts);
$bars = count($keys) ?: 1;
$barW = ($imgW - $margin*2) / ($bars*1.5);
$gap = $barW / 3;
$x = $margin + $gap;
foreach ($counts as $author => $val) {
    $barH = ($imgH - $margin*1.5) * ($val / $maxVal);
    $x1 = (int)$x;
    $y1 = (int)($imgH - $margin - $barH);
    $x2 = (int)($x + $barW);
    $y2 = (int)($imgH - $margin);
    imagefilledrectangle($img, $x1, $y1, $x2, $y2, $barColor);

    $label = (strlen($author) > 15) ? substr($author,0,12).'...' : $author;
    imagestring($img, 3, $x1, $y2+2, $label . " ({$val})", $labelColor);
    $x += $barW + $gap;
}

$tmp = sys_get_temp_dir() . '/libros_chart_' . uniqid() . '.png';
imagepng($img, $tmp);
imagedestroy($img);

// PDF
$pdf = new FPDF('P','mm','A4');
$pdf->AddPage();

// Título
$pdf->SetFont('Arial','B',16);
$pdf->SetTextColor(255,255,255);
$pdf->SetFillColor(60,130,200);
$pdf->Cell(0,12,'Reporte de Libros',0,1,'C',true);
$pdf->Ln(4);

// Gráfico
$pdf->Image($tmp, ($pdf->GetPageWidth()-160)/2, null, 160);
$pdf->Ln(8);

// Tabla con color
$pdf->SetFont('Arial','B',12);
$pdf->SetFillColor(230, 230, 250); // color suave encabezado
$pdf->SetTextColor(0,0,0);
$pdf->Cell(12,10,'ID',1,0,'C',true);
$pdf->Cell(85,10,'Titulo',1,0,'C',true);
$pdf->Cell(55,10,'Autor',1,0,'C',true);
$pdf->Cell(20,10,'Stock',1,0,'C',true);
$pdf->Cell(18,10,'Disp',1,1,'C',true);

// Filas alternadas con color
$pdf->SetFont('Arial','',11);
$fill = false;
foreach ($libros as $l) {
    $pdf->SetFillColor($fill ? 245 : 255, $fill ? 245 : 255, 255);
    $pdf->Cell(12,8,$l['id_libro'],1,0,'C',true);
    $pdf->Cell(85,8,utf8_decode($l['titulo']),1,0,'L',true);
    $pdf->Cell(55,8,utf8_decode($l['autor']),1,0,'L',true);
    $pdf->Cell(20,8,$l['stock'],1,0,'C',true);
    $pdf->Cell(18,8,($l['disponible'] ? 'Si' : 'No'),1,1,'C',true);
    $fill = !$fill;
}

$pdffile = 'libros_reporte.pdf';
$pdf->Output('D', $pdffile);
@unlink($tmp);
exit;
?>
