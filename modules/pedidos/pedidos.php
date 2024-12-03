<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: ../usuarios/login.php');
    exit();
}

include '../../config/conexion.php';
include '../../includes/header.php';
include '../../functions/CRUD/pedidos_functions.php';

// Obtener pedidos de todos los clientes
$pedidos = obtener_todos_pedidos($conexion);
?>

<div id="page-content-wrapper">
    <div class="container mt-5">
        <h1 class="mb-4">Pedidos</h1>

        <div class="mb-3">
            <a href="agregar-pedido.php" class="btn btn-success mb-3">Agregar Pedido</a>
        </div>

        <!-- Tabla de Pedidos -->
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Cliente</th>
                    <th>Fecha</th>
                    <th>Total</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($pedido = $pedidos->fetch_assoc()) : ?>
                    <tr>
                        <td><?php echo $pedido['clt_nombre']; ?></td>
                        <td><?php echo $pedido['pedo_fecha']; ?></td>
                        <td><?php echo $pedido['pedo_total']; ?></td>
                        <td><?php echo $pedido['pedo_estado']; ?></td>
                        <td>
                            <a href="detalle_pedido.php?id=<?php echo $pedido['pedo_id']; ?>" class="btn btn-info">Ver Detalle</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php
    include '../../includes/footer.php';
?>