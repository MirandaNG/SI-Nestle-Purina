<?php
    session_start();
    if (!isset($_SESSION['usuario'])) {
        header('Location: ../usuarios/login.php');
        exit();
    }

    $usuario = $_SESSION['usuario'];
    $rol_id = $_SESSION['rol_id'];
    $permisos = $_SESSION['permisos'];

    include '../../config/conexion.php';
    include '../../includes/header.php';
    include '../../functions/CRUD/proveedores_functions.php';

    // Llamada para obtener la lista de proveedores
    $proveedores = obtener_proveedores($conexion);

    // Si el usuario envía el formulario para agregar un proveedor
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['agregar_proveedor'])) {
        $nombre = $_POST['prov_nombre'];
        $contacto = $_POST['prov_contacto'];
        $telefono = $_POST['prov_telefono'];
        $email = $_POST['prov_correo'];
        $direccion = $_POST['prov_direccion'];
        
        $resultado = agregar_proveedor($nombre, $contacto, $telefono, $email, $direccion, $conexion);
        if ($resultado) {
            header('Location: proveedores.php?mensaje=Proveedor registrado con éxito');
            exit();
        } else {
            echo "Error al registrar proveedor";
        }
    }
?>

<div id="page-content-wrapper">
    <div class="container mt-5">
        <h1 class="mb-4">Proveedores</h1>

        <div class="mb-3">
            <a href="agregar-proveedor.php" class="btn btn-success mb-3">Agregar Proveedor</a>
        </div>

        <!-- Tabla de Clientes -->
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Contacto</th>
                    <th>Teléfono</th>
                    <th>Email</th>
                    <th>Dirección</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($proveedor = mysqli_fetch_assoc($proveedores)): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($proveedor['prov_nombre']); ?></td>
                        <td><?php echo htmlspecialchars($proveedor['prov_contacto']); ?></td>
                        <td><?php echo htmlspecialchars($proveedor['prov_telefono']); ?></td>
                        <td><?php echo htmlspecialchars($proveedor['prov_correo']); ?></td>
                        <td><?php echo htmlspecialchars($proveedor['prov_direccion']); ?></td>
                        <td>
                            <a href="editar-proveedor.php?id=<?php echo $proveedor['prov_id']; ?>" class="btn btn-warning btn-sm">Editar</a>
                            <a href="eliminar-proveedor.php?id=<?php echo $proveedor['prov_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de eliminar este proveedor?');">Eliminar</a>
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