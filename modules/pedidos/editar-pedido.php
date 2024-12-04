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
include '../../functions/CRUD/productos_functions.php'; // Para obtener productos disponibles

// Verificar si el ID del pedido está presente
if (isset($_GET['pedo_id'])) {
    $pedo_id = $_GET['pedo_id'];

    // Obtener los datos del pedido
    $pedido = obtener_pedido($pedo_id, $conexion);
    $detalles = obtener_detalles_pedido($pedo_id, $conexion);
}

// Si el usuario envía el formulario para actualizar el pedido
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['actualizar_pedido'])) {
    $clt_id = $_POST['clt_id'];
    $productos = $_POST['productos']; // Array con IDs de producto, cantidades y precios

    $resultado = actualizar_pedido($pedo_id, $clt_id, $productos, $conexion);
    if (strpos($resultado, "Pedido actualizado") !== false) {
        header('Location: pedidos.php?mensaje=Pedido actualizado con éxito');
        exit();
    } else {
        echo "<div class='alert alert-danger'>$resultado</div>";
    }
}
?>

<div id="page-content-wrapper">
    <div class="container mt-5">
        <h1 class="mb-4">Editar Pedido</h1>
        <form method="POST">
            <!-- Selección del cliente -->
            <div class="mb-3">
                <label for="clt_id" class="form-label">Cliente</label>
                <select id="clt_id" name="clt_id" class="form-select" required>
                    <option value="">Seleccione un cliente</option>
                    <?php
                    $clientes = obtener_clientes($conexion);
                    while ($cliente = mysqli_fetch_assoc($clientes)): ?>
                        <option value="<?php echo $cliente['clt_id']; ?>" <?php echo ($pedido['clt_id'] == $cliente['clt_id']) ? 'selected' : ''; ?>>
                            <?php echo $cliente['clt_nombre']; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <h3>Productos</h3>
            <div id="productos-container">
                <?php foreach ($detalles as $index => $detalle): ?>
                <div class="producto-item">
                    <div class="row">
                        <div class="col-md-4">
                            <label for="producto" class="form-label">Producto</label>
                            <select name="productos[<?php echo $index; ?>][prod_id]" class="form-select" required>
                                <option value="">Seleccione un producto</option>
                                <?php
                                $productos = obtener_productos($conexion); // Función para obtener productos
                                while ($producto = mysqli_fetch_assoc($productos)): ?>
                                    <option value="<?php echo $producto['prod_id']; ?>" <?php echo ($detalle['prod_id'] == $producto['prod_id']) ? 'selected' : ''; ?>>
                                        <?php echo $producto['prod_nombre'] . " - $" . $producto['prod_precio']; ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="cantidad" class="form-label">Cantidad</label>
                            <input type="number" name="productos[<?php echo $index; ?>][cantidad]" value="<?php echo $detalle['det_pedo_cantidad']; ?>" class="form-control" required>
                        </div>
                        <div class="col-md-3">
                            <label for="precio" class="form-label">Precio Unitario</label>
                            <input type="number" step="0.01" name="productos[<?php echo $index; ?>][precio_unit]" value="<?php echo $detalle['det_pedo_precio_unit']; ?>" class="form-control" required>
                        </div>
                        <div class="col-md-3">
                            <label for="subtotal" class="form-label">Subtotal</label>
                            <input type="number" step="0.01" name="productos[<?php echo $index; ?>][subtotal]" value="<?php echo $detalle['det_pedo_subtotal']; ?>" class="form-control" readonly>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <button type="button" id="agregar-producto" class="btn btn-secondary mt-3">Agregar Producto</button>

            <!-- Botón para actualizar el pedido -->
            <button type="submit" name="actualizar_pedido" class="btn btn-success mt-4">Actualizar Pedido</button>
        </form>
    </div>
</div>

<script>
    let productoIndex = <?php echo count($detalles); ?>;
    document.getElementById('agregar-producto').addEventListener('click', () => {
        const productosContainer = document.getElementById('productos-container');
        const nuevoProducto = document.createElement('div');
        nuevoProducto.classList.add('producto-item');
        nuevoProducto.innerHTML = `
            <div class="row mt-3">
                <div class="col-md-4">
                    <label for="producto" class="form-label">Producto</label>
                    <select name="productos[${productoIndex}][prod_id]" class="form-select" required>
                        <option value="">Seleccione un producto</option>
                        <?php
                        $productos = obtener_productos($conexion);
                        while ($producto = mysqli_fetch_assoc($productos)): ?>
                            <option value="<?php echo $producto['prod_id']; ?>">
                                <?php echo $producto['prod_nombre'] . " - $" . $producto['prod_precio']; ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="cantidad" class="form-label">Cantidad</label>
                    <input type="number" name="productos[${productoIndex}][cantidad]" class="form-control" required>
                </div>
                <div class="col-md-3">
                    <label for="precio" class="form-label">Precio Unitario</label>
                    <input type="number" step="0.01" name="productos[${productoIndex}][precio_unit]" class="form-control" required>
                </div>
                <div class="col-md-3">
                    <label for="subtotal" class="form-label">Subtotal</label>
                    <input type="number" step="0.01" name="productos[${productoIndex}][subtotal]" class="form-control" readonly>
                </div>
            </div>
        `;
        productosContainer.appendChild(nuevoProducto);
        productoIndex++;
    });
</script>

<?php
include '../../includes/footer.php';
?>
