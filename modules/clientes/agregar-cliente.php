<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: ../usuarios/login.php');
    exit();
}

$usuario = $_SESSION['usuario'];

include '../../config/conexion.php';
include '../../includes/header.php';
include '../../functions/CRUD/clientes_functions.php';

// Obtener las opciones del ENUM
$enum_opciones = obtener_opciones_enum($conexion, 'clientes', 'clt_tipo');

// Si el usuario envía el formulario para crear un cliente
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['crear_cliente'])) {
    $clt_nombre = $_POST['clt_nombre'];
    $clt_contacto = $_POST['clt_contacto'];
    $clt_direccion = $_POST['clt_direccion'];
    $clt_telefono = $_POST['clt_telefono'];
    $clt_email = $_POST['clt_email'];
    $clt_tipo = $_POST['clt_tipo'];

    $resultado = agregar_cliente($clt_nombre, $clt_contacto, $clt_direccion, $clt_telefono, $clt_email, $clt_tipo, $conexion);
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
        <h1 class="mb-4">Registrar Cliente</h1>
        <form method="POST">
            <div class="mb-3">
                <label for="clt_nombre" class="form-label">Nombre</label>
                <input type="text" id="clt_nombre" name="clt_nombre" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="clt_contacto" class="form-label">Contacto</label>
                <input type="text" id="clt_contacto" name="clt_contacto" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="clt_direccion" class="form-label">Dirección</label>
                <input type="text" id="clt_direccion" name="clt_direccion" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="clt_telefono" class="form-label">Telefono</label>
                <input type="text" id="clt_telefono" name="clt_telefono" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="clt_email" class="form-label">Email</label>
                <input type="email" id="clt_email" name="clt_email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="clt_tipo" class="form-label">Tipo</label>
                <select id="clt_tipo" name="clt_tipo" class="form-select" required>
                    <option value="">Seleccione una opción</option>
                    <?php foreach ($enum_opciones as $opcion): ?>
                        <option value="<?php echo htmlspecialchars($opcion); ?>">
                            <?php echo htmlspecialchars($opcion); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" name="crear_cliente" class="btn btn-success">Registrar Cliente</button>
        </form>
    </div>
</div>

<?php
include '../../includes/footer.php';
?>