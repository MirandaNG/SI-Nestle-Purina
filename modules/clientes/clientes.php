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
    include '../../functions/clientes_functions.php';

    // Llamada para obtener la lista de clientes
    $clientes = obtener_clientes($conexion);
    // Si el usuario envía el formulario para crear un cliente
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['crear_cliente'])) {
        $clt_nombre = $_POST['clt_nombre'];
        $clt_direccion = $_POST['clt_direccion'];
        $clt_telefono = $_POST['clt_telefono'];
        $clt_email = $_POST['clt_email'];
        $clt_tipo = $_POST['clt_tipo'];

        $resultado = crear_cliente($clt_nombre, $clt_direccion, $clt_telefono, $clt_email, $clt_tipo, $conexion);
        if ($resultado) {
            echo "Cliente creado con éxito";
        } else {
            echo "Error al crear cliente";
        }
    }
?>

<div id="page-content-wrapper">
    <div class="container mt-5">
        <h1 class="mb-4">Clientes</h1>

        <!-- Verificar permisos para agregar clientes -->
        <?php if (isset($permisos['Clientes']) && in_array('crear', $permisos['Clientes'])): ?>
            <a href="agregar-cliente.php" class="btn btn-success mb-3">Agregar Cliente</a>
        <?php endif; ?>

        <!-- Tabla de Clientes -->
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Dirección</th>
                    <th>Teléfono</th>
                    <th>Correo</th>
                    <th>Tipo</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($cliente = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($cliente['clt_nombre']); ?></td>
                        <td><?php echo htmlspecialchars($cliente['clt_direccion']); ?></td>
                        <td><?php echo htmlspecialchars($cliente['clt_telefono']); ?></td>
                        <td><?php echo htmlspecialchars($cliente['clt_correo']); ?></td>
                        <td><?php echo htmlspecialchars($cliente['clt_tipo']); ?></td>
                        <td>
                            <!-- Acciones según permisos -->
                            <?php if (isset($permisos['Clientes']) && in_array('ver', $permisos['Clientes'])): ?>
                                <a href="ver-cliente.php?id=<?php echo $cliente['id']; ?>" class="btn btn-warning btn-sm">Ver</a>
                            <?php endif; ?>
                            <?php if (isset($permisos['Clientes']) && in_array('editar', $permisos['Clientes'])): ?>
                                <a href="editar-cliente.php?id=<?php echo $cliente['id']; ?>" class="btn btn-warning btn-sm">Editar</a>
                            <?php endif; ?>
                            <?php if (isset($permisos['Clientes']) && in_array('eliminar', $permisos['Clientes'])): ?>
                                <a href="eliminar-cliente.php?id=<?php echo $cliente['id']; ?>" class="btn btn-danger btn-sm">Eliminar</a>
                            <?php endif; ?>
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