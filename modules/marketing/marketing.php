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
    <h1 class="mt-5">Marketing</h1>
    
    <!-- Tarjetas del Dashboard -->
    <div class="row">
        <!-- Card para Campañas de Marketing -->
        <div class="col-sm-3 mb-3">
            <div class="card shadow border-0 card-clientes">
                <a href="campanas.php" class="card-body d-flex align-items-center justify-content-start text-decoration-none">
                    <img src="../../assets/images/campanas.png" alt="Campanas" class="me-3 img-fluid" width="40">
                    <span class="h5">Campañas de Marketing</span>
                </a>
            </div>
        </div>

        <!-- Card para Resultados -->
        <div class="col-sm-3 mb-3">
            <div class="card shadow border-0 card-salidas">
                <a href="resultados.php" class="card-body d-flex align-items-center justify-content-start text-decoration-none">
                    <img src="../../assets/images/resultados.png" alt="Resultados" class="me-3 img-fluid" width="40">
                    <span class="h5">Resultados</span>
                </a>
            </div>
        </div>
    </div>
</div>

<?php
    include '../../includes/footer.php';
?>