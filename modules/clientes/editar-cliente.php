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

// Obtener el ID a editar
if (isset($_GET['id'])) {
    $cliente_id = $_GET['id'];
    $cliente = obtener_cliente_por_id($cliente_id, $conexion);
    if (!$cliente) {
        echo "Cliente no encontrado.";
        exit();
    }
} else {
    echo "ID de cliente no especificado.";
    exit();
}

// Si el usuario envía el formulario para editar
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['editar_cliente'])) {
    $nombre = $_POST['clt_nombre'];
    $contacto = $_POST['clt_contacto'];
    $direccion = $_POST['clt_direccion'];
    $telefono = $_POST['clt_telefono'];
    $email = $_POST['clt_email'];
    $tipo = $_POST['clt_tipo'];

    // Validar que el tipo seleccionado sea válido
    if (!in_array($tipo, $enum_opciones)) {
        die("Tipo de cliente no válido.");
    }

    $resultado = editar_cliente($cliente_id, $nombre, $contacto, $direccion, $telefono, $email, $tipo, $conexion);
    if ($resultado) {
        header('Location: clientes.php?mensaje=Cliente actualizado con éxito');
        exit();
    } else {
        echo "Error al actualizar cliente.";
    }
}
?>

<div id="page-content-wrapper">
    <div class="container mt-5">
        <h1 class="mb-4">Editar Cliente</h1>
        <form method="POST">
            <!-- Campo Nombre -->
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" id="nombre" class="form-control" value="<?php echo htmlspecialchars($cliente['clt_nombre']); ?>" disabled>
                <!-- Campo oculto para enviar el valor del nombre -->
                <input type="hidden" name="clt_nombre" value="<?php echo htmlspecialchars($cliente['clt_nombre']); ?>">
            </div>
            <!-- Campo Contacto -->
            <div class="mb-3">
                <label for="contacto" class="form-label">Contacto</label>
                <input type="text" id="contacto" name="clt_contacto" class="form-control" value="<?php echo htmlspecialchars($cliente['clt_contacto']); ?>" required>
            </div>
            <!-- Campo Dirección -->
            <div class="mb-3">
                <label for="direccion" class="form-label">Dirección</label>
                <input type="text" id="direccion" name="clt_direccion" class="form-control" value="<?php echo htmlspecialchars($cliente['clt_direccion']); ?>" required>
            </div>
            <!-- Campo Teléfono -->
            <div class="mb-3">
                <label for="telefono" class="form-label">Teléfono</label>
                <input type="text" id="telefono" name="clt_telefono" class="form-control" value="<?php echo htmlspecialchars($cliente['clt_telefono']); ?>" required>
            </div>
            <!-- Campo Email -->
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" id="email" name="clt_email" class="form-control" value="<?php echo htmlspecialchars($cliente['clt_email']); ?>" required>
            </div>
            <!-- Campo Tipo -->
            <div class="mb-3">
                <label for="tipo" class="form-label">Tipo</label>
                <select id="tipo" name="clt_tipo" class="form-select" required>
                    <option value="">Seleccione una opción</option>
                    <?php foreach ($enum_opciones as $opcion): ?>
                        <option value="<?php echo htmlspecialchars($opcion); ?>" 
                            <?php echo ($cliente['clt_tipo'] === $opcion) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($opcion); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <!-- Botón de enviar -->
            <button type="submit" name="editar_cliente" class="btn btn-warning">Actualizar Cliente</button>
        </form>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>
