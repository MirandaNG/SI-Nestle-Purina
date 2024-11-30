<?php
    session_start();
    if (!isset($_SESSION['usuario'])) {
        header('Location: ../usuarios/login.php');
        exit();
    }

    include '../../config/conexion.php';
    include '../../includes/header.php';
    include '../../functions/CRUD/inventarios_functions.php';

    // Obtener ID del producto
    if (!isset($_GET['id']) || empty($_GET['id'])) {
        $_SESSION['mensaje_error'] = "Producto no encontrado.";
        header('Location: stock-inventario.php');
        exit();
    }

    $producto_id = $_GET['id'];

    // Obtener datos del producto
    $producto = obtener_producto_por_id($producto_id, $conexion);

    if (!$producto) {
        $_SESSION['mensaje_error'] = "Producto no encontrado.";
        header('Location: stock-inventario.php');
        exit();
    }

    // Procesar formulario al enviar
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $inv_nombre = $_POST['inv_nombre'];
        $inv_descripcion = $_POST['inv_descripcion'];
        $inv_tipo = $_POST['inv_tipo'];
        $inv_cantidad_actual = $_POST['inv_cantidad_actual'];
        $inv_ubicacion = $_POST['inv_ubicacion'];
        $inv_precio_unitario = $_POST['inv_precio_unitario'];

        $resultado = editar_producto_inventario(
            $producto_id, 
            $inv_nombre, 
            $inv_descripcion, 
            $inv_tipo, 
            $inv_cantidad_actual, 
            $inv_ubicacion, 
            $inv_precio_unitario, 
            $conexion
        );

        if ($resultado) {
            $_SESSION['mensaje_exito'] = "Producto actualizado con éxito.";
            header('Location: stock-inventario.php');
            exit();
        } else {
            $error = "Error al actualizar el producto. Por favor, inténtalo de nuevo.";
        }
    }
?>

<div id="page-content-wrapper">
    <div class="container mt-5">
        <h1 class="mb-4">Editar Producto</h1>

        <!-- Mostrar mensajes de error, si los hay -->
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <!-- Formulario de Edición -->
        <form method="POST" action="">
            <div class="mb-3">
                <label for="inv_nombre" class="form-label">Nombre del Producto *</label>
                <input type="text" class="form-control" id="inv_nombre" name="inv_nombre" value="<?php echo htmlspecialchars($producto['inv_nombre']); ?>" required>
            </div>

            <div class="mb-3">
                <label for="inv_descripcion" class="form-label">Descripción</label>
                <textarea class="form-control" id="inv_descripcion" name="inv_descripcion"><?php echo htmlspecialchars($producto['inv_descripcion']); ?></textarea>
            </div>

            <div class="mb-3">
                <label for="inv_tipo" class="form-label">Tipo de Producto</label>
                <select class="form-control" id="inv_tipo" name="inv_tipo">
                    <option value="Materia Prima" <?php echo ($producto['inv_tipo'] == 'Materia Prima') ? 'selected' : ''; ?>>Materia Prima</option>
                    <option value="Producto Terminado" <?php echo ($producto['inv_tipo'] == 'Producto Terminado') ? 'selected' : ''; ?>>Producto Terminado</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="inv_cantidad_actual" class="form-label">Cantidad Actual *</label>
                <input type="number" class="form-control" id="inv_cantidad_actual" name="inv_cantidad_actual" value="<?php echo $producto['inv_cantidad_actual']; ?>" required>
            </div>

            <div class="mb-3">
                <label for="inv_ubicacion" class="form-label">Ubicación</label>
                <input type="text" class="form-control" id="inv_ubicacion" name="inv_ubicacion" value="<?php echo htmlspecialchars($producto['inv_ubicacion']); ?>">
            </div>

            <div class="mb-3">
                <label for="inv_precio_unitario" class="form-label">Precio Unitario *</label>
                <input type="number" step="0.01" class="form-control" id="inv_precio_unitario" name="inv_precio_unitario" value="<?php echo $producto['inv_precio_unitario']; ?>" required>
            </div>

            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
            <a href="stock-inventario.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</div>

<?php
    include '../../includes/footer.php';
?>
