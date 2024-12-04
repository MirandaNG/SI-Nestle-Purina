<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: ../usuarios/login.php');
    exit();
}

$usuario = $_SESSION['usuario'];

include '../../config/conexion.php';
include '../../includes/header.php';
include '../../functions/CRUD/inventarios_functions.php';

// Si el usuario envía el formulario para registrar una salida
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['registrar_salida'])) {
    $inv_id = $_POST['inv_id'];
    $cantidad = $_POST['cantidad'];
    $fecha = $_POST['fecha'];
    $destino = $_POST['destino'];
    $motivo = $_POST['motivo_id'];

    $resultado = registrar_salida_inventario($inv_id, $cantidad, $fecha, $destino, $motivo, $conexion);
    if ($resultado) {
        header('Location: salidas-inventario.php?mensaje=Salida registrada con éxito');
        exit();
    } else {
        echo "Error al registrar la salida.";
    }
}
?>

<div id="page-content-wrapper">
    <div class="container mt-5">
        <h1 class="mb-4">Registrar Nueva Salida</h1>
        <form method="POST">
            <div class="mb-3">
                <label for="inv_id" class="form-label">Producto</label>
                <select id="inv_id" name="inv_id" class="form-select" required>
                    <option value="">Seleccione un producto</option>
                    <?php
                    $productos = obtener_inventario($conexion); // Función para obtener productos disponibles
                    while ($producto = mysqli_fetch_assoc($productos)): ?>
                        <option value="<?php echo $producto['inv_id']; ?>"><?php echo $producto['inv_nombre']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="cantidad" class="form-label">Cantidad</label>
                <input type="number" id="cantidad" name="cantidad" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="fecha" class="form-label">Fecha</label>
                <input type="date" id="fecha" name="fecha" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="destino" class="form-label">Destino</label>
                <input type="text" id="destino" name="destino" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="motivo_id" class="form-label">Motivo</label>
                <select id="motivo_id" name="motivo_id" class="form-select" required>
                    <option value="">Seleccione un motivo</option>
                    <?php
                    // Obtener solo los motivos con motivo_opcion = 2 (para salidas)
                    $motivos = obtener_motivos_por_opcion($conexion, 2);
                    while ($motivo = mysqli_fetch_assoc($motivos)): ?>
                        <option value="<?php echo $motivo['motivo_id']; ?>">
                            <?php echo $motivo['motivo_nombre']; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <button type="submit" name="registrar_salida" class="btn btn-success">Registrar Salida</button>
        </form>
    </div>
</div>

<?php
include '../../includes/footer.php';
?>
