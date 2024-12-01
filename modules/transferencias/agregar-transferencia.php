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
include '../../functions/CRUD/ubicaciones_functions.php';

// Si el usuario envía el formulario para registrar una transferencia
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['registrar_transferencia'])) {
    $inv_id = $_POST['inv_id'];
    $origen_id = $_POST['origen_id'];
    $destino_id = $_POST['destino_id'];
    $cantidad = $_POST['cantidad'];
    $fecha = $_POST['fecha'];

    // Validación de campos
    if (empty($inv_id) || empty($origen_id) || empty($destino_id) || empty($cantidad) || empty($fecha)) {
        echo "Error: Todos los campos son obligatorios.";
        return;
    }

    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha)) {
        echo "Error: La fecha debe estar en formato YYYY-MM-DD.";
        return;
    }

    if ($cantidad <= 0) {
        echo "Error: La cantidad debe ser mayor a 0.";
        return;
    }

    // Registrar transferencia
    $resultado = registrar_transferencia($inv_id, $origen_id, $destino_id, $cantidad, $fecha, $conexion);
    if ($resultado) {
        header('Location: transferencias.php?mensaje=Transferencia registrada con éxito');
        exit();
    } else {
        echo "Error al registrar la transferencia.";
    }
}
?>

<div id="page-content-wrapper">
    <div class="container mt-5">
        <h1 class="mb-4">Registrar Transferencia</h1>
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
                <label for="origen_id" class="form-label">Origen</label>
                <select id="origen_id" name="origen_id" class="form-select" required>
                    <option value="">Seleccione una ubicación</option>
                    <?php
                    $ubicaciones = obtener_ubicaciones($conexion); // Función para obtener ubicaciones disponibles
                    while ($ubicacion = mysqli_fetch_assoc($ubicaciones)): ?>
                        <option value="<?php echo $ubicacion['ubica_id']; ?>"><?php echo $ubicacion['ubica_nombre']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="destino_id" class="form-label">Destino</label>
                <select id="destino_id" name="destino_id" class="form-select" required>
                    <option value="">Seleccione una ubicación</option>
                    <?php
                    $ubicaciones = obtener_ubicaciones($conexion); // Función para obtener ubicaciones disponibles
                    while ($ubicacion = mysqli_fetch_assoc($ubicaciones)): ?>
                        <option value="<?php echo $ubicacion['ubica_id']; ?>"><?php echo $ubicacion['ubica_nombre']; ?></option>
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
            <button type="submit" name="registrar_transferencia" class="btn btn-success">Registrar Transferencia</button>
        </form>
    </div>
</div>

<?php
include '../../includes/footer.php';
?>
