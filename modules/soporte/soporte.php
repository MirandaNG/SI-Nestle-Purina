<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: ../usuarios/login.php');
    exit();
}

include '../../config/conexion.php';
include '../../includes/header.php';
include '../../functions/CRUD/tickets_functions.php';

// Obtener tickets de soporte de todos los clientes
$tickets = obtener_todos_tickets($conexion);
?>

<div id="page-content-wrapper">
    <div class="container mt-5">
        <h1 class="mb-4">Tickets de Soporte</h1>

        <div class="mb-3">
            <a href="agregar-ticket.php" class="btn btn-success mb-3">Agregar Ticket</a>
        </div>

        <!-- Tabla de Tickets -->
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Cliente</th>
                    <th>Fecha de Creación</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($ticket = $tickets->fetch_assoc()) : ?>
                    <tr>
                        <td><?php echo $ticket['clt_nombre']; ?></td>
                        <td><?php echo $ticket['tick_soport_fecha_creacion']; ?></td>
                        <td><?php echo $ticket['tick_soport_estado']; ?></td>
                        <td>
                            <a href="detalle_ticket.php?id=<?php echo $ticket['tick_soport_id']; ?>" class="btn btn-primary">Ver</a>
                            <a href="editar_ticket.php?id=<?php echo $ticket['tick_soport_id']; ?>" class="btn btn-warning">Actualizar</a>
                            <a href="eliminar_ticket.php?id=<?php echo $ticket['tick_soport_id']; ?>" class="btn btn-danger" onclick="return confirm('¿Estás seguro de eliminar este ticket?')">Eliminar</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>