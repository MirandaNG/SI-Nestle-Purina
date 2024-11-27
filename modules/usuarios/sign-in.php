<?php
session_start();
include '../../config/conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usu_nombre = $_POST['usu_nombre'];
    $usu_correo = $_POST['usu_correo'];
    $usu_contraseña = password_hash($_POST['usu_contraseña'], PASSWORD_DEFAULT);
    $rol_id = $_POST['rol_id'];

    // Insertar el nuevo usuario
    $query = "INSERT INTO Usuarios (usu_nombre, usu_correo, usu_contraseña, rol_id) VALUES (?, ?, ?, ?)";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param('sssi', $usu_nombre, $usu_correo, $usu_contraseña, $rol_id);

    if ($stmt->execute()) {
        // Asignar permisos al nuevo usuario según el rol
        $query_permisos = "SELECT perm_tabla, perm_tipo_acceso FROM Permisos WHERE rol_id = ?";
        $stmt_permisos = $conexion->prepare($query_permisos);
        $stmt_permisos->bind_param('i', $rol_id);
        $stmt_permisos->execute();
        $resultado_permisos = $stmt_permisos->get_result();

        $permisos = [];
        while ($permiso = $resultado_permisos->fetch_assoc()) {
            $permisos[$permiso['perm_tabla']][] = $permiso['perm_tipo_acceso'];
        }

        // Guardar permisos en la sesión
        $_SESSION['permisos'] = $permisos;

        $success = "Usuario registrado exitosamente.";
    } else {
        $error = "Error al registrar el usuario: " . $conexion->error;
    }
}

// Obtener departamentos y roles para el formulario
$departamentos = $conexion->query("SELECT * FROM Departamentos");
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Crear Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h2 class="mt-5">Registrar Nuevo Usuario</h2>
        <?php if (!empty($success)): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php elseif (!empty($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form action="" method="post">
            <div class="mb-3">
                <label for="usu_nombre" class="form-label">Nombre:</label>
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
                <label for="rol_id" class="form-label">Rol:</label>
                <select class="form-control" name="rol_id" required>
                    <option value="1">Administrador</option>
                    <option value="2">Empleado</option>
                    <!-- Agregar más opciones según roles -->
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Registrar Usuario</button>
        </form>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function cargarRoles() {
            const departamentoId = document.getElementById('depa_id').value;
            const rolSelect = document.getElementById('rol_id');
            rolSelect.innerHTML = '<option value="">Cargando roles...</option>';

            fetch(`modules/usuarios/get_roles.php?depa_id=${departamentoId}`)
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
</body>
</html>