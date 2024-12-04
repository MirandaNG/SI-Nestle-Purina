<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: ../usuarios/login.php');
    exit();
}

include '../../config/conexion.php';
include '../../includes/header.php';
include '../../functions/CRUD/empleados_functions.php';

// Obtener departamentos
$depa_query = "SELECT depa_id, depa_nombre FROM departamentos";
$departamentos = $conexion->query($depa_query);

// Procesar el formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $empl_nombre = $_POST['empl_nombre'];
    $empl_apellido = $_POST['empl_apellido'];
    $empl_puesto = $_POST['empl_puesto'];
    $depa_id = $_POST['depa_id'];
    $empl_salario = $_POST['empl_salario'];
    $empl_fecha_contrat = $_POST['empl_fecha_contrat'];
    $empl_estado = $_POST['empl_estado'];

    $resultado = registrar_empleado($empl_nombre, $empl_apellido, $empl_puesto, $depa_id, $empl_salario, $empl_fecha_contrat, $empl_estado, $conexion);
    echo "<div class='alert alert-success'>$resultado</div>";
}
?>

<div id="page-content-wrapper">
    <div class="container mt-5">
        <h1 class="mb-4">Agregar Empleado</h1>
        <form method="POST">
            <div class="mb-3">
                <label for="empl_nombre" class="form-label">Nombre</label>
                <input type="text" class="form-control" id="empl_nombre" name="empl_nombre" required>
            </div>
            <div class="mb-3">
                <label for="empl_apellido" class="form-label">Apellido</label>
                <input type="text" class="form-control" id="empl_apellido" name="empl_apellido" required>
            </div>
            <div class="mb-3">
                <label for="empl_puesto" class="form-label">Puesto</label>
                <input type="text" class="form-control" id="empl_puesto" name="empl_puesto" required>
            </div>
            <div class="mb-3">
                <label for="depa_id" class="form-label">Departamento</label>
                <select class="form-select" id="depa_id" name="depa_id" required>
                    <option value="">Seleccione un departamento</option>
                    <?php while ($depa = $departamentos->fetch_assoc()) : ?>
                        <option value="<?php echo $depa['depa_id']; ?>"><?php echo $depa['depa_nombre']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="empl_salario" class="form-label">Salario</label>
                <input type="number" class="form-control" id="empl_salario" name="empl_salario" required>
            </div>
            <div class="mb-3">
                <label for="empl_fecha_contrat" class="form-label">Fecha de Contratación</label>
                <input type="date" class="form-control" id="empl_fecha_contrat" name="empl_fecha_contrat" required>
            </div>
            <div class="mb-3">
                <label for="empl_estado" class="form-label">Estado</label>
                <select class="form-select" id="empl_estado" name="empl_estado" required>
                    <option value="activo">Activo</option>
                    <option value="inactivo">Inactivo</option>
                </select>
            </div>
            <button type="submit" class="btn btn-success">Registrar Empleado</button>
        </form>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>
