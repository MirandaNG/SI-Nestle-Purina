<?php
    session_start();
    if (!isset($_SESSION['usuario'])) {
        header('Location: ../usuarios/login.php');
        exit();
    }

    include '../../config/conexion.php';
    include '../../includes/header.php';
    include '../../functions/CRUD/inventarios_functions.php';

    // Obtener lista de productos en inventario
    $inventario = obtener_inventario($conexion);
?>

<div id="page-content-wrapper">
    <div class="container mt-5">
        <h1 class="mb-4">Control de Stock</h1>

        <!-- Mostrar mensajes -->
        <?php if (isset($_SESSION['mensaje_exito'])): ?>
            <div class="alert alert-success"><?php echo $_SESSION['mensaje_exito']; unset($_SESSION['mensaje_exito']); ?></div>
        <?php endif; ?>
        <?php if (isset($_SESSION['mensaje_error'])): ?>
            <div class="alert alert-danger"><?php echo $_SESSION['mensaje_error']; unset($_SESSION['mensaje_error']); ?></div>
        <?php endif; ?>

        <!-- Botón para agregar producto -->
        <div class="mb-3">
            <a href="agregar-producto-inventario.php" class="btn btn-success mb-3">Agregar Producto</a>
        </div>

        <!-- Tabla de Inventario -->
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Tipo</th>
                    <th>Cantidad</th>
                    <th>Ubicación</th>
                    <th>Precio Unitario</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($producto = mysqli_fetch_assoc($inventario)): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($producto['inv_nombre']); ?></td>
                        <td><?php echo htmlspecialchars($producto['inv_tipo']); ?></td>
                        <td><?php echo $producto['inv_cantidad_actual']; ?></td>
                        <td><?php echo htmlspecialchars($producto['inv_ubicacion']); ?></td>
                        <td><?php echo $producto['inv_precio_unitario']; ?></td>
                        <td>
                            <a href="editar_producto_inventario.php?id=<?php echo $producto['inv_id']; ?>" class="btn btn-warning btn-sm">Editar</a>
                            <a href="eliminar_producto_inventario.php?id=<?php echo $producto['inv_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de eliminar este producto?');">Eliminar</a>
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