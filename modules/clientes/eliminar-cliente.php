<?php
include 'conexion.php';
include 'clientes_functions.php';

if (isset($_GET['id'])) {
    $clt_id = $_GET['id'];
    $resultado = eliminar_cliente($clt_id, $conexion);

    if ($resultado) {
        echo "Cliente eliminado con éxito";
    } else {
        echo "Error al eliminar cliente";
    }
}

header("Location: clientes.php");
exit;
?>
