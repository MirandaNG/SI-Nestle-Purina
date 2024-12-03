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
    <h1 class="mt-5">Ventas y CRM</h1>
    
    <!-- Tarjetas del Dashboard -->
    <div class="row">
        <!-- Card para Clientes -->
        <div class="col-sm-3 mb-3">
            <div class="card shadow border-0 card-tres">
                <a href="../clientes/clientes.php" class="card-body d-flex align-items-center justify-content-start text-decoration-none">
                    <img src="../../assets/images/clientes.png" alt="Clientes" class="me-3 img-fluid" width="40">
                    <span class="h5">Clientes</span>
                </a>
            </div>
        </div>

        <!-- Card para Pedidos -->
        <div class="col-sm-3 mb-3">
            <div class="card shadow border-0 card-ocho">
                <a href="../pedidos/pedidos.php" class="card-body d-flex align-items-center justify-content-start text-decoration-none">
                    <img src="../../assets/images/pedidos.png" alt="Pedidos" class="me-3 img-fluid" width="40">
                    <span class="h5">Pedidos</span>
                </a>
            </div>
        </div>

        <!-- Card para Soporte -->
        <div class="col-sm-3 mb-3">
            <div class="card shadow border-0 card-seis">
                <a href="../soporte/soporte.php" class="card-body d-flex align-items-center justify-content-start text-decoration-none">
                    <img src="../../assets/images/atencion-clientes.png" alt="Soporte" class="me-3 img-fluid" width="40">
                    <span class="h5">Soporte</span>
                </a>
            </div>
        </div>
    </div>
</div>

<?php
    include '../../includes/footer.php';
?>