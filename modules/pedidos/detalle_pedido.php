<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: ../usuarios/login.php');
    exit();
}

$usuario = $_SESSION['usuario'];

include '../../config/conexion.php';
include '../../includes/header.php';
include '../../functions/CRUD/pedidos_functions.php';
include '../../functions/CRUD/productos_functions.php';

// Verificar si el ID del pedido está presente
if (isset($_GET['pedo_id'])) {
    $pedo_id = $_GET['pedo_id'];

    // Obtener los datos del pedido
    $pedido = obtener_pedido($pedo_id, $conexion);
    $detalles = obtener_detalles_pedido($pedo_id, $conexion);
} else {
    echo "<div class='alert alert-danger'>No se ha especificado un pedido</div>";
    exit();
}
?>

<div id="page-content-wrapper">
    <div class="container mt-5">
        <h1 class="mb-4">Detalles del Pedido</h1>

        <div class="mb-4">
            <p><strong>ID del Pedido:</strong> <?php echo $pedido['pedo_id']; ?></p>
            <p><strong>Cliente:</strong> <?php echo $pedido['clt_nombre']; ?></p>
            <p><strong>Fecha de Pedido:</strong> <?php echo $pedido['pedo_fecha']; ?></p>
            <p><strong>Total del Pedido:</strong> $<?php echo number_format($pedido['pedo_total'], 2); ?></p>
        </div>

        <h3>Productos del Pedido</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Precio Unitario</th>
                    <th>Subtotal</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($detalles as $detalle): ?>
                    <tr>
                        <td><?php echo $detalle['prod_nombre']; ?></td>
                        <td><?php echo $detalle['det_pedo_cantidad']; ?></td>
                        <td>$<?php echo number_format($detalle['det_pedo_precio_unit'], 2); ?></td>
                        <td>$<?php echo number_format($detalle['det_pedo_subtotal'], 2); ?></td>
                        <td>
                            <!-- Botón para editar el detalle -->
                            <a href="editar_detalle.php?det_pedo_id=<?php echo $detalle['det_pedo_id']; ?>&pedo_id=<?php echo $pedido['pedo_id']; ?>" class="btn btn-warning btn-sm">Editar</a>
                            <!-- Botón para eliminar el detalle -->
                            <a href="eliminar_detalle.php?det_pedo_id=<?php echo $detalle['det_pedo_id']; ?>&pedo_id=<?php echo $pedido['pedo_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de eliminar este detalle del pedido?')">Eliminar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Botón para actualizar el pedido -->
        <a href="editar_pedido.php?pedo_id=<?php echo $pedido['pedo_id']; ?>" class="btn btn-primary mt-3">Actualizar Pedido</a>

        <!-- Botón para eliminar el pedido completo -->
        <a href="eliminar_pedido.php?pedo_id=<?php echo $pedido['pedo_id']; ?>" class="btn btn-danger mt-3" onclick="return confirm('¿Estás seguro de eliminar este pedido?')">Eliminar Pedido</a>

        <a href="pedidos.php" class="btn btn-primary mt-4">Volver a la lista de pedidos</a>
    </div>
</div>

<?php
include '../../includes/footer.php';
?>