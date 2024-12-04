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

// Obtener el ID a eliminar
if (isset($_GET['id'])) {
    $ubica_id = $_GET['id'];
    $resultado = eliminar_ubicacion($ubica_id, $conexion);
    if ($resultado) {
        header('Location: ubicaciones.php?mensaje=Ubicacion eliminada con éxito');
        exit();
    } else {
        echo "Error al eliminar ubicacion.";
    }
} else {
    echo "ID de ubicacion no especificado.";
    exit();
}
?>

<?php
include '../../includes/footer.php';
?>