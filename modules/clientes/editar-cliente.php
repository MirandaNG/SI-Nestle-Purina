<?php
include '../../config/conexion.php';
include '../../includes/header.php';
include '../../functions/clientes_functions.php';

if (isset($_GET['id'])) {
    $clt_id = $_GET['id'];
    $cliente = obtener_cliente($clt_id, $conexion);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['editar_cliente'])) {
    $clt_nombre = $_POST['clt_nombre'];
    $clt_direccion = $_POST['clt_direccion'];
    $clt_telefono = $_POST['clt_telefono'];
    $clt_email = $_POST['clt_email'];
    $clt_tipo = $_POST['clt_tipo'];

    $resultado = editar_cliente($clt_id, $clt_nombre, $clt_direccion, $clt_telefono, $clt_email, $clt_tipo, $conexion);
    if ($resultado) {
        echo "Cliente actualizado con éxito";
    } else {
        echo "Error al actualizar cliente";
    }
}
?>

<div id="page-content-wrapper">
    <div class="container mt-5">
        <h1 class="mb-4">Editar Cliente</h1>
        <form action="" method="POST">
            <input type="text" name="clt_nombre" value="<?php echo $cliente['clt_nombre']; ?>" required>
            <input type="text" name="clt_direccion" value="<?php echo $cliente['clt_direccion']; ?>" required>
            <input type="text" name="clt_telefono" value="<?php echo $cliente['clt_telefono']; ?>" required>
            <input type="email" name="clt_email" value="<?php echo $cliente['clt_email'];?>" required>
            <select name="clt_tipo"> 
                <option value="Particular" <?php echo ($cliente['clt_tipo'] == 'Particular') ? 'selected' : ''; ?>>Particular</option>
                <option value="Empresa" <?php echo ($cliente['clt_tipo'] == 'Empresa') ? 'selected' : ''; ?>>Empresa</option>
            </select>
            <button type="submit" name="editar_cliente">Actualizar Cliente</button>
        </form>
        <a href="clientes.php">Volver</a>
    </div>
</div>

<?php
    include '../../includes/footer.php';
?>