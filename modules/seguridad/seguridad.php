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
    <h1 class="mt-5">Log y Seguridad</h1>
    
    <!-- Tarjetas del Dashboard -->
    <div class="row">
        <!-- Card para Proveedores -->
        <div class="col-sm-3 mb-3">
            <div class="card shadow border-0 card-clientes">
                <a href="../proveedores/proveedores.php" class="card-body d-flex align-items-center justify-content-start text-decoration-none">
                    <img src="../../assets/images/proveedores.png" alt="Proveedores" class="me-3 img-fluid" width="40">
                    <span class="h5">Proveedores</span>
                </a>
            </div>
        </div>
    </div>
</div>

<?php
    include '../../includes/footer.php';
?>