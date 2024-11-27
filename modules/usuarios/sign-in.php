<?php
session_start();
include '../../config/conexion.php';
include '../../includes/header-01.php';
include '../../functions/usuarios_functions.php';
include '../../functions/empleados_functions.php';
include '../../functions/roles_functions.php';
include '../../functions/departamentos_functions.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usu_nombre = $_POST['usu_nombre'];
    $usu_correo = $_POST['usu_correo'];
    $usu_contraseña = password_hash($_POST['usu_contraseña'], PASSWORD_DEFAULT);
    $rol_id = $_POST['rol_id'];
    $depa_id = $_POST['depa_id'];
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];

    // Validar que el empleado exista y esté activo
    $empleado = obtener_empleado_activo($nombre, $apellido, $depa_id, $conexion);
    if (!$empleado) {
        $error = "Error: Solo los empleados pueden crear un usuario.";
    } else {
        $empl_id = $empleado['empl_id'];
        
        // Registrar usuario
        $resultado = registrar_usuario($usu_nombre, $usu_correo, $usu_contraseña, $rol_id, $empl_id, $conexion);
        if (str_starts_with($resultado, "Error")) {
            $error = $resultado;
        } else {
            $success = $resultado;
        }
    }
}

// Obtener los departamentos para el formulario
$departamentos = obtener_departamentos($conexion);
?>

    <div class="container">
        <h2 class="mt-5 tittle-sign-in">Registrar Nuevo Usuario</h2>
        
        <?php if (!empty($success)): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
            <?php unset($success); ?>
        <?php elseif (!empty($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php unset($error); ?>
        <?php endif; ?>
        
        <form action="" method="post">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre:</label>
                <input type="text" class="form-control" id="nombre" name="nombre" required>
            </div>
            <div class="mb-3">
                <label for="apellido" class="form-label">Apellido:</label>
                <input type="text" class="form-control" id="apellido" name="apellido" required>
            </div>
            <div class="mb-3">
                <label for="usu_nombre" class="form-label">Usuario:</label>
                <input type="text" class="form-control" id="usu_nombre" name="usu_nombre" required>
            </div>
            <div class="mb-3">
                <label for="usu_correo" class="form-label">Correo:</label>
                <input type="email" class="form-control" id="usu_correo" name="usu_correo" required>
            </div>
            <div class="mb-3">
                <label for="usu_contraseña" class="form-label">Contraseña:</label>
                <input type="password" class="form-control" id="usu_contraseña" name="usu_contraseña" required>
            </div>
            <div class="mb-3">
                <label for="depa_id" class="form-label">Departamento:</label>
                <select class="form-select" id="depa_id" name="depa_id" required onchange="cargarRoles()">
                    <option value="">Seleccione un Departamento</option>
                    <?php while ($depa = $departamentos->fetch_assoc()): ?>
                        <option value="<?php echo $depa['depa_id']; ?>"><?php echo $depa['depa_nombre']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="rol_id" class="form-label">Rol:</label>
                <select class="form-select" id="rol_id" name="rol_id" required>
                    <option value="">Seleccione un Rol</option>
                </select>
            </div>
            <button type="submit" class="btn btn-sign-in">Registrar</button>
        </form>
    </div>
    <script>
        function cargarRoles() {
            const departamentoId = document.getElementById('depa_id').value;
            const rolSelect = document.getElementById('rol_id');
            rolSelect.innerHTML = '<option value="">Cargando roles...</option>';

            fetch(`get_roles.php?depa_id=${departamentoId}`)
                .then(response => response.json())
                .then(data => {
                    rolSelect.innerHTML = '<option value="">Seleccione un Rol</option>';
                    data.forEach(rol => {
                        const option = document.createElement('option');
                        option.value = rol.rol_id;
                        option.textContent = rol.rol_nombre;
                        rolSelect.appendChild(option);
                    });
                })
                .catch(error => console.error('Error cargando roles:', error));
        }
    </script>

<?php
    include '../../includes/footer.php';
?>