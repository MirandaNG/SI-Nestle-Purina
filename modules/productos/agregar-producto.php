<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: ../usuarios/login.php');
    exit();
}

include '../../config/conexion.php';
include '../../includes/header.php';
include '../../functions/CRUD/productos_functions.php';

// Si se envió el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['prod_nombre'];
    $descripcion = $_POST['prod_descripcion'];
    $precio = $_POST['prod_precio'];
    $especie_id = $_POST['esp_id'];
    $tipo_comida_id = $_POST['tip_com_id'];
    $etapa_vida_id = $_POST['etpa_vida_id'];
    $marca = $_POST['prod_marca'];

    if (agregar_producto($conexion, $nombre, $descripcion, $precio, $especie_id, $tipo_comida_id, $etapa_vida_id, $marca)) {
        header('Location: productos.php?mensaje=Producto agregado con éxito');
        exit();
    } else {
        echo "<div class='alert alert-danger'>Error al agregar el producto.</div>";
    }
}
?>

<div id="page-content-wrapper">
    <div class="container mt-5">
        <h1 class="mb-4">Agregar Producto</h1>
        <form method="POST">
            <div class="mb-3">
                <label for="prod_nombre" class="form-label">Nombre</label>
                <input type="text" class="form-control" id="prod_nombre" name="prod_nombre" required>
            </div>
            <div class="mb-3">
                <label for="prod_descripcion" class="form-label">Descripción</label>
                <textarea class="form-control" id="prod_descripcion" name="prod_descripcion" rows="3"></textarea>
            </div>
            <div class="mb-3">
                <label for="prod_precio" class="form-label">Precio</label>
                <input type="number" step="0.01" class="form-control" id="prod_precio" name="prod_precio" required>
            </div>
            <div class="mb-3">
                <label for="esp_id" class="form-label">Especie</label>
                <select class="form-select" id="esp_id" name="esp_id" required>
                    <option value="">Seleccione una especie</option>
                    <?php
                    $especies = obtener_especies($conexion);
                    while ($especie = mysqli_fetch_assoc($especies)): ?>
                        <option value="<?php echo $especie['esp_id']; ?>">
                            <?php echo $especie['esp_nombre']; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="tip_com_id" class="form-label">Tipo de Comida</label>
                <select class="form-select" id="tip_com_id" name="tip_com_id" required>
                    <option value="">Seleccione un tipo de comida</option>
                    <?php
                    $tipos_comida = obtener_tipos_comida($conexion);
                    while ($tipo = mysqli_fetch_assoc($tipos_comida)): ?>
                        <option value="<?php echo $tipo['tip_com_id']; ?>">
                            <?php echo $tipo['tip_com_nombre']; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="etpa_vida_id" class="form-label">Etapa de Vida</label>
                <select class="form-select" id="etpa_vida_id" name="etpa_vida_id" required>
                    <option value="">Seleccione una etapa de vida</option>
                    <?php
                    $etapas_vida = obtener_etapas_vida($conexion);
                    while ($etapa = mysqli_fetch_assoc($etapas_vida)): ?>
                        <option value="<?php echo $etapa['etpa_vida_id']; ?>">
                            <?php echo $etapa['etpa_vida_nombre']; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="prod_marca" class="form-label">Marca</label>
                <input type="text" class="form-control" id="prod_marca" name="prod_marca" required>
            </div>
            <button type="submit" class="btn btn-success">Agregar Producto</button>
        </form>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>