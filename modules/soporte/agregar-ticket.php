<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: ../usuarios/login.php');
    exit();
}

include '../../config/conexion.php';
include '../../includes/header.php';
include '../../functions/CRUD/tickets_functions.php';
include '../../functions/CRUD/clientes_functions.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['registrar_ticket'])) {
    $clt_id = $_POST['clt_id'];
    $descripcion = $_POST['descripcion'];
    $estado = $_POST['estado'];

    $resultado = crear_ticket_soporte($clt_id, $descripcion, $estado, $conexion);
    if ($resultado) {
        header('Location: soporte.php?mensaje=Ticket registrado con éxito');
        exit();
    } else {
        echo "<div class='alert alert-danger'>Hubo un error al registrar el ticket.</div>";
    }
}
?>

<div id="page-content-wrapper">
    <div class="container mt-5">
        <h1 class="mb-4">Registrar Ticket de Soporte</h1>
        <form method="POST">
            <div class="mb-3">
                <label for="clt_id" class="form-label">Cliente</label>
                <select id="clt_id" name="clt_id" class="form-select" required>
                    <option value="">Seleccione un cliente</option>
                    <?php
                    $clientes = obtener_clientes($conexion);
                    while ($cliente = mysqli_fetch_assoc($clientes)): ?>
                        <option value="<?php echo $cliente['clt_id']; ?>">
                            <?php echo $cliente['clt_nombre']; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="descripcion" class="form-label">Descripción</label>
                <textarea id="descripcion" name="descripcion" class="form-control" rows="3" required></textarea>
            </div>

            <div class="mb-3">
                <label for="estado" class="form-label">Estado</label>
                <select id="estado" name="estado" class="form-select" required>
                    <option value="abierto">Abierto</option>
                    <option value="cerrado">Cerrado</option>
                </select>
            </div>

            <button type="submit" name="registrar_ticket" class="btn btn-success mt-4">Registrar Ticket</button>
        </form>
    </div>
</div>

<?php
include '../../includes/footer.php';
?>
