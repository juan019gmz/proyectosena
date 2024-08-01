<?php
session_start();
require_once "../conexion.php";
$id_user = $_SESSION['idUser'];
$permiso = "ventas";
$sql = mysqli_query($conexion, "SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = $id_user AND p.nombre = '$permiso'");
$existe = mysqli_fetch_all($sql);
if (empty($existe) && $id_user != 1) {
    header('Location: permisos.php');
}

// Obtener fechas seleccionadas del formulario
$fecha_inicio = isset($_POST['fecha_inicio']) ? $_POST['fecha_inicio'] : date('Y-m-01');
$fecha_fin = isset($_POST['fecha_fin']) ? $_POST['fecha_fin'] : date('Y-m-t');

// Consultar ventas y sus detalles filtradas por rango de fechas
$query = mysqli_query($conexion, "SELECT v.id AS venta_id, v.total, v.fecha, c.idcliente, c.nombre, p.descripcion, dv.precio, dv.cantidad
                                  FROM ventas v
                                  INNER JOIN cliente c ON v.id_cliente = c.idcliente
                                  INNER JOIN detalle_venta dv ON v.id = dv.id_venta
                                  INNER JOIN producto p ON dv.id_producto = p.codproducto
                                  WHERE DATE(v.fecha) BETWEEN '$fecha_inicio' AND '$fecha_fin'");
include_once "includes/header.php";
?>

<div class="card">
    <div class="card-header">
        Historial ventas
    </div>
    <div class="card-body">
        <form method="post" action="">
            <div class="form-group">
                <label for="fecha_inicio">Fecha inicio:</label>
                <input type="date" id="fecha_inicio" name="fecha_inicio" value="<?php echo $fecha_inicio; ?>" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="fecha_fin">Fecha fin:</label>
                <input type="date" id="fecha_fin" name="fecha_fin" value="<?php echo $fecha_fin; ?>" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Filtrar</button>
            <button type="button" class="btn btn-success" onclick="downloadCSV()">Descargar CSV</button>
        </form>
        <div class="table-responsive">
            <table class="table table-light" id="tbl">
                <thead class="thead-dark">
                    <tr>
                        <th>#</th>
                        <th>Cliente</th>                        
                        <th>Fecha</th>
                        <th>Descripción</th>
                        <th>Precio</th>
                        <th>Cantidad</th>
                        <th>Total</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($query)) { ?>
                        <tr>
                            <td><?php echo $row['venta_id']; ?></td>
                            <td><?php echo $row['nombre']; ?></td>                            
                            <td><?php echo $row['fecha']; ?></td>
                            <td><?php echo $row['descripcion']; ?></td>
                            <td><?php echo $row['precio']; ?></td>
                            <td><?php echo $row['cantidad']; ?></td>
                            <td><?php echo $row['total']; ?></td>
                            <td>
                                <a href="pdf/generar.php?cl=<?php echo $row['idcliente'] ?>&v=<?php echo $row['venta_id'] ?>" target="_blank" class="btn btn-danger"><i class="fas fa-file-pdf"></i></a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function downloadCSV() {
    var fechaInicio = document.getElementById('fecha_inicio').value;
    var fechaFin = document.getElementById('fecha_fin').value;
    window.location.href = 'descargar_csv.php?fecha_inicio=' + fechaInicio + '&fecha_fin=' + fechaFin;
}
</script>

<?php include_once "includes/footer.php"; ?>
