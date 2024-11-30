<?php
    session_start();
    if (!isset($_SESSION['usuario'])) {
        header('Location: ../usuarios/login.php');
        exit();
    }

    $usuario = $_SESSION['usuario'];

    include '../../config/conexion.php';
    include '../../includes/header.php';
    include '../../functions/CRUD/inventario_functions.php';

    // Obtener lista de productos en inventario
    $inventario = obtener_inventario($conexion);

    // Si el usuario envía el formulario para crear un nuevo producto
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['crear_producto'])) {
        $inv_nombre = $_POST['inv_nombre'];
        $inv_descripcion = $_POST['inv_descripcion'];
        $inv_tipo = $_POST['inv_tipo'];
        $inv_cantidad_actual = $_POST['inv_cantidad_actual'];
        $inv_ubicacion = $_POST['inv_ubicacion'];
        $inv_precio_unitario = $_POST['inv_precio_unitario'];

        $resultado = crear_producto_inventario($inv_nombre, $inv_descripcion, $inv_tipo, $inv_cantidad_actual, $inv_ubicacion, $inv_precio_unitario, $conexion);
        if ($resultado) {
            echo "Producto agregado con éxito";
        } else {
            echo "Error al agregar producto";
        }
    }
?>

<div id="page-content-wrapper">
    <div class="container mt-5">
        <h1 class="mb-4">Control de Stock</h1>

        <div class="mb-3">
            <a href="agregar-producto-inventario.php" class="btn btn-success mb-3">Agregar Producto</a>
        </div>

        <!-- Tabla de Inventario -->
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Descripción</th>
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
                        <td><?php echo htmlspecialchars($producto['inv_descripcion']); ?></td>
                        <td><?php echo htmlspecialchars($producto['inv_tipo']); ?></td>
                        <td><?php echo $producto['inv_cantidad_actual']; ?></td>
                        <td><?php echo htmlspecialchars($producto['inv_ubicacion']); ?></td>
                        <td><?php echo $producto['inv_precio_unitario']; ?></td>
                        <td>
                            <a href="editar-producto.php?id=<?php echo $producto['inv_id']; ?>" class="btn btn-warning btn-sm">Editar</a>
                            <a href="eliminar-producto.php?id=<?php echo $producto['inv_id']; ?>" class="btn btn-danger btn-sm">Eliminar</a>
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