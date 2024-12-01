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

// Obtener lista de entradas
$entradas = obtener_entradas_inventario($conexion);

// Si el usuario envía el formulario para registrar una entrada
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['registrar_entrada'])) {
    $inv_id = $_POST['inv_id'];
    $cantidad = $_POST['inv_entra_cantidad'];
    $fecha = $_POST['inv_entra_fecha'];
    $proveedor = $_POST['inv_entra_proveedor'];
    $motivo = $_POST['motivo_id'];

    $resultado = registrar_entrada_inventario($inv_id, $cantidad, $fecha, $proveedor, $motivo, $conexion);
    if ($resultado) {
        echo "Entrada registrada con éxito";
    } else {
        echo "Error al registrar la entrada";
    }
}
?>

<div id="page-content-wrapper">
    <div class="container mt-5">
        <h1 class="mb-4">Entradas de Inventario</h1>

        <div class="mb-3">
            <a href="agregar-entrada.php" class="btn btn-success mb-3">Registrar Entrada</a>
        </div>

        <!-- Tabla de Entradas -->
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Fecha</th>
                    <th>Proveedor</th>
                    <th>Motivo</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($entrada = mysqli_fetch_assoc($entradas)): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($entrada['inv_nombre']); ?></td>
                        <td><?php echo $entrada['inv_entra_cantidad']; ?></td>
                        <td><?php echo htmlspecialchars($entrada['inv_entra_fecha']); ?></td>
                        <td><?php echo htmlspecialchars($entrada['inv_entra_proveedor']); ?></td>
                        <td><?php echo htmlspecialchars($entrada['motivo_nombre']); ?></td>
                        <td>
                            <a href="editar-entrada.php?id=<?php echo $entrada['inv_entra_id']; ?>" class="btn btn-warning btn-sm">Editar</a>
                            <a href="eliminar-entrada.php?id=<?php echo $entrada['inv_entra_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de eliminar esta entrada?');">Eliminar</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php
include '../../includes/footer.php';
?>
