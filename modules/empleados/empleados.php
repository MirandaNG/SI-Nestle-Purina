<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: ../usuarios/login.php');
    exit();
}

include '../../config/conexion.php';
include '../../includes/header.php';
include '../../functions/CRUD/empleados_functions.php';
include '../../functions/No-CRUD/departamentos_functions.php';

// Obtener empleados
$empleados = obtener_empleados($conexion);
?>

<div id="page-content-wrapper">
    <div class="container mt-5">
        <h1 class="mb-4">Empleados</h1>

        <div class="mb-3">
            <a href="agregar_empleado.php" class="btn btn-success mb-3">Agregar Empleado</a>
        </div>

        <!-- Tabla de Empleados -->
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Puesto</th>
                    <th>Departamento</th>
                    <th>Salario</th>
                    <th>Fecha Contratación</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($empleado = $empleados->fetch_assoc()) : ?>
                    <tr>
                        <td><?php echo $empleado['empl_nombre'] . ' ' . $empleado['empl_apellido']; ?></td>
                        <td><?php echo $empleado['empl_puesto']; ?></td>
                        <td><?php echo $empleado['depa_nombre']; ?></td>
                        <td><?php echo '$' . number_format($empleado['empl_salario'], 2); ?></td>
                        <td><?php echo date('d/m/Y', strtotime($empleado['empl_fecha_contrat'])); ?></td>
                        <td><?php echo ucfirst($empleado['empl_estado']); ?></td>
                        <td>
                            <a href="editar-empleado.php?id=<?php echo $empleado['empl_id']; ?>" class="btn btn-warning btn-sm">Editar</a>
                            <a href="eliminar-empleado.php?id=<?php echo $empleado['empl_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de eliminar este empleado?');">Eliminar</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>
