<?php
include '../../config/conexion.php';
include '../../includes/header.php';
include '../../functions/clientes_functions.php';

// Verifica si se ha pasado el ID del cliente
if (isset($_GET['id'])) {
    $clt_id = $_GET['id'];
    $cliente = obtener_cliente($clt_id, $conexion);
    $pedidos = obtener_pedidos_cliente($clt_id, $conexion);
    $facturas = obtener_facturas_cliente($clt_id, $conexion);
    $tickets = obtener_tickets_cliente($clt_id, $conexion);
} else {
    die('Cliente no encontrado');
}
?>

<div id="page-content-wrapper">
    <div class="container mt-5">
        <h1 class="mb-4">Detalles del Cliente: <?php echo $cliente['clt_nombre']; ?></h1>

        <!-- Información básica del cliente -->
        <p><strong>Nombre:</strong> <?php echo $cliente['clt_nombre']; ?></p>
        <p><strong>Dirección:</strong> <?php echo $cliente['clt_direccion']; ?></p>
        <p><strong>Teléfono:</strong> <?php echo $cliente['clt_telefono']; ?></p>
        <p><strong>Email:</strong> <?php echo $cliente['clt_email']; ?></p>
        <p><strong>Tipo:</strong> <?php echo $cliente['clt_tipo']; ?></p>

        <!-- Contenedor de las pestañas -->
        <div class="tabs">
            <!-- Pestañas -->
            <ul class="tab-titles">
                <li class="tab-title active" data-tab="compras">Historial de Compras</li>
                <li class="tab-title" data-tab="facturas">Facturas</li>
                <li class="tab-title" data-tab="soporte">Tickets de Soporte</li>
            </ul>

            <!-- Contenido de las pestañas -->
            <div class="tab-content active" id="compras">
                <h2>Historial de Compras</h2>
                <table>
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
                                <td><?php echo $pedido['pedo_fecha']; ?></td>
                                <td><?php echo $pedido['pedo_total']; ?></td>
                                <td><?php echo $pedido['pedo_estado']; ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

            <div class="tab-content" id="facturas">
                <h2>Facturas</h2>
                <table>
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
                                <td><?php echo $factura['fact_fecha']; ?></td>
                                <td><?php echo $factura['fact_total']; ?></td>
                                <td><?php echo $factura['fact_estado']; ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

            <div class="tab-content" id="soporte">
                <h2>Tickets de Soporte</h2>
                <table>
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
                                <td><?php echo $ticket['tick_soport_descripcion']; ?></td>
                                <td><?php echo $ticket['tick_soport_fecha_creacion']; ?></td>
                                <td><?php echo $ticket['tick_soport_estado']; ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <a href="clientes.php">Volver</a>
    </div>
</div>

<?php
    include '../../includes/footer.php';
?>
