<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: ../usuarios/login.php');
    exit();
}

$usuario = $_SESSION['usuario'];

include '../../config/conexion.php';
include '../../includes/header.php';
include '../../functions/CRUD/proveedores_functions.php';

// Si el usuario envía el formulario para agregar un proveedor
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['agregar_proveedor'])) {
    $nombre = $_POST['prov_nombre'];
    $contacto = $_POST['prov_contacto'];
    $telefono = $_POST['prov_telefono'];
    $email = $_POST['prov_correo'];
    $direccion = $_POST['prov_direccion'];
    
    $resultado = agregar_proveedor($nombre, $contacto, $telefono, $email, $direccion, $conexion);
    if ($resultado) {
        header('Location: proveedores.php?mensaje=Proveedor registrado con éxito');
        exit();
    } else {
        echo "Error al registrar proveedor";
    }
}
?>

<div id="page-content-wrapper">
    <div class="container mt-5">
        <h1 class="mb-4">Registrar Proveedor</h1>
        <form method="POST">
            <div class="mb-3">
                <label for="prov_nombre" class="form-label">Nombre</label>
                <input type="text" id="prov_nombre" name="prov_nombre" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="prov_contacto" class="form-label">Contacto</label>
                <input type="text" id="prov_contacto" name="prov_contacto" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="prov_telefono" class="form-label">Telefono</label>
                <input type="text" id="prov_telefono" name="prov_telefono" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="prov_correo" class="form-label">Email</label>
                <input type="email" id="prov_correo" name="prov_correo" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="prov_direccion" class="form-label">Dirección</label>
                <input type="text" id="prov_direccion" name="prov_direccion" class="form-control" required>
            </div>
            <button type="submit" name="agregar_proveedor" class="btn btn-success">Registrar Proveedor</button>
            <a href="proveedores.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>