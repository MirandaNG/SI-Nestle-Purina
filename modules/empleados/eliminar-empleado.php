<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: ../usuarios/login.php');
    exit();
}

include '../../config/conexion.php';
include '../../includes/header.php';
include '../../functions/CRUD/empleados_functions.php';

// Obtener el ID del empleado a eliminar
if (isset($_GET['id'])) {
    $empl_id = $_GET['id'];
    $empleado = obtener_empleado_por_id($empl_id, $conexion);
} else {
    echo "<div class='alert alert-danger'>No se ha especificado un empleado para eliminar.</div>";
    exit();
}

// Procesar la eliminación
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $resultado = eliminar_empleado($empl_id, $conexion);
    echo "<div class='alert alert-success'>$resultado</div>";
    header('Location: empleados.php');
    exit();
}
?>

<div id="page-content-wrapper">
    <div class="container mt-5">
        <h1 class="mb-4">Eliminar Empleado</h1>
        <p>¿Estás seguro de que deseas eliminar al siguiente empleado?</p>

        <div class="alert alert-warning">
            <strong>¡Advertencia!</strong> Esta acción no se puede deshacer.
        </div>

        <div class="mb-3">
            <strong>Nombre:</strong> <?php echo $empleado['empl_nombre'] . ' ' . $empleado['empl_apellido']; ?><br>
            <strong>Puesto:</strong> <?php echo $empleado['empl_puesto']; ?><br>
            <strong>Departamento:</strong> 
            <?php
            // Mostrar el nombre del departamento
            $depa_query = "SELECT depa_nombre FROM departamentos WHERE depa_id = ?";
            $stmt = $conexion->prepare($depa_query);
            $stmt->bind_param('i', $empleado['depa_id']);
            $stmt->execute();
            $stmt->bind_result($depa_nombre);
            $stmt->fetch();
            echo $depa_nombre;
            ?>
        </div>

        <form method="POST">
            <button type="submit" class="btn btn-danger">Eliminar Empleado</button>
            <a href="empleados.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>
