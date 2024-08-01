<?php
session_start();
require("../conexion.php");
if (!$conexion) {
    die("Error al conectar con la base de datos: " . mysqli_connect_error());
}

// Recoger los datos del formulario
$num_cotizacion = $_POST['num_cotizacion'];
$idcliente = $_POST['idcliente']; // Asegúrate de que este valor se recoge correctamente

// Imprimir los valores recibidos para verificar
echo "Número de Cotización: " . $num_cotizacion . "<br>";
echo "ID del Cliente: " . $idcliente . "<br>";

// Insertar los datos en la base de datos
$sql_insert = "INSERT INTO ventas (num_cotizacion, idcliente) VALUES ('$num_cotizacion', '$idcliente')";
if (mysqli_query($conexion, $sql_insert)) {
    // Si la inserción fue exitosa, realizar un commit explícito
    mysqli_commit($conexion);
    // Redirigir a alguna página de éxito o mostrar un mensaje de éxito
    header('Location: venta_exitosa.php');
    exit; // Terminar el script después de redirigir
} else {
    // Si la inserción falló, mostrar un mensaje de error
    echo "Error al guardar la venta: " . mysqli_error($conexion);
}

// Comprobación de errores en la consulta SQL
if(mysqli_error($conexion)) {
    echo "Error en la consulta: " . mysqli_error($conexion);
}
?>
