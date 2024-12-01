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

// Obtener el ID de la transferencia a editar
if (isset($_GET['id'])) {
    $transferencia_id = $_GET['id'];
    $transferencia = obtener_transferencia_por_id($transferencia_id, $conexion);
    if (!$transferencia) {
        echo "Transferencia no encontrada.";
        exit();
    }
} else {
    echo "ID de Transferencia no especificado.";
    exit();
}

// Si el usuario envía el formulario para editar
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['editar_transferencia'])) {
    $inv_id = $_POST['inv_id'];
    $origen_id = $_POST['origen_id'];
    $destino_id = $_POST['destino_id'];
    $cantidad = $_POST['cantidad'];
    $fecha = $_POST['fecha'];

    $resultado = editar_transferencia($transferencia_id, $inv_id, $origen_id, $destino_id, $cantidad, $fecha, $conexion);
    if ($resultado) {
        header('Location: transferencias.php?mensaje=Transferencia actualizada con éxito');
        exit();
    } else {
        echo "Error al actualizar la transferencia.";
    }
}
?>

<div id="page-content-wrapper">
    <div class="container mt-5">
        <h1 class="mb-4">Editar Transferencia</h1>
        <form method="POST">
            <!-- Producto (No editable) -->
            <div class="mb-3">
                <label for="producto" class="form-label">Producto</label>
                <input type="text" id="producto" class="form-control" value="<?php echo htmlspecialchars($transferencia['inv_nombre']); ?>" disabled>
                <input type="hidden" name="inv_id" value="<?php echo $transferencia['inv_id']; ?>">
            </div>

            <!-- Origen -->
            <div class="mb-3">
                <label for="origen_id" class="form-label">Origen</label>
                <select id="origen_id" name="origen_id" class="form-select" required>
                    <option value="">Seleccione una ubicación</option>
                    <?php
                    $ubicaciones = obtener_ubicaciones($conexion); // Función para obtener ubicaciones disponibles
                    while ($ubicacion = mysqli_fetch_assoc($ubicaciones)):
                        // Asignar 'selected' si la ubicación es la misma que la actual
                        $selected = ($ubicacion['ubica_id'] == $transferencia['origen_id']) ? 'selected' : '';
                    ?>
                        <option value="<?php echo $ubicacion['ubica_id']; ?>" <?php echo $selected; ?>>
                            <?php echo htmlspecialchars($ubicacion['ubica_nombre']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <!-- Destino -->
            <div class="mb-3">
                <label for="destino_id" class="form-label">Destino</label>
                <select id="destino_id" name="destino_id" class="form-select" required>
                    <option value="">Seleccione una ubicación</option>
                    <?php
                    // Obtener ubicaciones para el destino
                    $ubicaciones = obtener_ubicaciones($conexion); // Función para obtener ubicaciones disponibles
                    while ($ubicacion = mysqli_fetch_assoc($ubicaciones)):
                        // Asignar 'selected' si la ubicación es la misma que la actual
                        $selected = ($ubicacion['ubica_id'] == $transferencia['destino_id']) ? 'selected' : '';
                    ?>
                        <option value="<?php echo $ubicacion['ubica_id']; ?>" <?php echo $selected; ?>>
                            <?php echo htmlspecialchars($ubicacion['ubica_nombre']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <!-- Cantidad -->
            <div class="mb-3">
                <label for="cantidad" class="form-label">Cantidad</label>
                <input type="number" id="cantidad" name="cantidad" class="form-control" value="<?php echo $transferencia['transfe_cantidad']; ?>" required>
            </div>

            <!-- Fecha -->
            <div class="mb-3">
                <label for="fecha" class="form-label">Fecha</label>
                <input type="date" id="fecha" name="fecha" class="form-control" value="<?php echo $transferencia['transfe_fecha']; ?>" required>
            </div>

            <button type="submit" name="editar_transferencia" class="btn btn-warning">Actualizar Transferencia</button>
        </form>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>
