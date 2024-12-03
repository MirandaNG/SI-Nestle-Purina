<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: ../usuarios/login.php');
    exit();
}

include '../../config/conexion.php';
include '../../includes/header.php';
include '../../functions/CRUD/tickets_functions.php';

// Obtener el ID del ticket desde la URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<div class='alert alert-danger'>ID de ticket inválido.</div>";
    exit();
}

$ticket_id = $_GET['id'];
$ticket = obtener_ticket_detallado($ticket_id, $conexion);

if (!$ticket) {
    echo "<div class='alert alert-danger'>Ticket no encontrado.</div>";
    exit();
}
?>

<div id="page-content-wrapper">
    <div class="container mt-5">
        <h1 class="mb-4">Detalles del Ticket</h1>
        <p><strong>Cliente:</strong> <?php echo $ticket['clt_nombre']; ?></p>
        <p><strong>Descripción:</strong> <?php echo $ticket['tick_soport_descripcion']; ?></p>
        <p><strong>Fecha:</strong> <?php echo $ticket['tick_soport_fecha_creacion']; ?></p>
        <p><strong>Estado:</strong> <?php echo $ticket['tick_soport_estado']; ?></p>

        <a href="soporte.php" class="btn btn-primary">Volver</a>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>