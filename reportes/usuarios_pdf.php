<?php
// reportes/usuarios_pdf.php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/cn.php';
require_once __DIR__ . '/../vendor/setasign/fpdf/fpdf.php';

$db = new CNpdo();

// Consulta: todos los usuarios
$usuarios = $db->consulta("SELECT id_usuario, nombre, apellido, correo, rol FROM usuario");

// Datos para la gráfica: conteo por rol
$roles = ['admin' => 0, 'usuario' => 0];
foreach ($usuarios as $u) {
    $r = $u['rol'] ?? 'usuario';
    if (!isset($roles[$r])) $roles[$r] = 0;
    $roles[$r]++;
}

// Generar imagen PNG con GD
$imgW = 800; $imgH = 300;
$img = imagecreatetruecolor($imgW, $imgH);
$white = imagecolorallocate($img, 255,255,255);
$black = imagecolorallocate($img, 0,0,0);
$barColor = imagecolorallocate($img, 40,120,200);
$barColor2 = imagecolorallocate($img, 70,180,90);
$gray = imagecolorallocate($img, 230,230,230);
imagefill($img,0,0,$white);

// Title
$fontSize = 5;
imagestring($img, 5, 10, 8, "Usuarios por Rol", $black);

// Draw grid
$margin = 40;
$maxVal = max(1, max($roles));
$gridLines = 5;
for ($i=0;$i<=$gridLines;$i++){
    $y = $margin + (($imgH - $margin*2) * $i / $gridLines);
    imageline($img, $margin, $y, $imgW-$margin, $y, $gray);
    $val = round($maxVal * (1 - $i/$gridLines));
    imagestring($img, 3, 10, $y-7, $val, $black);
}

// Bars
$keys = array_keys($roles);
$bars = count($keys);
$barW = 80;
$gap = (($imgW - $margin*2) - ($bars*$barW)) / ($bars+1);
$x = $margin + $gap;
foreach ($keys as $i => $k) {
    $val = $roles[$k];
    $barH = ($imgH - $margin*2) * ($val / $maxVal);
    $x1 = (int)$x;
    $y1 = (int)($imgH - $margin - $barH);
    $x2 = (int)($x + $barW);
    $y2 = (int)($imgH - $margin);
    $color = $i%2==0 ? $barColor : $barColor2;
    imagefilledrectangle($img, $x1, $y1, $x2, $y2, $color);
    imagestring($img, 3, $x1+10, $y2+4, $k . " ({$val})", $black);
    $x += $barW + $gap;
}

// Guardar PNG temporal
$tmp = sys_get_temp_dir() . '/usuarios_chart_' . uniqid() . '.png';
imagepng($img, $tmp);
imagedestroy($img);

// Crear PDF e insertar imagen y tabla
$pdf = new FPDF('P','mm','A4');
$pdf->AddPage();
$pdf->SetFont('Arial','B',16);
$pdf->Cell(0,10,'Reporte Usuarios',0,1,'C');
$pdf->Ln(4);

// Insertar imagen (ajustamos tamaño para caber)
$imgWmm = 170; // ancho en mm
$pdf->Image($tmp, ($pdf->GetPageWidth()-$imgWmm)/2, null, $imgWmm);
$pdf->Ln(95);

// Tabla de usuarios
$pdf->SetFont('Arial','B',12);
$pdf->Cell(15,10,'ID',1);
$pdf->Cell(50,10,'Nombre',1);
$pdf->Cell(50,10,'Apellido',1);
$pdf->Cell(65,10,'Correo',1);
$pdf->Ln();

$pdf->SetFont('Arial','',11);
foreach ($usuarios as $u) {
    $pdf->Cell(15,8,$u['id_usuario'],1);
    $pdf->Cell(50,8,utf8_decode($u['nombre']),1);
    $pdf->Cell(50,8,utf8_decode($u['apellido']),1);
    $pdf->Cell(65,8,utf8_decode($u['correo']),1);
    $pdf->Ln();
}

// Enviar al navegador
$pdffile = 'usuarios_reporte.pdf';
$pdf->Output('D', $pdffile);

// borrar imagen temporal
@unlink($tmp);
exit;
