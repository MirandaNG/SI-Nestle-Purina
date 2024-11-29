<?php
include '../../config/conexion.php';
include '../../includes/header.php';
include '../../functions/clientes_functions.php';

// Llamada para obtener la lista de clientes
$clientes = obtener_clientes($conexion);

// Si el usuario envía el formulario para crear un cliente
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['crear_cliente'])) {
    $clt_nombre = $_POST['clt_nombre'];
    $clt_direccion = $_POST['clt_direccion'];
    $clt_telefono = $_POST['clt_telefono'];
    $clt_email = $_POST['clt_email'];
    $clt_tipo = $_POST['clt_tipo'];

    $resultado = crear_cliente($clt_nombre, $clt_direccion, $clt_telefono, $clt_email, $clt_tipo, $conexion);
    if ($resultado) {
        echo "Cliente creado con éxito";
    } else {
        echo "Error al crear cliente";
    }
}
?>


<div id="page-content-wrapper">
    <div class="container mt-5">
        <h1 class="mb-4">Agregar Cliente</h1>

        <!-- Formulario para crear cliente -->
        <form action="" method="POST">
            <input type="text" name="clt_nombre" placeholder="Nombre" required>
            <input type="text" name="clt_direccion" placeholder="Dirección" required>
            <input type="text" name="clt_telefono" placeholder="Teléfono" required>
            <input type="email" name="clt_email" placeholder="Correo" required>
            <select name="clt_tipo">
                <option value="Particular">Particular</option>
                <option value="Empresa">Empresa</option>
            </select>
            <button type="submit" name="crear_cliente">Crear Cliente</button>
        </form>
    </div>
</div>

<?php
    include '../../includes/footer.php';
?>