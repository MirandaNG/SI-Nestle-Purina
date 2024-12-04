<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: ../usuarios/login.php');
    exit();
}

include '../../config/conexion.php';
include '../../includes/header.php';
include '../../functions/CRUD/facturas_functions.php';

// Verificar si se pasó el ID de la factura a eliminar
if (isset($_GET['id'])) {
    $fact_id = $_GET['id'];

    // Obtener los detalles de la factura a eliminar
    $factura = obtener_factura_por_id($fact_id, $conexion);
    if (!$factura) {
        echo "<div class='alert alert-danger'>Factura no encontrada.</div>";
        exit();
    }
} else {
    echo "<div class='alert alert-danger'>No se ha especificado una factura para eliminar.</div>";
    exit();
}

// Si el formulario es enviado para eliminar la factura
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['eliminar_factura'])) {
    $resultado = eliminar_factura($fact_id, $conexion);

    if ($resultado) {
        header('Location: facturas.php?mensaje=Factura eliminada con éxito');
        exit();
    } else {
        echo "<div class='alert alert-danger'>Hubo un error al eliminar la factura.</div>";
    }
}
?>

<div id="page-content-wrapper">
    <div class="container mt-5">
        <h1 class="mb-4">Eliminar Factura</h1>
        <p>¿Está seguro de que desea eliminar esta factura?</p>
        
        <div class="alert alert-warning">
            <strong>¡Advertencia!</strong> Esta acción no se puede deshacer.
        </div>

        <div class="mb-3">
            <strong>Folio:</strong> <?php echo $factura['fact_folio']; ?><br>
            <strong>Tipo:</strong> <?php echo $factura['fact_tipo']; ?><br>
            <strong>Total:</strong> $<?php echo number_format($factura['fact_total'], 2); ?><br>
            <strong>Estado:</strong> <?php echo ucfirst($factura['fact_estado']); ?><br>
            <strong>Fecha:</strong> <?php echo date('d/m/Y', strtotime($factura['fact_fecha'])); ?><br>
        </div>

        <!-- Confirmación para eliminar la factura -->
        <form method="POST">
            <button type="submit" name="eliminar_factura" class="btn btn-danger">Eliminar Factura</button>
            <a href="facturas.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>
