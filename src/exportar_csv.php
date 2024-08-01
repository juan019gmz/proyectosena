<?php
session_start(); // Iniciar la sesion
if (empty($_SESSION['active'])) {
    header('Location: ../'); // Redirigir si la sesion no esta activa
    exit; // Detener la ejecucion adicional
}
    // Conexion a la base de datos (ejemplo usando PDO)
$dsn = 'mysql:host=localhost;dbname=sis_farmacia';
$username = 'root';
$password = '';

try {
    $pdo = new PDO($dsn, $username, $password);
} catch (PDOException $e) {
    die('Error de conexión: ' . $e->getMessage());
}


// Consulta para obtener los datos del inventario
$sql = "SELECT * FROM producto";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$productos = $stmt->fetchAll(PDO::FETCH_ASSOC);


if (isset($_POST['fecha_inicio'], $_POST['fecha_fin'])) {
    $fecha_inicio = $_POST['fecha_inicio'];
    $fecha_fin = $_POST['fecha_fin'];

    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="informe_productos.csv"');

    $output = fopen('php://output', 'w');

    // Encabezados del CSV
    fputcsv($output, array('codproducto', 'codigo','descripcion', 'precio', 'existencia', 'id_lab', 'id_presentacion', 'id_tipo','vencimiento','fecha_ingreso'));

    // Consulta para obtener los productos actualizados en el periodo proporcionado
    $sql = "SELECT * FROM producto WHERE fecha_ingreso BETWEEN ? AND ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$fecha_inicio, $fecha_fin]);
    $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Escribir datos al archivo CSV
    foreach ($productos as $producto) {
        fputcsv($output, $producto);
    }

    fclose($output);
    exit();
}
?>