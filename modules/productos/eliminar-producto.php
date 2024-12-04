<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: ../usuarios/login.php');
    exit();
}

include '../../config/conexion.php';
include '../../functions/CRUD/productos_functions.php';

// Verificar si se recibe el ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<div class='alert alert-danger'>ID de producto inválido.</div>";
    exit();
}

$id = $_GET['id'];

if (eliminar_producto($conexion, $id)) {
    header('Location: productos.php?mensaje=Producto eliminado con éxito');
    exit();
} else {
    echo "<div class='alert alert-danger'>Error al eliminar el producto.</div>";
}
?>
