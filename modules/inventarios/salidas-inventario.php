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

// Obtener lista de salidas
$salidas = obtener_salidas_inventario($conexion);

// Si el usuario envía el formulario para registrar una salida
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['registrar_salida'])) {
    $inv_id = $_POST['inv_id'];
    $cantidad = $_POST['inv_sal_cantidad'];
    $fecha = $_POST['inv_sal_fecha'];
    $destino = $_POST['inv_sal_destino'];
    $motivo = $_POST['inv_sal_motivo'];

    $resultado = registrar_salida_inventario($inv_id, $cantidad, $fecha, $destino, $motivo, $conexion);
    if ($resultado) {
        echo "Salida registrada con éxito";
    } else {
        echo "Error al registrar la salida";
    }
}
?>

<div id="page-content-wrapper">
    <div class="container mt-5">
        <h1 class="mb-4">Salidas de Inventario</h1>

        <div class="mb-3">
            <a href="agregar-salida.php" class="btn btn-success mb-3">Registrar Salida</a>
        </div>


        <!-- Tabla de Salidas -->
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Fecha</th>
                    <th>Destino</th>
                    <th>Motivo</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($salida = mysqli_fetch_assoc($salidas)): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($salida['inv_nombre']); ?></td>
                        <td><?php echo $salida['inv_sal_cantidad']; ?></td>
                        <td><?php echo htmlspecialchars($salida['inv_sal_fecha']); ?></td>
                        <td><?php echo htmlspecialchars($salida['inv_sal_destino']); ?></td>
                        <td><?php echo htmlspecialchars($salida['inv_sal_motivo']); ?></td>
                        <td>
                            <a href="editar-salida.php?id=<?php echo $salida['id']; ?>" class="btn btn-warning btn-sm">Editar</a>
                            <a href="eliminar-salida.php?id=<?php echo $salida['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de eliminar esta salida?');">Eliminar</a>
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
