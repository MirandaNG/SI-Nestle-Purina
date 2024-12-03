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
    include '../../functions/CRUD/clientes_functions.php';

    // Llamada para obtener la lista de clientes
    $clientes = obtener_clientes($conexion);

    // Si el usuario envía el formulario para crear un cliente
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['crear_cliente'])) {
        $nombre = $_POST['clt_nombre'];
        $contacto = $_POST['clt_contacto'];
        $direccion = $_POST['clt_direccion'];
        $telefono = $_POST['clt_telefono'];
        $email = $_POST['clt_email'];
        $tipo = $_POST['clt_tipo'];

        $resultado = agregar_cliente($nombre, $contacto, $direccion, $telefono, $email, $tipo, $conexion);
        if ($resultado) {
            header('Location: clientes.php?mensaje=Cliente registrado con éxito');
            exit();
        } else {
            echo "Error al registrar cliente";
        }
    }
?>

<div id="page-content-wrapper">
    <div class="container mt-5">
        <h1 class="mb-4">Clientes</h1>

        <div class="mb-3">
            <a href="agregar-cliente.php" class="btn btn-success mb-3">Agregar Cliente</a>
        </div>

        <!-- Tabla de Clientes -->
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Contacto</th>
                    <th>Dirección</th>
                    <th>Teléfono</th>
                    <th>Email</th>
                    <th>Tipo</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($cliente = mysqli_fetch_assoc($clientes)): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($cliente['clt_nombre']); ?></td>
                        <td><?php echo htmlspecialchars($cliente['clt_contacto']); ?></td>
                        <td><?php echo htmlspecialchars($cliente['clt_direccion']); ?></td>
                        <td><?php echo htmlspecialchars($cliente['clt_telefono']); ?></td>
                        <td><?php echo htmlspecialchars($cliente['clt_email']); ?></td>
                        <td><?php echo htmlspecialchars($cliente['clt_tipo']); ?></td>
                        <td>
                            <a href="ver-cliente.php?id=<?php echo $cliente['clt_id']; ?>" class="btn btn-primary btn-sm">Ver</a>
                            <a href="editar-cliente.php?id=<?php echo $cliente['clt_id']; ?>" class="btn btn-warning btn-sm">Editar</a>
                            <a href="eliminar-cliente.php?id=<?php echo $cliente['clt_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de eliminar este cliente?');">Eliminar</a>
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