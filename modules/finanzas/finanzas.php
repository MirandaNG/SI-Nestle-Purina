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
    <h1 class="mt-5">Finanzas y Contabilidad</h1>
    
    <!-- Tarjetas del Dashboard -->
    <div class="row">
        <!-- Card para Facturas -->
        <div class="col-sm-3 mb-3">
            <div class="card shadow border-0 card-clientes">
                <a href="../facturas/facturas.php" class="card-body d-flex align-items-center justify-content-start text-decoration-none">
                    <img src="../../assets/images/facturas.png" alt="Facturas" class="me-3 img-fluid" width="40">
                    <span class="h5">Facturas</span>
                </a>
            </div>
        </div>

        <!-- Card para Pagos -->
        <div class="col-sm-3 mb-3">
            <div class="card shadow border-0 card-salidas">
                <a href="pagos.php" class="card-body d-flex align-items-center justify-content-start text-decoration-none">
                    <img src="../../assets/images/pagos.png" alt="Pagos" class="me-3 img-fluid" width="40">
                    <span class="h5">Pagos</span>
                </a>
            </div>
        </div>

        <!-- Card para Gastos -->
        <div class="col-sm-3 mb-3">
            <div class="card shadow border-0 card-productos">
                <a href="gastos.php" class="card-body d-flex align-items-center justify-content-start text-decoration-none">
                    <img src="../../assets/images/gastos.png" alt="Gastos" class="me-3 img-fluid" width="40">
                    <span class="h5">Gastos</span>
                </a>
            </div>
        </div>

        <!-- Card para Reportes -->
        <div class="col-sm-3 mb-3">
            <div class="card shadow border-0 card-proveedores">
                <a href="reportes.php" class="card-body d-flex align-items-center justify-content-start text-decoration-none">
                    <img src="../../assets/images/reportes.png" alt="Resportes" class="me-3 img-fluid" width="40">
                    <span class="h5">Reportes</span>
                </a>
            </div>
        </div>
    </div>
</div>

<?php
    include '../../includes/footer.php';
?>