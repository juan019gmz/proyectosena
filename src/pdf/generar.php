<?php
require_once '../../conexion.php';
require_once 'fpdf/fpdf.php';
$pdf = new FPDF('P', 'mm', array(80, 200));
$pdf->AddPage();
$pdf->SetMargins(5, 0, 0);
$pdf->SetTitle("Ventas");
$pdf->SetFont('Arial', 'B', 12);
$id = $_GET['v'];
$idcliente = $_GET['cl'];
$config = mysqli_query($conexion, "SELECT * FROM configuracion");
$datos = mysqli_fetch_assoc($config);
$clientes = mysqli_query($conexion, "SELECT * FROM cliente WHERE idcliente = $idcliente");
$datosC = mysqli_fetch_assoc($clientes);
$ventas = mysqli_query($conexion, "SELECT d.*, p.codproducto, p.descripcion FROM detalle_venta d INNER JOIN producto p ON d.id_producto = p.codproducto WHERE d.id_venta = $id");
$pdf->Cell(65, 5, utf8_decode($datos['nombre']), 0, 1, 'C');
$pdf->image("../../assets/img/logo.png", 60, 15, 15, 15, 'png');
$pdf->SetFont('Arial', 'B', 7);
$pdf->Cell(15, 5, utf8_decode("Teléfono: "), 0, 0, 'L');
$pdf->SetFont('Arial', '', 7);
$pdf->Cell(15, 5, $datos['telefono'], 0, 1, 'L');
$pdf->SetFont('Arial', 'B', 7);
$pdf->Cell(15, 5, utf8_decode("Dirección: "), 0, 0, 'L');
$pdf->SetFont('Arial', '', 7);
$pdf->Cell(15, 5, utf8_decode($datos['direccion']), 0, 1, 'L');
$pdf->SetFont('Arial', 'B', 7);
$pdf->Cell(15, 5, "Correo: ", 0, 0, 'L');
$pdf->SetFont('Arial', '', 7);
$pdf->Cell(15, 5, utf8_decode($datos['email']), 0, 1, 'L');
$pdf->SetFont('Arial', 'B', 7);
$pdf->Cell(15, 5, "Nit: ", 0, 0, 'L');
$pdf->SetFont('Arial', '', 7);
$pdf->Cell(15, 5, utf8_decode($datos['nit']), 0, 1, 'L');

//facturacion y resolucion
//$pdf->SetTextColor(0, 0, 0);
//$pdf->SetFont('Arial', 'B', 7);
//$pdf->Cell(10, 5, "Prefijo: ", 0, 0, 'L');
//$pdf->SetFont('Arial', '', 7);
//$pdf->Cell(10, 5, utf8_decode($datos['prefijo']), 0, 1, 'L');



//Consecutivo ID VENTAS
while ($colum = mysqli_fetch_assoc($ventas)) {
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('Arial', 'B', 7);
$pdf->Cell(15, 5, "Consec #: ", 0, 0, 'L');
$pdf->SetFont('Arial', '', 7);
$pdf->Cell(15, 5, utf8_decode($colum['id']), 0, 1, 'L');}
$pdf->Ln();
$pdf->SetFont('Arial', 'B', 8);
$pdf->SetFillColor(0, 0, 0);
$pdf->SetTextColor(255, 255, 255);

//DATOS CLIENTE CON FONTS   
$pdf->Cell(70, 5, "Datos del cliente", 1, 1, 'C', 1);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(20, 5, utf8_decode('identificacion'), 0, 0, 'L');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(20, 5, utf8_decode($datosC['identificacion']), 0, 1, 'L');
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(12, 5, utf8_decode('Nombre'), 0, 0, 'L');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(20, 5, utf8_decode($datosC['nombre']), 0, 1, 'L');
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(13, 5, utf8_decode('Teléfono'), 0, 0, 'L');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(13, 5, utf8_decode($datosC['telefono']), 0, 1, 'L');
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(15, 5, utf8_decode('Dirección'), 0, 0, 'L');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(23, 5, utf8_decode($datosC['direccion']), 0, 1, 'L');

//conecion bd ventas para arrojar los valores 
$ventas = mysqli_query($conexion, "SELECT d.*, p.codproducto, p.descripcion FROM detalle_venta d INNER JOIN producto p ON d.id_producto = p.codproducto WHERE d.id_venta = $id");
$pdf->Ln(4);
$pdf->SetFont('Arial', 'B', 8);
$pdf->SetTextColor(255, 255, 255);
$pdf->Cell(70, 5, "Detalle de Producto", 1, 1, 'C', 1);
$pdf->SetTextColor(0, 0, 0);
$pdf->Cell(30, 5, utf8_decode('Descripción'), 0, 0, 'L');
$pdf->Cell(10, 5, 'Cant.', 0, 0, 'L');
$pdf->Cell(15, 5, 'Precio', 0, 0, 'L');
$pdf->Cell(15, 5, 'Sub Total.', 0, 1, 'L');
$pdf->SetFont('Arial', '', 7);
$total = 0.00;
$desc = 0.00;
while ($row = mysqli_fetch_assoc($ventas)) {
    $pdf->Cell(30, 5, $row['descripcion'], 0, 0, 'L');
    $pdf->Cell(10, 5, $row['cantidad'], 0, 0, 'L');
    $pdf->Cell(15, 5, $row['precio'], 0, 0, 'L');
    $sub_total = $row['total'];
    $total = $total + $sub_total;
    $desc = $desc + $row['descuento'];
    $pdf->Cell(15, 5, number_format($sub_total, 2, '.', ','), 0, 1, 'L');}

$pdf->Ln();
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(65, 5, 'Descuento Total', 0, 1, 'R');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(65, 5, number_format($desc, 2, '.', ','), 0, 1, 'R');
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(65, 5, 'Total Pagar', 0, 1, 'R');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(65, 5, number_format($total, 2, '.', ','), 0, 1, 'R');

//RESOLUCION

$pdf->Ln(4);
$pdf->SetFont('Arial', 'B', 8);
$pdf->SetTextColor(255, 255, 255);
//$pdf->Cell(70, 5, "ALMACEN ETC", 1, 1, 'C', 1);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('Arial', '', 7);
$pdf->Cell(10, 5, utf8_decode($datos['resolucion']), 0, 1, 'L');








$pdf->Output("ventas.pdf", "I");


?>