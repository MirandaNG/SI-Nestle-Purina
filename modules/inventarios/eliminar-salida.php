<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: ../usuarios/login.php');
    exit();
}

$usuario = $_SESSION['usuario'];

include '../../config/conexion.php';
include '../../includes/header.php';
include '../../functions/CRUD/inventarios_functions.php';

// Obtener el ID de la salida a eliminar
if (isset($_GET['id'])) {
    $salida_id = $_GET['id'];
    $resultado = eliminar_salida_inventario($salida_id, $conexion);
    if ($resultado) {
        header('Location: salidas-inventario.php?mensaje=Salida eliminada con éxito');
        exit();
    } else {
        echo "Error al eliminar la salida.";
    }
} else {
    echo "ID de salida no especificado.";
    exit();
}
?>

<?php
include '../../includes/footer.php';
?>
