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

// Obtener el ID a editar
if (isset($_GET['id'])) {
    $ubica_id = $_GET['id'];
    $ubicacion = obtener_ubicacion_por_id($ubica_id, $conexion);
    if (!$ubicacion) {
        echo "Entrada no encontrada.";
        exit();
    }
} else {
    echo "ID de entrada no especificado.";
    exit();
}

// Si el usuario envía el formulario para editar
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['editar_ubicacion'])) {
    $nombre = $_POST['nombre'];
    $direccion = $_POST['direccion'];

    $resultado = editar_ubicacion($ubica_id, $nombre, $direccion, $conexion);
    if ($resultado) {
        header('Location: ubicaciones.php?mensaje=Ubicacion actualizada con éxito');
        exit();
    } else {
        echo "Error al actualizar ubicacion.";
    }
}
?>

<div id="page-content-wrapper">
    <div class="container mt-5">
        <h1 class="mb-4">Editar Ubicación</h1>
        <form method="POST">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" id="nombre" name="nombre" class="form-control" value="<?php echo $ubicacion['ubica_nombre']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="direccion" class="form-label">Dirección</label>
                <input type="text" id="direccion" name="direccion" class="form-control" value="<?php echo $ubicacion['ubica_direccion']; ?>" required>
            </div>
            <button type="submit" name="editar_ubicacion" class="btn btn-warning">Actualizar Ubicación</button>
        </form>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>