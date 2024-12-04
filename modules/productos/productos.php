<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: ../usuarios/login.php');
    exit();
}

include '../../config/conexion.php';
include '../../includes/header.php';
include '../../functions/CRUD/productos_functions.php';

// Obtener los productos
$productos = obtener_productos($conexion);
?>

<div id="page-content-wrapper">
    <div class="container mt-5">
        <h1 class="mb-4">Productos</h1>
        <a href="agregar-producto.php" class="btn btn-success mb-3">Agregar Producto</a>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Precio</th>
                    <th>Especie</th>
                    <th>Tipo Comida</th>
                    <th>Etapa Vida</th>
                    <th>Marca</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($producto = $productos->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $producto['prod_nombre']; ?></td>
                        <td><?php echo $producto['prod_precio']; ?></td>
                        <td><?php echo $producto['especie']; ?></td>
                        <td><?php echo $producto['tipo_comida']; ?></td>
                        <td><?php echo $producto['etapa_vida']; ?></td>
                        <td><?php echo $producto['prod_marca']; ?></td>
                        <td>
                            <a href="ver-producto.php?id=<?php echo $producto['prod_id']; ?>" class="btn btn-info">Ver</a>
                            <a href="editar-producto.php?id=<?php echo $producto['prod_id']; ?>" class="btn btn-warning">Editar</a>
                            <a href="eliminar-producto.php?id=<?php echo $producto['prod_id']; ?>" class="btn btn-danger" onclick="return confirm('¿Estás seguro de eliminar este producto?')">Eliminar</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>