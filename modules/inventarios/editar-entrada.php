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

// Verificar si la entrada tiene transferencia asociada
$query = "SELECT transfe_id FROM inventario_entradas WHERE inv_entra_id = ?";
$stmt = $conexion->prepare($query);
$stmt->bind_param('i', $entrada_id);
$stmt->execute();
$resultado = $stmt->get_result()->fetch_assoc();

if (!is_null($resultado['transfe_id'])) {
    die("Error: No se puede editar esta entrada porque está asociada a una transferencia.");
}

// Obtener el ID de la entrada a editar
if (isset($_GET['id'])) {
    $entrada_id = $_GET['id'];
    $entrada = obtener_entrada_por_id($entrada_id, $conexion);
    if (!$entrada) {
        echo "Entrada no encontrada.";
        exit();
    }
} else {
    echo "ID de entrada no especificado.";
    exit();
}

// Si el usuario envía el formulario para editar la entrada
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['editar_entrada'])) {
    $inv_id = $_POST['inv_id'];
    $cantidad = $_POST['cantidad'];
    $fecha = $_POST['fecha'];
    $proveedor = $_POST['proveedor'];
    $motivo = $_POST['motivo_id'];

    $resultado = editar_entrada_inventario($entrada_id, $inv_id, $cantidad, $fecha, $proveedor, $motivo, $conexion);
    if ($resultado) {
        header('Location: entradas-inventario.php?mensaje=Entrada actualizada con éxito');
        exit();
    } else {
        echo "Error al actualizar la entrada.";
    }
}
?>

<div id="page-content-wrapper">
    <div class="container mt-5">
        <h1 class="mb-4">Editar Entrada</h1>
        <form method="POST">
            <!-- Producto (No editable) -->
            <div class="mb-3">
                <label for="producto" class="form-label">Producto</label>
                <input type="text" id="producto" class="form-control" value="<?php echo htmlspecialchars($entrada['inv_nombre']); ?>" disabled>
                <input type="hidden" name="inv_id" value="<?php echo $entrada['inv_id']; ?>">
            </div>
            <div class="mb-3">
                <label for="cantidad" class="form-label">Cantidad</label>
                <input type="number" id="cantidad" name="cantidad" class="form-control" value="<?php echo $entrada['inv_entra_cantidad']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="fecha" class="form-label">Fecha</label>
                <input type="date" id="fecha" name="fecha" class="form-control" value="<?php echo $entrada['inv_entra_fecha']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="proveedor" class="form-label">Proveedor</label>
                <input type="text" id="proveedor" name="proveedor" class="form-control" value="<?php echo $entrada['inv_entra_proveedor']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="motivo_id" class="form-label">Motivo</label>
                <select id="motivo_id" name="motivo_id" class="form-select" required>
                    <option value="">Seleccione un motivo</option>
                    <?php
                    // Obtener solo los motivos con motivo_opcion = 1 (para entradas)
                    $motivos = obtener_motivos_por_opcion($conexion, 1);
                    while ($motivo = mysqli_fetch_assoc($motivos)):
                        // Verificar si este motivo es el seleccionado actualmente
                        $selected = ($motivo['motivo_id'] == $registro_actual['motivo_id']) ? 'selected' : ''; ?>
                        <option value="<?php echo $motivo['motivo_id']; ?>" <?php echo $selected; ?>>
                            <?php echo $motivo['motivo_nombre']; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <button type="submit" name="editar_entrada" class="btn btn-warning">Actualizar Entrada</button>
        </form>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>