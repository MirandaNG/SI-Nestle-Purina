<?php
    session_start();
    if (!isset($_SESSION['usuario'])) {
        header('Location: ../usuarios/login.php');
        exit();
    }

    include '../../config/conexion.php';
    include '../../functions/CRUD/inventarios_functions.php';

    // Verificar que se reciba un ID válido
    if (isset($_GET['id']) && !empty($_GET['id'])) {
        $producto_id = $_GET['id'];
        $resultado = eliminar_producto_inventario($producto_id, $conexion);

        if ($resultado) {
            $_SESSION['mensaje_exito'] = "Producto eliminado con éxito.";
        } else {
            $_SESSION['mensaje_error'] = "Error al eliminar el producto.";
        }
    } else {
        $_SESSION['mensaje_error'] = "ID de producto inválido.";
    }

    header('Location: stock-inventario.php');
    exit();
?>
