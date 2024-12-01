<?php
    session_start();
    if (!isset($_SESSION['usuario'])) {
        header('Location: ../usuarios/login.php');
        exit();
    }

    include '../../config/conexion.php';
    include '../../includes/header.php';
    include '../../functions/CRUD/inventarios_functions.php';
    include '../../functions/CRUD/ubicaciones_functions.php';

    // Obtener lista de transferencias
    $transferencias = obtener_transferencias($conexion);

    // Si el usuario envía el formulario para registrar una transferencia
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['registrar_transferencia'])) {
        $inv_id = $_POST['inv_id'];
        $origen_id = $_POST['origen_id'];
        $destino_id = $_POST['destino_id'];
        $cantidad = $_POST['cantidad'];
        $fecha = $_POST['fecha'];

        // Registrar transferencia
        $resultado = registrar_transferencia($inv_id, $origen_id, $destino_id, $cantidad, $fecha, $conexion);
        if ($resultado) {
            header('Location: transferencias.php?mensaje=Transferencia registrada con éxito');
            exit();
        } else {
            echo "Error al registrar la transferencia.";
        }
    }
?>

<div id="page-content-wrapper">
    <div class="container mt-5">
        <h1 class="mb-4">Seguimiento de Productos</h1>

        <!-- Mostrar mensajes -->
        <?php if (isset($_SESSION['mensaje_exito'])): ?>
            <div class="alert alert-success"><?php echo $_SESSION['mensaje_exito']; unset($_SESSION['mensaje_exito']); ?></div>
        <?php endif; ?>
        <?php if (isset($_SESSION['mensaje_error'])): ?>
            <div class="alert alert-danger"><?php echo $_SESSION['mensaje_error']; unset($_SESSION['mensaje_error']); ?></div>
        <?php endif; ?>

        <!-- Botón para agregar ubicación -->
        <div class="mb-3">
            <a href="agregar-transferencia.php" class="btn btn-success mb-3">Agregar Transferencia</a>
        </div>

        <!-- Tabla de Ubicaciones -->
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Origen</th>
                    <th>Destino</th>
                    <th>Cantidad</th>
                    <th>Fecha</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($transferencia = mysqli_fetch_assoc($transferencias)): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($transferencia['inv_nombre']); ?></td>
                        <td><?php echo htmlspecialchars($transferencia['origen_nombre']); ?></td>
                        <td><?php echo htmlspecialchars($transferencia['destino_nombre']); ?></td>
                        <td><?php echo $transferencia['transfe_cantidad']; ?></td>
                        <td><?php echo htmlspecialchars($transferencia['transfe_fecha']); ?></td>
                        <td>
                            <a href="editar-transferencia.php?id=<?php echo $transferencia['transfe_id']; ?>" class="btn btn-warning btn-sm">Editar</a>
                            <a href="eliminar-transferencia.php?id=<?php echo $transferencia['transfe_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de eliminar esta transferencia?');">Eliminar</a>
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