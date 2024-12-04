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
include '../../functions/CRUD/facturas_functions.php';
include '../../functions/CRUD/clientes_functions.php';
include '../../functions/CRUD/proveedores_functions.php';;

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['registrar_factura'])) {
    $tipo = $_POST['tipo'];
    $cliente_o_proveedor_id = ($tipo == 'venta') ? $_POST['clt_id'] : $_POST['prov_id'];
    $folio = $_POST['folio'];
    $total = $_POST['total'];
    $estado = $_POST['estado'];
    $metodo_pago = $_POST['met_pago_id'];

    $resultado = agregar_factura($tipo, $cliente_o_proveedor_id, $folio, $total, $estado, $metodo_pago, $conexion);
    if ($resultado) {
        header('Location: facturas.php?mensaje=Factura registrada con éxito');
        exit();
    } else {
        echo "<div class='alert alert-danger'>Hubo un error al registrar la factura.</div>";
    }
}
?>

<div id="page-content-wrapper">
    <div class="container mt-5">
        <h1 class="mb-4">Registrar Factura</h1>
        <form method="POST">
            <!-- Tipo de Factura -->
            <div class="mb-3">
                <label for="tipo" class="form-label">Tipo de Factura</label>
                <select id="tipo" name="tipo" class="form-select" required onchange="mostrarClienteProveedor()">
                    <option value="">Seleccione un tipo</option>
                    <option value="venta">Venta</option>
                    <option value="compra">Compra</option>
                </select>
            </div>

            <!-- Cliente o Proveedor (aparece según el tipo de factura) -->
            <div class="mb-3" id="cliente-container" style="display:none;">
                <label for="clt_id" class="form-label">Cliente</label>
                <select id="clt_id" name="clt_id" class="form-select">
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

            <div class="mb-3" id="proveedor-container" style="display:none;">
                <label for="prov_id" class="form-label">Proveedor</label>
                <select id="prov_id" name="prov_id" class="form-select">
                    <option value="">Seleccione un proveedor</option>
                    <?php
                    $proveedores = obtener_proveedores($conexion);
                    while ($proveedor = mysqli_fetch_assoc($proveedores)): ?>
                        <option value="<?php echo $proveedor['prov_id']; ?>">
                            <?php echo $proveedor['prov_nombre']; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <!-- Folio -->
            <div class="mb-3">
                <label for="folio" class="form-label">Folio</label>
                <input type="text" id="folio" name="folio" class="form-control" required>
            </div>

            <!-- Total -->
            <div class="mb-3">
                <label for="total" class="form-label">Total</label>
                <input type="number" step="0.01" id="total" name="total" class="form-control" required>
            </div>

            <!-- Estado -->
            <div class="mb-3">
                <label for="estado" class="form-label">Estado</label>
                <select id="estado" name="estado" class="form-select" required>
                    <option value="pendiente">Pendiente</option>
                    <option value="pagado">Pagado</option>
                </select>
            </div>

            <!-- Método de pago -->
            <div class="mb-3">
                <label for="met_pago_id" class="form-label">Método de Pago</label>
                <select id="met_pago_id" name="met_pago_id" class="form-select" required>
                    <option value="">Seleccione un método de pago</option>
                    <?php
                    $metodos_pago = obtener_metodos_pago($conexion);
                    while ($metodo = mysqli_fetch_assoc($metodos_pago)): ?>
                        <option value="<?php echo $metodo['met_pago_id']; ?>">
                            <?php echo $metodo['met_pago_nombre']; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <button type="submit" name="registrar_factura" class="btn btn-success mt-4">Registrar Factura</button>
        </form>
    </div>
</div>

<script>
    // Función para mostrar los campos según el tipo de factura seleccionado
    function mostrarClienteProveedor() {
        const tipo = document.getElementById('tipo').value;
        const clienteContainer = document.getElementById('cliente-container');
        const proveedorContainer = document.getElementById('proveedor-container');

        if (tipo === 'venta') {
            clienteContainer.style.display = 'block';
            proveedorContainer.style.display = 'none';
        } else if (tipo === 'compra') {
            clienteContainer.style.display = 'none';
            proveedorContainer.style.display = 'block';
        } else {
            clienteContainer.style.display = 'none';
            proveedorContainer.style.display = 'none';
        }
    }
</script>

<?php include '../../includes/footer.php'; ?>



Warning: include(../../functions/CRUD/metodos_pago_functions.php): Failed to open stream: No such file or directory in C:\MAMP\htdocs\SI-Nestle-Purina\modules\facturas\agregar_factura.php on line 17

Warning: include(): Failed opening '../../functions/CRUD/metodos_pago_functions.php' for inclusion (include_path='.;C:\php\pear') in C:\MAMP\htdocs\SI-Nestle-Purina\modules\facturas\agregar_factura.php on line 17