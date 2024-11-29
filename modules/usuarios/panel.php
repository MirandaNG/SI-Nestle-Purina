<?php
    session_start();
    if (!isset($_SESSION['usuario'])) {
        header('Location: login.php');
        exit();
    }    
    $usuario = $_SESSION['usuario'];
    $rol_id = $_SESSION['rol_id'];
    $permisos = $_SESSION['permisos'];
    
    include '../../config/conexion.php';
    include '../../includes/header.php';
?>

<div id="page-content-wrapper" class="container">
    <h1 class="mt-5">Bienvenido al Panel de Control</h1>
    
    <!-- Tarjetas del Dashboard -->
    <div class="row">
        <!-- Card para Ventas y CRM -->
        <div class="col-sm-3 mb-3">
            <div class="card shadow border-0 card-ventas">
                <a href="../ventas/ventas.php" class="card-body d-flex align-items-center justify-content-start text-decoration-none">
                    <img src="../../assets/images/ventas.png" alt="Ventas" class="me-3 img-fluid" width="40">
                    <span class="h5">Ventas y CRM</span>
                </a>
            </div>
        </div>

        <!-- Card para Inventario -->
        <div class="col-sm-3 mb-3">
            <div class="card shadow border-0 card-inventarios">
                <a href="../inventarios/inventarios.php" class="card-body d-flex align-items-center justify-content-start text-decoration-none">
                    <img src="../../assets/images/inventarios.png" alt="Inventarios" class="me-3 img-fluid" width="40">
                    <span class="h5">Inventario</span>
                </a>
            </div>
        </div>

        <!-- Card para Productos -->
        <div class="col-sm-3 mb-3">
            <div class="card shadow border-0 card-productos">
                <a href="../modules/productos/productos.php" class="card-body d-flex align-items-center justify-content-start text-decoration-none">
                    <img src="../../assets/images/products.png" alt="Productos" class="me-3 img-fluid" width="40">
                    <span class="h5">Productos</span>
                </a>
            </div>
        </div>

        <!-- Card para Proveedores -->
        <div class="col-sm-3 mb-3">
            <div class="card shadow border-0 card-proveedores">
                <a href="../modules/proveedores/proveedores.php" class="card-body d-flex align-items-center justify-content-start text-decoration-none">
                    <img src="../../assets/images/proveedores.png" alt="Proveedores" class="me-3 img-fluid" width="40">
                    <span class="h5">Proveedores</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Verificar permisos para Clientes -->
    <?php if (isset($permisos['Clientes']) && in_array('lectura', $permisos['Clientes'])): ?>
        <a href="../modules/clientes/clientes.php" class="btn btn-primary">Ver Clientes</a>
    <?php endif; ?>

    <?php if (isset($permisos['Clientes']) && in_array('escritura', $permisos['Clientes'])): ?>
        <a href="../modules/clientes/editar_clientes.php" class="btn btn-secondary">Editar Clientes</a>
    <?php endif; ?>
</div>

<?php
    include '../../includes/footer.php';
?>