<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: ../usuarios/login.php');
    exit();
}

include '../../config/conexion.php';
include '../../includes/header.php';
include '../../functions/CRUD/facturas_functions.php';

// Obtener facturas de todos los clientes
$facturas = obtener_todas_facturas_ventas($conexion);
?>

<div id="page-content-wrapper">
    <div class="container mt-5">
        <h1 class="mb-4">Facturas</h1>

        <div class="mb-3">
            <a href="agregar-factura-ventas.php" class="btn btn-success mb-3">Agregar Factura</a>
        </div>

        <!-- Tabla de Facturas -->
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Cliente</th>
                    <th>Folio</th>
                    <th>Fecha</th>
                    <th>Total</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($factura = $facturas->fetch_assoc()) : ?>
                    <tr>
                        <td><?php echo $factura['clt_nombre']; ?></td>
                        <td><?php echo $factura['fact_folio']; ?></td>
                        <td><?php echo $factura['fact_fecha']; ?></td>
                        <td><?php echo $factura['fact_total']; ?></td>
                        <td><?php echo $factura['fact_estado']; ?></td>
                        <td>
                            <a href="editar-factura.php?id=<?php echo $factura['fact_id']; ?>" class="btn btn-warning btn-sm">Editar</a>
                            <a href="eliminar-factura.php?id=<?php echo $factura['fact_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de eliminar esta factura?');">Eliminar</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>