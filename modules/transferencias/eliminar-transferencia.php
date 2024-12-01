<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: ../usuarios/login.php');
    exit();
}

include '../../config/conexion.php';
include '../../functions/CRUD/inventarios_functions.php';
include '../../functions/CRUD/ubicaciones_functions.php';

// Verificar si se ha enviado el formulario con el ID de la transferencia
if (isset($_POST['transfe_id'])) {
    $transferencia_id = $_POST['transfe_id'];

    // Eliminar la transferencia
    $resultado = eliminar_transferencia($transferencia_id, $conexion);

    if ($resultado) {
        header('Location: transferencias.php?mensaje=Transferencia eliminada con éxito');
        exit();
    } else {
        echo "Error al eliminar la transferencia.";
    }
} else {
    echo "ID de transferencia no proporcionado.";
    exit();
}
?>
