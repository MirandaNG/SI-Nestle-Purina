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
$producto = obtener_producto_por_id($conexion, $id);

if (!$producto) {
    echo "<div class='alert alert-danger'>Producto no encontrado.</div>";
    exit();
}

// Si se envía el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['prod_nombre'];
    $descripcion = $_POST['prod_descripcion'];
    $precio = $_POST['prod_precio'];
    $especie_id = $_POST['esp_id'];
    $tipo_comida_id = $_POST['tip_com_id'];
    $etapa_vida_id = $_POST['etpa_vida_id'];
    $marca = $_POST['prod_marca'];

    if (actualizar_producto($conexion, $id, $nombre, $descripcion, $precio, $especie_id, $tipo_comida_id, $etapa_vida_id, $marca)) {
        header('Location: productos.php?mensaje=Producto actualizado con éxito');
        exit();
    } else {
        echo "<div class='alert alert-danger'>Error al actualizar el producto.</div>";
    }
}
?>

<div class="container mt-5">
    <h1 class="mb-4">Editar Producto</h1>
    <form method="POST">
        <div class="mb-3">
            <label for="prod_nombre" class="form-label">Nombre</label>
            <input type="text" class="form-control" id="prod_nombre" name="prod_nombre" value="<?php echo $producto['prod_nombre']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="prod_descripcion" class="form-label">Descripción</label>
            <textarea class="form-control" id="prod_descripcion" name="prod_descripcion" rows="3"><?php echo $producto['prod_descripcion']; ?></textarea>
        </div>
        <div class="mb-3">
            <label for="prod_precio" class="form-label">Precio</label>
            <input type="number" step="0.01" class="form-control" id="prod_precio" name="prod_precio" value="<?php echo $producto['prod_precio']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="esp_id" class="form-label">Especie</label>
            <select class="form-select" id="esp_id" name="esp_id" required>
                <?php
                $especies = obtener_especies($conexion);
                while ($especie = mysqli_fetch_assoc($especies)): ?>
                    <option value="<?php echo $especie['esp_id']; ?>" <?php echo $producto['esp_id'] == $especie['esp_id'] ? 'selected' : ''; ?>>
                        <?php echo $especie['esp_nombre']; ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="tip_com_id" class="form-label">Tipo de Comida</label>
            <select class="form-select" id="tip_com_id" name="tip_com_id" required>
                <?php
                $tipos_comida = obtener_tipos_comida($conexion);
                while ($tipo = mysqli_fetch_assoc($tipos_comida)): ?>
                    <option value="<?php echo $tipo['tip_com_id']; ?>" <?php echo $producto['tip_com_id'] == $tipo['tip_com_id'] ? 'selected' : ''; ?>>
                        <?php echo $tipo['tip_com_nombre']; ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="etpa_vida_id" class="form-label">Etapa de Vida</label>
            <select class="form-select" id="etpa_vida_id" name="etpa_vida_id" required>
                <?php
                $etapas_vida = obtener_etapas_vida($conexion);
                while ($etapa = mysqli_fetch_assoc($etapas_vida)): ?>
                    <option value="<?php echo $etapa['etpa_vida_id']; ?>" <?php echo $producto['etpa_vida_id'] == $etapa['etpa_vida_id'] ? 'selected' : ''; ?>>
                        <?php echo $etapa['etpa_vida_nombre']; ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="prod_marca" class="form-label">Marca</label>
            <input type="text" class="form-control" id="prod_marca" name="prod_marca" value="<?php echo $producto['prod_marca']; ?>" required>
        </div>
        <button type="submit" class="btn btn-success">Guardar Cambios</button>
    </form>
</div>

<?php include '../../includes/footer.php'; ?>