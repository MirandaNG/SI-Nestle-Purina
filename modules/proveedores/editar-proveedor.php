<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: ../usuarios/login.php');
    exit();
}

$usuario = $_SESSION['usuario'];

include '../../config/conexion.php';
include '../../includes/header.php';
include '../../functions/CRUD/proveedores_functions.php';

// Obtener el ID a editar
if (isset($_GET['id'])) {
    $proveedor_id = $_GET['id'];
    $proveedor = obtener_proveedor_por_id($proveedor_id, $conexion);
    if (!$proveedor) {
        echo "Proveedor no encontrado.";
        exit();
    }
} else {
    echo "ID de proveedor no especificado.";
    exit();
}

// Si el usuario envía el formulario para editar
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['editar_proveedor'])) {
    $nombre = $_POST['prov_nombre'];
    $contacto = $_POST['prov_contacto'];
    $telefono = $_POST['prov_telefono'];
    $email = $_POST['prov_correo'];
    $direccion = $_POST['prov_direccion'];

    $resultado = editar_proveedor($proveedor_id, $nombre, $contacto, $telefono, $email, $direccion, $conexion);
    if ($resultado) {
        header('Location: proveedores.php?mensaje=Proveedor actualizado con éxito');
        exit();
    } else {
        echo "Error al actualizar proveedor.";
    }
}
?>

<div id="page-content-wrapper">
    <div class="container mt-5">
        <h1 class="mb-4">Editar Proveedor</h1>
        <form method="POST">
            <!-- Campo Nombre -->
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" id="nombre" class="form-control" value="<?php echo htmlspecialchars($proveedor['prov_nombre']); ?>" disabled>
                <!-- Campo oculto para enviar el valor del nombre -->
                <input type="hidden" name="prov_nombre" value="<?php echo htmlspecialchars($proveedor['prov_nombre']); ?>">
            </div>
            <!-- Campo Contacto -->
            <div class="mb-3">
                <label for="contacto" class="form-label">Contacto</label>
                <input type="text" id="contacto" name="prov_contacto" class="form-control" value="<?php echo htmlspecialchars($proveedor['prov_contacto']); ?>" required>
            </div>
            <!-- Campo Teléfono -->
            <div class="mb-3">
                <label for="telefono" class="form-label">Teléfono</label>
                <input type="text" id="telefono" name="prov_telefono" class="form-control" value="<?php echo htmlspecialchars($proveedor['prov_telefono']); ?>" required>
            </div>
            <!-- Campo Email -->
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" id="email" name="prov_correo" class="form-control" value="<?php echo htmlspecialchars($proveedor['prov_correo']); ?>" required>
            </div>
            <!-- Campo Dirección -->
            <div class="mb-3">
                <label for="direccion" class="form-label">Dirección</label>
                <input type="text" id="direccion" name="prov_direccion" class="form-control" value="<?php echo htmlspecialchars($proveedor['prov_direccion']); ?>" required>
            </div>

            <!-- Botón de enviar -->
            <button type="submit" name="editar_proveedor" class="btn btn-warning">Actualizar Proveedor</button>
            <a href="proveedores.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>