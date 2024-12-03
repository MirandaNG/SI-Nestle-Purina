<?php
    session_start();
    if (!isset($_SESSION['usuario'])) {
        header('Location: ../usuarios/login.php');
        exit();
    }

    include '../../config/conexion.php';
    include '../../includes/header.php';
    include '../../functions/CRUD/productos_functions.php';

// Si el usuario envía el formulario para registrar un producto
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['registrar_producto'])) {
    $nombre = $_POST['prod_nombre'];
    $descripcion = $_POST['prod_descripcion'];
    $precio = $_POST['prod_precio'];
    $especie = $_POST['esp_id'];
    $tipo_comida = $_POST['tip_com_id'];
    $etapa_vida = $_POST['etpa_vida_id'];
    $marca = $_POST['prod_marca'];

    $resultado = registrar_producto($nombre, $descripcion, $precio, $especie, $tipo_comida, $etapa_vida, $marca, $conexion);
    if ($resultado) {
        header('Location: productos.php?mensaje=Producto registrado con éxito');
        exit();
    } else {
        echo "Error al registrar producto.";
    }
}
?>

<div id="page-content-wrapper">
    <div class="container mt-5">
        <h1 class="mb-4">Registrar Producto</h1>
        <!-- Formulario de Producto -->
        <form method="POST" action="">
            <div class="mb-3">
                <label for="prod_nombre" class="form-label">Nombre del Producto</label>
                <input type="text" class="form-control" id="prod_nombre" name="prod_nombre" required>
            </div>
            <div class="mb-3">
                <label for="prod_descripcion" class="form-label">Descripción</label>
                <textarea class="form-control" id="prod_descripcion" name="prod_descripcion"></textarea>
            </div>
            <div class="mb-3">
                <label for="prod_precio" class="form-label">Precio</label>
                <input type="number" step="0.01" class="form-control" id="prod_precio" name="prod_precio" required>
            </div>
            <div class="mb-3">
                <label for="esp_id" class="form-label">Especie</label>
                <select id="esp_id" name="esp_id" class="form-select" required>
                    <option value="">Seleccione una especie</option>
                    <?php
                    $especies = obtener_especies($conexion); // Función para obtener las especies
                    while ($especie = mysqli_fetch_assoc($especies)): ?>
                        <option value="<?php echo $especie['esp_id']; ?>"><?php echo $especie['esp_nombre']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="etpa_vida_id" class="form-label">Etapa de Vida</label>
                <select id="etpa_vida_id" name="etpa_vida_id" class="form-select" required>
                    <option value="">Seleccione una etapa</option>
                    <?php
                    $etapas = obtener_etapas($conexion); // Función para obtener las etapas
                    while ($etapa = mysqli_fetch_assoc($etapas)): ?>
                        <option value="<?php echo $etapa['etpa_vida_id']; ?>"><?php echo $etapa['etpa_vida_nombre']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="tip_com_id" class="form-label">Tipo de Comida</label>
                <select id="tip_com_id" name="tip_com_id" class="form-select" required>
                    <option value="">Seleccione un tipo de comida</option>
                    <?php
                    $tipos = obtener_tipos($conexion); // Función para obtener los tipos de comida
                    while ($tipo = mysqli_fetch_assoc($tipos)): ?>
                        <option value="<?php echo $tipo['tip_com_id']; ?>"><?php echo $tipo['tip_com_nombre']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="prod_marca" class="form-label">Marca</label>
                <input type="text" class="form-control" id="prod_marca" name="prod_marca" required>
            </div>

            <button type="submit" id="registrar_producto" class="btn btn-primary">Agregar Producto</button>
            <a href="productos.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>