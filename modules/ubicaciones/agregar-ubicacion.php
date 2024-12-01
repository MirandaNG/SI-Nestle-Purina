<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: ../usuarios/login.php');
    exit();
}

$usuario = $_SESSION['usuario'];

include '../../config/conexion.php';
include '../../includes/header.php';
include '../../functions/CRUD/ubicaciones_functions.php';
include '../../functions/CRUD/inventarios_functions.php';

// Si el usuario envía el formulario para registrar una ubicacion
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['registrar_ubicacion'])) {
    $nombre = $_POST['nombre'];
    $direccion = $_POST['direccion'];

    $resultado = agregar_ubicacion($nombre, $direccion, $conexion);
    if ($resultado) {
        header('Location: ubicaciones.php?mensaje=Ubicacion registrada con éxito');
        exit();
    } else {
        echo "Error al registrar ubicacion.";
    }
}
?>

<div id="page-content-wrapper">
    <div class="container mt-5">
        <h1 class="mb-4">Registrar Ubicación</h1>
        <form method="POST">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" id="nombre" name="nombre" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="direccion" class="form-label">Dirección</label>
                <input type="text" id="direccion" name="direccion" class="form-control" required>
            </div>
            <button type="submit" name="registrar_ubicacion" class="btn btn-success">Registrar Ubicación</button>
        </form>
    </div>
</div>

<?php
include '../../includes/footer.php';
?>