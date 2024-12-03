<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: ../usuarios/login.php');
    exit();
}

include '../../config/conexion.php';
include '../../includes/header.php';
include '../../functions/CRUD/tickets_functions.php';

// Obtener todos los tickets de soporte
$tickets = obtener_todos_tickets($conexion);
?>

<div class="container mt-5">
    <h1>Gestión de Tickets de Soporte</h1>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Cliente</th>
                <th>Descripción</th>
                <th>Fecha de Creación</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($ticket = $tickets->fetch_assoc()) : ?>
                <tr>
                    <td><?php echo $ticket['tick_soport_id']; ?></td>
                    <td><?php echo $ticket['clt_nombre']; ?></td>
                    <td><?php echo $ticket['tick_soport_descripcion']; ?></td>
                    <td><?php echo $ticket['tick_soport_fecha_creacion']; ?></td>
                    <td><?php echo $ticket['tick_soport_estado']; ?></td>
                    <td>
                        <a href="detalle_ticket.php?id=<?php echo $ticket['tick_soport_id']; ?>" class="btn btn-info">Ver Detalle</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php include '../../includes/footer.php'; ?>
