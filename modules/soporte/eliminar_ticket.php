<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: ../usuarios/login.php');
    exit();
}

$usuario = $_SESSION['usuario'];

include '../../config/conexion.php';
include '../../includes/header.php';
include '../../functions/CRUD/tickets_functions.php';

// Verificar si el ID está presente
if (isset($_GET['id'])) {
    $ticket_id = $_GET['id'];

    // Eliminar el ticket
    $resultado = eliminar_ticket($ticket_id, $conexion);
    if ($resultado) {
        header('Location: soporte.php?mensaje=Ticket eliminado con éxito');
        exit();
    } else {
        echo "<div class='alert alert-danger'>Error al eliminar el ticket</div>";
    }
}

?>

<div id="page-content-wrapper">
    <div class="container mt-5">
        <h1 class="mb-4">Eliminar Ticket</h1>
        <p>¿Está seguro de eliminar este ticket?</p>
        <form method="POST">
            <button type="submit" name="eliminar_ticket" class="btn btn-danger">Eliminar Ticket</button>
            <a href="soporte.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</div>

<?php
include '../../includes/footer.php';
?>