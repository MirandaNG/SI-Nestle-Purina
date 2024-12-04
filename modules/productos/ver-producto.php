<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: ../usuarios/login.php');
    exit();
}

include '../../config/conexion.php';
include '../../includes/header.php';
include '../../functions/CRUD/productos_functions.php';

// Obtener el ID del producto desde la URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<div class='alert alert-danger'>ID de producto inválido.</div>";
    exit();
}

$id = $_GET['id'];
$producto = obtener_producto_detallado($conexion, $id);

if (!$producto) {
    echo "<div class='alert alert-danger'>Producto no encontrado.</div>";
    exit();
}
?>

<div id="page-content-wrapper">
    <div class="container mt-5">
        <h1 class="mb-4">Detalles del Producto</h1>
        <p><strong>Nombre:</strong> <?php echo $producto['prod_nombre']; ?></p>
        <p><strong>Descripción:</strong> <?php echo $producto['prod_descripcion']; ?></p>
        <p><strong>Precio:</strong> $<?php echo number_format($producto['prod_precio'], 2); ?></p>
        <p><strong>Especie:</strong> <?php echo $producto['esp_nombre']; ?></p>
        <p><strong>Tipo de Comida:</strong> <?php echo $producto['tip_com_nombre']; ?></p>
        <p><strong>Etapa de Vida:</strong> <?php echo $producto['etpa_vida_nombre']; ?></p>
        <p><strong>Marca:</strong> <?php echo $producto['prod_marca']; ?></p>

        <a href="productos.php" class="btn btn-primary">Volver</a>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>