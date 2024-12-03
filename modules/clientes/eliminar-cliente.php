<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: ../usuarios/login.php');
    exit();
}

$usuario = $_SESSION['usuario'];

include '../../config/conexion.php';
include '../../includes/header.php';
include '../../functions/CRUD/clientes_functions.php';

// Validar que se haya recibido un ID de cliente
if (isset($_GET['id'])) {
    $cliente_id = $_GET['id'];
    $cliente = obtener_cliente_por_id($cliente_id, $conexion);

    if (!$cliente) {
        header('Location: clientes.php?error=Cliente no encontrado');
        exit();
    }
} else {
    header('Location: clientes.php?error=ID de cliente no especificado');
    exit();
}

// Si el usuario confirma la eliminación
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['confirmar_eliminar'])) {
    $resultado = eliminar_cliente($cliente_id, $conexion);

    if ($resultado) {
        header('Location: clientes.php?mensaje=Cliente eliminado con éxito');
        exit();
    } else {
        echo "<div class='alert alert-danger'>Error al eliminar el cliente.</div>";
    }
}
?>

<div id="page-content-wrapper">
    <div class="container mt-5">
        <div class="card border-danger" style="transform: none !important;">
            <div class="card-header bg-danger text-white">
                <h2 class="mb-0">Eliminar Cliente</h2>
            </div>
            <div class="card-body">
                <p class="lead">¿Estás seguro de que deseas eliminar al cliente <strong><?php echo htmlspecialchars($cliente['clt_nombre']); ?></strong>?</p>
                <p class="text-muted">Esta acción no se puede deshacer.</p>
                <form method="POST">
                    <div class="d-flex justify-content-end">
                        <button type="submit" name="confirmar_eliminar" class="btn btn-danger me-2">Eliminar Cliente</button>
                        <a href="clientes.php" class="btn btn-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>