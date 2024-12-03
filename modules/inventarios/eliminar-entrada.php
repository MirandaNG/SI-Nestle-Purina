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

// Verificar si la entrada tiene transferencia asociada
$query = "SELECT transfe_id FROM inventario_entradas WHERE inv_entra_id = ?";
$stmt = $conexion->prepare($query);
$stmt->bind_param('i', $entrada_id);
$stmt->execute();
$resultado = $stmt->get_result()->fetch_assoc();

if (!is_null($resultado['transfe_id'])) {
    die("Error: No se puede eliminar esta entrada porque está asociada a una transferencia.");
}

// Obtener el ID de la entrada a eliminar
if (isset($_GET['id'])) {
    $entrada_id = $_GET['id'];
    $resultado = eliminar_entrada_inventario($entrada_id, $conexion);
    if ($resultado) {
        header('Location: entradas-inventario.php?mensaje=Entrada eliminada con éxito');
        exit();
    } else {
        echo "Error al eliminar la entrada.";
    }
} else {
    echo "ID de entrada no especificado.";
    exit();
}
?>

<?php
include '../../includes/footer.php';
?>
