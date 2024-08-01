<?php
session_start(); // Iniciar la sesion
if (empty($_SESSION['active'])) {
    header('Location: ../'); // Redirigir si la sesion no esta activa
    exit; // Detener la ejecucion adicional
}

// Verificar si se hace la solicitud de descarga
if (isset($_GET['descargar_inventario'])) {
    // Tu codigo para generar el archivo de inventario va aqui
  

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

// Formato de salida: CSV
$csv_filename = 'inventario.csv';
$csv_handle = fopen($csv_filename, 'w');

// Escribir encabezados
$encabezados = array_keys($productos[0]);
fputcsv($csv_handle, $encabezados);

// Escribir datos de productos
foreach ($productos as $producto) {
    fputcsv($csv_handle, $producto);
}

// Cerrar el archivo CSV
fclose($csv_handle);

// Ofrecer el archivo CSV para descarga
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="' . $csv_filename . '"');
readfile($csv_filename);

// Salir del script
exit;
    // Por ejemplo, si tienes datos almacenados en una base de datos, puedes recuperarlos y formatearlos en un archivo CSV
    // Una vez que el archivo de inventario esté generado, puedes ofrecerlo para su descarga utilizando encabezados adecuados
    // Ejemplo:
    // header('Content-Type: text/csv');
    // header('Content-Disposition: attachment; filename="inventario.csv"');
    // // Aquí se imprime el contenido del archivo CSV
    // exit;
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Panel de Administración</title>
    <link href="../assets/css/material-dashboard.css" rel="stylesheet" />
    <link href="../assets/css/dataTables.bootstrap4.min.css" rel="stylesheet" crossorigin="anonymous" />
    <link rel="stylesheet" href="../assets/js/jquery-ui/jquery-ui.min.css">
    <script src="../assets/js/all.min.js" crossorigin="anonymous"></script>
</head>

<body>
    <div class="wrapper ">
        <div class="sidebar" data-color="purple" data-background-color="blue" data-image="../assets/img/sidebar.jpg">
            <div class="logo bg-primary"><a href="./" class="simple-text logo-normal">
                   Admin Inventario
                </a></div>
            <div class="sidebar-wrapper">
                <ul class="nav">
                    <!-- Links de navegación existentes -->
                    <!-- ... -->
                    <!-- Agregar enlace para descargar el inventario -->
                    <li class="nav-item">
                        <a class="nav-link d-flex" href="?descargar_inventario">
                            <i class="fas fa-download mr-2 fa-2x"></i>
                            <p> Descargar Inventario </p>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="main-panel">
            <!-- Resto del código HTML -->
            <!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventario de Productos</title>
    <link rel="stylesheet" href="../assets/css/material-dashboard.css">
</head>

<body>
    <div class="wrapper">
        <!-- Barra de navegación -->
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">Inventario de Productos</a>
            </div>
        </nav>

        <!-- Contenido principal -->
        <div class="container mt-4">
            <h2>Inventario de Productos</h2>
            <!-- Agregar tabla para mostrar los productos -->
           
                <tbody>
                    <!-- Aquí puedes cargar dinámicamente los datos de los productos desde PHP -->
                    <?php
                    // Conexión a la base de datos y consulta de productos
                    $dsn = 'mysql:host=localhost;dbname=sis_farmacia';
                $username = 'root';
                $password = '';

                try {
                    $pdo = new PDO($dsn, $username, $password);
                } catch (PDOException $e) {
                    die('Error de conexión: ' . $e->getMessage());
                }
                // Verificar si se proporciona una fecha para el reporte
                    $fecha_reporte = isset($_GET['fecha']) ? $_GET['fecha'] : date('Y-m-d');

                    // Consulta para obtener los productos actualizados en la fecha proporcionada
                    $sql = "SELECT * FROM producto WHERE DATE(fecha_ingreso) = ?";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([$fecha_reporte]);
                    $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    ?>
                           
                    <!-- Código HTML para mostrar los productos -->
              <!-- Código HTML para mostrar los productos -->
                        <!-- Formulario para introducir la fecha o periodo -->
           <!-- Formulario para introducir el periodo o lapso de tiempo -->
<div class="mt-4">
<form action="" method="GET">
    <div class="form-group">
        <label for="fecha_inicio">Fecha de Inicio:</label>
        <input type="date" id="fecha_inicio" name="fecha_inicio" class="form-control">
    </div>
    <div class="form-group">
        <label for="fecha_fin">Fecha de Fin:</label>
        <input type="date" id="fecha_fin" name="fecha_fin" class="form-control">
    </div>
    <button type="submit" class="btn btn-primary">Generar Informe</button>
</form>
    
</div>

<?php
// Verificar si se proporciona un periodo para el informe
if (isset($_GET['fecha_inicio'], $_GET['fecha_fin'])) {
    $fecha_inicio = $_GET['fecha_inicio'];
    $fecha_fin = $_GET['fecha_fin'];

    // Consulta para obtener los productos actualizados en el periodo proporcionado
    $sql = "SELECT * FROM producto WHERE fecha_ingreso BETWEEN ? AND ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$fecha_inicio, $fecha_fin]);
    $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Código HTML para mostrar los productos...
}
?>

<table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Precio</th>
                <th>Cantidad</th>
                <th>Fecha de Ingreso</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($productos as $producto): ?>
                <tr>
                    <td><?php echo $producto['codproducto']; ?></td>
                    <td><?php echo $producto['descripcion']; ?></td>
                    <td><?php echo $producto['precio']; ?></td>
                    <td><?php echo $producto['existencia']; ?></td>
                    <td><?php echo $producto['fecha_ingreso']; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Agregar un botón para descargar el archivo CSV -->
    <form action="exportar_csv.php" method="POST">
        <input type="hidden" name="fecha_inicio" value="<?php echo $fecha_inicio; ?>">
        <input type="hidden" name="fecha_fin" value="<?php echo $fecha_fin; ?>">
        <button type="submit" class="btn btn-primary">Descargar CSV</button>
    </form>

<?php
//else {
   // echo "Por favor, seleccione un rango de fechas para generar el informe.";
//}
?>





  

    <!-- Scripts -->
    <script src="../assets/js/all.min.js"></script>
</body>

</html>
            
            <!-- ... -->
        </div>
    </div>
</body>

</html>