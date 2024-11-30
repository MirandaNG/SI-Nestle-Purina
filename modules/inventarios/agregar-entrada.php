<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: ../usuarios/login.php');
    exit();
}

$usuario = $_SESSION['usuario'];

include '../../config/conexion.php';
include '../../includes/header.php';
include '../../functions/CRUD/inventarios_functions.php';

// Si el usuario envía el formulario para registrar una entrada
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['registrar_entrada'])) {
    $inv_id = $_POST['inv_id'];
    $cantidad = $_POST['cantidad'];
    $fecha = $_POST['fecha'];
    $proveedor = $_POST['proveedor'];
    $motivo = $_POST['motivo'];

    $resultado = registrar_entrada_inventario($inv_id, $cantidad, $fecha, $proveedor, $motivo, $conexion);
    if ($resultado) {
        header('Location: entradas-inventario.php?mensaje=Entrada registrada con éxito');
        exit();
    } else {
        echo "Error al registrar la entrada.";
    }
}
?>

<div id="page-content-wrapper">
    <div class="container mt-5">
        <h1 class="mb-4">Registrar Nueva Entrada</h1>
        <form method="POST">
            <div class="mb-3">
                <label for="inv_id" class="form-label">Producto</label>
                <select id="inv_id" name="inv_id" class="form-select" required>
                    <option value="">Seleccione un producto</option>
                    <?php
                    $productos = obtener_inventario($conexion); // Función para obtener productos disponibles
                    while ($producto = mysqli_fetch_assoc($productos)): ?>
                        <option value="<?php echo $producto['inv_id']; ?>"><?php echo $producto['inv_nombre']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="cantidad" class="form-label">Cantidad</label>
                <input type="number" id="cantidad" name="cantidad" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="fecha" class="form-label">Fecha</label>
                <input type="date" id="fecha" name="fecha" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="proveedor" class="form-label">Proveedor</label>
                <input type="text" id="proveedor" name="proveedor" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="motivo" class="form-label">Motivo</label>
                <input type="text" id="motivo" name="motivo" class="form-control" required>
            </div>
            <button type="submit" name="registrar_entrada" class="btn btn-success">Registrar Entrada</button>
        </form>
    </div>
</div>

<?php
include '../../includes/footer.php';
?>
