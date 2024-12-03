<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: ../usuarios/login.php');
    exit();
}

include '../../config/conexion.php';
include '../../includes/header.php';
include '../../functions/CRUD/empleados_functions.php';

// Obtener el ID del empleado a editar
if (isset($_GET['id'])) {
    $empl_id = $_GET['id'];
    $empleado = obtener_empleado_por_id($empl_id, $conexion);
    $depa_query = "SELECT depa_id, depa_nombre FROM departamentos";
    $departamentos = $conexion->query($depa_query);
}

// Procesar el formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $empl_nombre = $_POST['empl_nombre'];
    $empl_apellido = $_POST['empl_apellido'];
    $empl_puesto = $_POST['empl_puesto'];
    $depa_id = $_POST['depa_id'];
    $empl_salario = $_POST['empl_salario'];
    $empl_fecha_contrat = $_POST['empl_fecha_contrat'];
    $empl_estado = $_POST['empl_estado'];

    $resultado = actualizar_empleado($empl_id, $empl_nombre, $empl_apellido, $empl_puesto, $depa_id, $empl_salario, $empl_fecha_contrat, $empl_estado, $conexion);
    echo "<div class='alert alert-success'>$resultado</div>";
}
?>

<div id="page-content-wrapper">
    <div class="container mt-5">
        <h1 class="mb-4">Editar Empleado</h1>
        <form method="POST">
            <div class="mb-3">
                <label for="empl_nombre" class="form-label">Nombre</label>
                <input type="text" class="form-control" id="empl_nombre" name="empl_nombre" value="<?php echo $empleado['empl_nombre']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="empl_apellido" class="form-label">Apellido</label>
                <input type="text" class="form-control" id="empl_apellido" name="empl_apellido" value="<?php echo $empleado['empl_apellido']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="empl_puesto" class="form-label">Puesto</label>
                <input type="text" class="form-control" id="empl_puesto" name="empl_puesto" value="<?php echo $empleado['empl_puesto']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="depa_id" class="form-label">Departamento</label>
                <select class="form-select" id="depa_id" name="depa_id" required>
                    <?php while ($depa = $departamentos->fetch_assoc()) : ?>
                        <option value="<?php echo $depa['depa_id']; ?>" <?php if ($depa['depa_id'] == $empleado['depa_id']) echo 'selected'; ?>><?php echo $depa['depa_nombre']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="empl_salario" class="form-label">Salario</label>
                <input type="number" class="form-control" id="empl_salario" name="empl_salario" value="<?php echo $empleado['empl_salario']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="empl_fecha_contrat" class="form-label">Fecha de Contratación</label>
                <input type="date" class="form-control" id="empl_fecha_contrat" name="empl_fecha_contrat" value="<?php echo $empleado['empl_fecha_contrat']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="empl_estado" class="form-label">Estado</label>
                <select class="form-select" id="empl_estado" name="empl_estado" required>
                    <option value="activo" <?php if ($empleado['empl_estado'] == 'activo') echo 'selected'; ?>>Activo</option>
                    <option value="inactivo" <?php if ($empleado['empl_estado'] == 'inactivo') echo 'selected'; ?>>Inactivo</option>
                </select>
            </div>
            <button type="submit" class="btn btn-warning">Actualizar Empleado</button>
        </form>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>
