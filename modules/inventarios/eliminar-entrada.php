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

// Obtener el ID de la entrada a eliminar
if (isset($_GET['id'])) {
    $entrada_id = $_GET['id'];
    $resultado = eliminar_entrada_inventario($entrada_id, $conexion);
    if ($resultado) {
        header('Location: entradas-inventario.php?mensaje=Entrada eliminada con éxito');
        exit();
    } else {
        echo "Error al eliminar la entrada.";
    }
} else {
    echo "ID de entrada no especificado.";
    exit();
}
?>

<?php
include '../../includes/footer.php';
?>
