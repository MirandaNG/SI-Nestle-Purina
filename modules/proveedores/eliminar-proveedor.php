<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: ../usuarios/login.php');
    exit();
}

$usuario = $_SESSION['usuario'];

include '../../config/conexion.php';
include '../../includes/header.php';
include '../../functions/CRUD/proveedores_functions.php';

// Validar que se haya recibido un ID del proveedor
if (isset($_GET['id'])) {
    $proveedor_id = $_GET['id'];
    $proveedor = obtener_proveedor_por_id($proveedor_id, $conexion);

    if (!$proveedor) {
        header('Location: proveedores.php?error=Proveedor no encontrado');
        exit();
    }
} else {
    header('Location: proveedores.php?error=ID de proveedor no especificado');
    exit();
}

// Si el usuario confirma la eliminación
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['confirmar_eliminar'])) {
    $resultado = eliminar_proveedor($proveedor_id, $conexion);

    if ($resultado) {
        header('Location: proveedores.php?mensaje=Proveedor eliminado con éxito');
        exit();
    } else {
        echo "<div class='alert alert-danger'>Error al eliminar al proveedor.</div>";
    }
}
?>

<div id="page-content-wrapper">
    <div class="container mt-5">
        <div class="card border-danger" style="transform: none !important;">
            <div class="card-header bg-danger text-white">
                <h2 class="mb-0">Eliminar Proveedor</h2>
            </div>
            <div class="card-body">
                <p class="lead">¿Estás seguro de que deseas eliminar al proveedor <strong><?php echo htmlspecialchars($proveedor['prov_nombre']); ?></strong>?</p>
                <p class="text-muted">Esta acción no se puede deshacer.</p>
                <form method="POST">
                    <div class="d-flex justify-content-end">
                        <button type="submit" name="confirmar_eliminar" class="btn btn-danger me-2">Eliminar Proveedor</button>
                        <a href="proveedores.php" class="btn btn-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>