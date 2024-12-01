<?php
    session_start();
    if (!isset($_SESSION['usuario'])) {
        header('Location: ../usuarios/login.php');
        exit();
    }

    include '../../config/conexion.php';
    include '../../includes/header.php';
    include '../../functions/CRUD/ubicaciones_functions.php';

    // Obtener lista de ubicaciones
    $ubicaciones = obtener_ubicaciones($conexion);
?>

<div id="page-content-wrapper">
    <div class="container mt-5">
        <h1 class="mb-4">Gestión de Ubicaciones</h1>

        <!-- Mostrar mensajes -->
        <?php if (isset($_SESSION['mensaje_exito'])): ?>
            <div class="alert alert-success"><?php echo $_SESSION['mensaje_exito']; unset($_SESSION['mensaje_exito']); ?></div>
        <?php endif; ?>
        <?php if (isset($_SESSION['mensaje_error'])): ?>
            <div class="alert alert-danger"><?php echo $_SESSION['mensaje_error']; unset($_SESSION['mensaje_error']); ?></div>
        <?php endif; ?>

        <!-- Botón para agregar ubicación -->
        <div class="mb-3">
            <a href="agregar-ubicacion.php" class="btn btn-success mb-3">Agregar Ubicación</a>
        </div>

        <!-- Tabla de Ubicaciones -->
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Dirección</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($almacen = mysqli_fetch_assoc($ubicaciones)): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($almacen['ubica_nombre']); ?></td>
                        <td><?php echo htmlspecialchars($almacen['ubica_direccion']); ?></td>
                        <td>
                            <a href="editar-ubicacion.php?id=<?php echo $almacen['ubica_id']; ?>" class="btn btn-warning btn-sm">Editar</a>
                            <a href="eliminar-ubicacion.php?id=<?php echo $almacen['ubica_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de eliminar este producto?');">Eliminar</a>
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