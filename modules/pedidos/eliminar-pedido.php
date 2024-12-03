<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: ../usuarios/login.php');
    exit();
}

$usuario = $_SESSION['usuario'];

include '../../config/conexion.php';
include '../../includes/header.php';
include '../../functions/CRUD/pedidos_functions.php';

// Verificar si el ID del pedido está presente
if (isset($_GET['pedo_id'])) {
    $pedo_id = $_GET['pedo_id'];

    // Eliminar el pedido
    $resultado = eliminar_pedido($pedo_id, $conexion);
    if ($resultado) {
        header('Location: pedidos.php?mensaje=Pedido eliminado con éxito');
        exit();
    } else {
        echo "<div class='alert alert-danger'>Error al eliminar el pedido</div>";
    }
}

?>

<div id="page-content-wrapper">
    <div class="container mt-5">
        <h1 class="mb-4">Eliminar Pedido</h1>
        <p>¿Está seguro de eliminar este pedido?</p>
        <form method="POST">
            <button type="submit" name="eliminar_pedido" class="btn btn-danger">Eliminar Pedido</button>
            <a href="pedidos.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</div>

<?php
include '../../includes/footer.php';
?>
