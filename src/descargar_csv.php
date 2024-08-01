<?php
require_once "../conexion.php";

// Obtener las fechas de la consulta
$fecha_inicio = isset($_GET['fecha_inicio']) ? $_GET['fecha_inicio'] : date('Y-m-01');
$fecha_fin = isset($_GET['fecha_fin']) ? $_GET['fecha_fin'] : date('Y-m-t');

// Consultar ventas y sus detalles filtradas por rango de fechas
$query = mysqli_query($conexion, "SELECT v.id AS venta_id,  v.fecha, c.identificacion, c.nombre, p.descripcion, dv.precio, dv.cantidad, v.total
                                  FROM ventas v
                                  INNER JOIN cliente c ON v.id_cliente = c.idcliente
                                  INNER JOIN detalle_venta dv ON v.id = dv.id_venta
                                  INNER JOIN producto p ON dv.id_producto = p.codproducto
                                  WHERE DATE(v.fecha) BETWEEN '$fecha_inicio' AND '$fecha_fin'");

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="ventas_' . $fecha_inicio . '_to_' . $fecha_fin . '.csv"');

$output = fopen('php://output', 'w');
fputcsv($output, array( 'Consecutivo', 'Fecha', 'Identificacion cliente', 'Nombre Cliente','Descripcion', 'Valor unitario', 'Cantidad', 'Total Venta'));

while ($row = mysqli_fetch_assoc($query)) {
    fputcsv($output, $row);
}

fclose($output);
exit();
?>
