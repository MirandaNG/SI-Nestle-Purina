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

// Verificar si se ha pasado el ID del empleado a editar
if (isset($_GET['id'])) {
    $empleado_id = $_GET['id'];
    
    // Obtener los detalles del empleado desde la base de datos
    $empleado = obtener_empleado_por_id($empleado_id, $conexion);
    if ($empleado) {
        // Variables con los datos actuales del empleado
        $nombre = $empleado['empl_nombre'];
        $apellido = $empleado['empl_apellido'];
        $puesto = $empleado['empl_puesto'];
        $departa = $empleado['depa_id'];
        $salario = $empleado['empl_salario'];
        $fecha_contrato = $empleado['empl_fecha_contrat'];
        $estado = $empleado['empl_estado'];
    } else {
        // Si no se encuentra el empleado
        echo "<div class='alert alert-danger'>Empleado no encontrado.</div>";
        exit();
    }
}

$departamentos = obtener_departamentos($conexion);

// Procesar el formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['actualizar_empleado'])) {
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $puesto = $_POST['puesto'];
    $departa = $_POST['departa'];
    $salario = $_POST['salario'];
    $fecha_contrato = $_POST['fecha_contrato'];
    $estado = $_POST['estado'];

    $resultado = actualizar_empleado($empl_id, $nombre, $apellido, $puesto, $departa, $salario, $fecha_contrato, $estado, $conexion);
    echo "<div class='alert alert-success'>$resultado</div>";
}
?>

<div id="page-content-wrapper">
    <div class="container mt-5">
        <h1 class="mb-4">Editar Empleado</h1>
        <form method="POST">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo $empleado['empl_nombre']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="apellido" class="form-label">Apellido</label>
                <input type="text" class="form-control" id="apellido" name="apellido" value="<?php echo $empleado['empl_apellido']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="puesto" class="form-label">Puesto</label>
                <input type="text" class="form-control" id="puesto" name="puesto" value="<?php echo $empleado['empl_puesto']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="departa" class="form-label">Departamento</label>
                <select class="form-select" id="departa" name="departa" required>
                    <?php while ($depa = $departamentos->fetch_assoc()) : ?>
                        <option value="<?php echo $depa['depa_id']; ?>" <?php if ($depa['depa_id'] == $empleado['depa_id']) echo 'selected'; ?>><?php echo $depa['depa_nombre']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="salario" class="form-label">Salario</label>
                <input type="number" class="form-control" id="salario" name="salario" value="<?php echo $empleado['empl_salario']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="fecha_contrato" class="form-label">Fecha de Contratación</label>
                <input type="date" class="form-control" id="fecha_contrato" name="fecha_contrato" value="<?php echo $empleado['empl_fecha_contrat']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="estado" class="form-label">Estado</label>
                <select class="form-select" id="estado" name="estado" required>
                    <option value="activo" <?php if ($empleado['empl_estado'] == 'activo') echo 'selected'; ?>>Activo</option>
                    <option value="inactivo" <?php if ($empleado['empl_estado'] == 'inactivo') echo 'selected'; ?>>Inactivo</option>
                </select>
            </div>
            <button type="submit" name="actualizar_empleado" class="btn btn-warning">Actualizar Empleado</button>
        </form>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>
