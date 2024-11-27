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

    // Consulta para obtener la lista de clientes
    $query = "SELECT * FROM clientes";
    $result = mysqli_query($conexion, $query);
?>

<div id="page-content-wrapper">
    <div class="container mt-5">
        <h1 class="mb-4">Clientes</h1>

        <!-- Verificar permisos para agregar clientes -->
        <?php if (isset($permisos['Clientes']) && in_array('crear', $permisos['Clientes'])): ?>
            <a href="agregar_cliente.php" class="btn btn-success mb-3">Agregar Cliente</a>
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
                            <?php if (isset($permisos['Clientes']) && in_array('editar', $permisos['Clientes'])): ?>
                                <a href="editar_cliente.php?id=<?php echo $cliente['id']; ?>" class="btn btn-warning btn-sm">Editar</a>
                            <?php endif; ?>
                            <?php if (isset($permisos['Clientes']) && in_array('eliminar', $permisos['Clientes'])): ?>
                                <a href="eliminar_cliente.php?id=<?php echo $cliente['id']; ?>" class="btn btn-danger btn-sm">Eliminar</a>
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