<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: ../usuarios/login.php');
    exit();
}

$usuario = $_SESSION['usuario'];

include '../../config/conexion.php';
include '../../includes/header.php';
include '../../functions/CRUD/clientes_functions.php'; // Funciones relacionadas con clientes
include '../../functions/CRUD/pedidos_functions.php'; // Funciones relacionadas con pedidos
include '../../functions/CRUD/facturas_functions.php'; // Funciones relacionadas con facturas
include '../../functions/CRUD/tickets_functions.php'; // Funciones relacionadas con tickets

// Verificar que se haya pasado el ID del cliente
if (isset($_GET['id'])) {
    $clt_id = $_GET['id'];
    $cliente = obtener_cliente_por_id($clt_id, $conexion); // Obtener datos básicos del cliente

    if (!$cliente) {
        echo "<div class='alert alert-danger'>Cliente no encontrado.</div>";
        exit();
    }

    // Obtener información relacionada con el cliente
    $pedidos = obtener_pedidos_cliente($clt_id, $conexion); // Historial de compras
    $facturas = obtener_facturas_cliente($clt_id, $conexion); // Facturas
    $tickets = obtener_tickets_cliente($clt_id, $conexion); // Tickets de soporte
} else {
    echo "<div class='alert alert-danger'>ID de cliente no especificado.</div>";
    exit();
}
?>

<div id="page-content-wrapper">
    <div class="container mt-5">
        <h1>Detalles del Cliente: <?php echo htmlspecialchars($cliente['clt_nombre']); ?></h1>

        <!-- Información básica del cliente -->
        <div class="card mb-4">
            <div class="card-body">
                <p><strong>Nombre:</strong> <?php echo htmlspecialchars($cliente['clt_nombre']); ?></p>
                <p><strong>Dirección:</strong> <?php echo htmlspecialchars($cliente['clt_direccion']); ?></p>
                <p><strong>Teléfono:</strong> <?php echo htmlspecialchars($cliente['clt_telefono']); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($cliente['clt_email']); ?></p>
                <p><strong>Tipo:</strong> <?php echo htmlspecialchars($cliente['clt_tipo']); ?></p>
            </div>
        </div>

        <!-- Sistema de pestañas -->
        <ul class="nav nav-tabs" id="tabCliente" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="tab-compras" data-bs-toggle="tab" data-bs-target="#compras" type="button" role="tab" aria-controls="compras" aria-selected="true">Historial de Compras</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="tab-facturas" data-bs-toggle="tab" data-bs-target="#facturas" type="button" role="tab" aria-controls="facturas" aria-selected="false">Facturas</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="tab-soporte" data-bs-toggle="tab" data-bs-target="#soporte" type="button" role="tab" aria-controls="soporte" aria-selected="false">Tickets de Soporte</button>
            </li>
        </ul>

        <div class="tab-content" id="tabContentCliente">
            <!-- Historial de compras -->
            <div class="tab-pane fade show active" id="compras" role="tabpanel" aria-labelledby="tab-compras">
                <h2 class="mt-3">Historial de Compras</h2>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Total</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($pedido = $pedidos->fetch_assoc()) : ?>
                            <tr>
                                <td><?php echo htmlspecialchars($pedido['pedo_fecha']); ?></td>
                                <td><?php echo htmlspecialchars($pedido['pedo_total']); ?></td>
                                <td><?php echo htmlspecialchars($pedido['pedo_estado']); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

            <!-- Facturas -->
            <div class="tab-pane fade" id="facturas" role="tabpanel" aria-labelledby="tab-facturas">
                <h2 class="mt-3">Facturas</h2>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Total</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($factura = $facturas->fetch_assoc()) : ?>
                            <tr>
                                <td><?php echo htmlspecialchars($factura['fact_fecha']); ?></td>
                                <td><?php echo htmlspecialchars($factura['fact_total']); ?></td>
                                <td><?php echo htmlspecialchars($factura['fact_estado']); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

            <!-- Tickets de soporte -->
            <div class="tab-pane fade" id="soporte" role="tabpanel" aria-labelledby="tab-soporte">
                <h2 class="mt-3">Tickets de Soporte</h2>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Descripción</th>
                            <th>Fecha de Creación</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($ticket = $tickets->fetch_assoc()) : ?>
                            <tr>
                                <td><?php echo htmlspecialchars($ticket['tick_soport_descripcion']); ?></td>
                                <td><?php echo htmlspecialchars($ticket['tick_soport_fecha_creacion']); ?></td>
                                <td><?php echo htmlspecialchars($ticket['tick_soport_estado']); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Botón de regreso -->
        <div class="mt-3">
            <a href="clientes.php" class="btn btn-secondary">Regresar</a>
        </div>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>
