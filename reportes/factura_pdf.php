<?php
// reportes/factura_pdf.php
require_once __DIR__ . '/../vendor/setasign/fpdf/fpdf.php';
require_once __DIR__ . '/../config/cn.php';

function generarFacturaPDF($idVenta) {
    $db = new CNpdo();

    // Obtener datos de la venta
    $venta = $db->consulta("SELECT v.*, u.nombre AS usuario_nombre, u.correo AS usuario_correo
                            FROM venta v
                            LEFT JOIN usuario u ON u.id_usuario = v.id_usuario
                            WHERE v.id_venta = ?", [$idVenta])[0] ?? null;

    if (!$venta) die("Venta no encontrada.");

    // Obtener detalles de la venta
    $detalles = $db->consulta("
        SELECT dv.*, l.titulo
        FROM detalle_venta dv
        JOIN libros l ON l.id_libro = dv.id_libro
        WHERE dv.id_venta = ?", [$idVenta]);

    // Crear PDF tipo ticket (ancho reducido)
    $pdf = new FPDF('P','mm',array(80,200)); // ancho 80mm, altura automática
    $pdf->AddPage();
    $pdf->SetMargins(5,5,5);

    // Logo o nombre tienda
    $pdf->SetFont('Arial','B',14);
    $pdf->Cell(0,6," Tienda de Libros",0,1,'C');
    $pdf->SetFont('Arial','',10);
    $pdf->Cell(0,5,"www.tiendadelibros.com",0,1,'C');
    $pdf->Ln(2);

    // Datos de la venta / cliente
    $pdf->SetFont('Arial','',10);
    $pdf->Cell(0,5,"Cliente: ".utf8_decode($venta['cliente_nombre']),0,1);
    $pdf->Cell(0,5,"Usuario: ".utf8_decode($venta['usuario_nombre'] ?? 'N/A'),0,1);
    $pdf->Cell(0,5,"Correo: ".($venta['usuario_correo'] ?? 'N/A'),0,1);
    $pdf->Cell(0,5,"ID Venta: ".$venta['id_venta'],0,1);
    $pdf->Cell(0,5,"Fecha: ".($venta['fecha'] ?? date('Y-m-d H:i')),0,1);
    $pdf->Ln(2);

    // Encabezado productos
    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(40,6,'Producto',0,0);
    $pdf->Cell(10,6,'Cant',0,0,'C');
    $pdf->Cell(20,6,'Subtotal',0,1,'R');
    $pdf->Line(0,$pdf->GetY(),80,$pdf->GetY());
    $pdf->Ln(1);

    // Productos
    $pdf->SetFont('Arial','',10);
    $total = 0;
    foreach ($detalles as $d) {
        $subtotal = $d['cantidad'] * $d['precio_unitario'];
        $total += $subtotal;

        $titulo = utf8_decode($d['titulo']);
        // Ajuste de texto largo
        if(strlen($titulo) > 25) $titulo = substr($titulo,0,22).'...';

        $pdf->Cell(40,5,$titulo,0,0);
        $pdf->Cell(10,5,$d['cantidad'],0,0,'C');
        $pdf->Cell(20,5,'$'.number_format($subtotal,2),0,1,'R');
    }
    $pdf->Line(0,$pdf->GetY(),80,$pdf->GetY());
    $pdf->Ln(2);

    // Total
    $pdf->SetFont('Arial','B',12);
    $pdf->Cell(50,6,'TOTAL',0,0,'R');
    $pdf->Cell(20,6,'$'.number_format($total,2),0,1,'R');
    $pdf->Ln(3);

    // Mensaje de agradecimiento
    $pdf->SetFont('Arial','I',9);
    $pdf->MultiCell(0,4,"Gracias por su compra!\nVisite nuestra tienda nuevamente.",0,'C');
    $pdf->Ln(2);

    // Generar PDF para descarga
    $filename = 'factura_ticket_'.$idVenta.'.pdf';
    $pdf->Output('D',$filename);
    exit;
}
?>
