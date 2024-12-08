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
?>

<div id="page-content-wrapper" class="container">
    <h1 class="mt-5">Inventario</h1>
    
    <!-- Tarjetas del Dashboard -->
    <div class="row">
    <!-- Card para Control de Stock -->
        <div class="col-sm-3 mb-3">
            <div class="card shadow border-0 card-uno">
                <a href="stock-inventario.php" class="card-body d-flex align-items-center justify-content-start text-decoration-none">
                    <img src="../../assets/images/productos.png" alt="Stock" class="me-3 img-fluid" width="40">
                    <span class="h5">Control de Stock</span>
                </a>
            </div>
        </div>

        <!-- Card para Entradas -->
        <div class="col-sm-3 mb-3">
            <div class="card shadow border-0 card-siete">
                <a href="entradas-inventario.php" class="card-body d-flex align-items-center justify-content-start text-decoration-none">
                    <img src="../../assets/images/entradas.png" alt="Entradas" class="me-3 img-fluid" width="40">
                    <span class="h5">Entradas</span>
                </a>
            </div>
        </div>

        <!-- Card para Salidas -->
        <div class="col-sm-3 mb-3">
            <div class="card shadow border-0 card-cinco">
                <a href="salidas-inventario.php" class="card-body d-flex align-items-center justify-content-start text-decoration-none">
                    <img src="../../assets/images/salidas.png" alt="Salidas" class="me-3 img-fluid" width="40">
                    <span class="h5">Salidas</span>
                </a>
            </div>
        </div>

        <!-- Card para Gestión de Almacenes -->
        <div class="col-sm-3 mb-3">
            <div class="card shadow border-0 card-seis">
                <a href="../ubicaciones/ubicaciones.php" class="card-body d-flex align-items-center justify-content-start text-decoration-none">
                    <img src="../../assets/images/almacen-01.png" alt="Ubicaciones" class="me-3 img-fluid" width="40">
                    <span class="h5">Gestión de Almacenes</span>
                </a>
            </div>
        </div>

        <!-- Card para Seguimiento de Productos -->
        <div class="col-sm-3 mb-3">
            <div class="card shadow border-0 card-tres">
                <a href="../transferencias/transferencias.php" class="card-body d-flex align-items-center justify-content-start text-decoration-none">
                    <img src="../../assets/images/transportes.png" alt="Transferencias" class="me-3 img-fluid" width="40">
                    <span class="h5">Seguimiento de Productos</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Verificar permisos para  -->
</div>

<?php
    include '../../includes/footer.php';
?>