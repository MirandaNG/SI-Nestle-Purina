<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: ../usuarios/login.php');
    exit();
}

include '../../config/conexion.php';
include '../../includes/header.php';
include '../../functions/CRUD/tickets_functions.php';

// Obtener ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['mensaje_error'] = "Ticket no encontrado.";
    header('Location: soporte.php');
    exit();
}

$ticket_id = $_GET['id'];

// Obtener datos del producto
$ticket = obtener_ticket_por_id($ticket_id, $conexion);

if (!$ticket) {
    $_SESSION['mensaje_error'] = "Ticket no encontrado.";
    header('Location: soporte.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $clt_id = $_POST['clt_id'];
    $descripcion = $_POST['descripcion'];
    $estado = $_POST['estado'];

    $resultado = actualizar_ticket($ticket_id, $descripcion, $estado, $conexion);
    if ($resultado) {
        header('Location: soporte.php?mensaje=Ticket actualizado con éxito');
        exit();
    } else {
        echo "<div class='alert alert-danger'>Hubo un error al actualizar el ticket.</div>";
    }
}
?>

<div class="container mt-5">
    <h1 class="mb-4">Editar Ticket de Soporte</h1>
    <form method="POST">
        <div class="mb-3">
            <label for="clt_id" class="form-label">Cliente</label>
            <input type="text" id="clt_id" class="form-control" value="<?php echo htmlspecialchars($ticket['clt_nombre']); ?>" disabled>
            <input type="hidden" name="clt_id" value="<?php echo $ticket['clt_id']; ?>">
        </div>

        <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción</label>
            <textarea class="form-control" id="descripcion" name="descripcion" rows="3"><?php echo $ticket['tick_soport_descripcion']; ?></textarea>
        </div>
        <div class="mb-3">
            <label for="estado" class="form-label">Estado</label>
            <select id="estado" name="estado" class="form-select" required>
                <option value="abierto" <?php echo ($ticket['estado'] == 'Abierto') ? 'selected' : ''; ?>>Abierto</option>
                <option value="cerrado" <?php echo ($ticket['estado'] == 'Cerrado') ? 'selected' : ''; ?>>Cerrado</option>

            </select>
        </div>
        <button type="submit" class="btn btn-success">Guardar Cambios</button>
    </form>
</div>

<?php include '../../includes/footer.php'; ?>