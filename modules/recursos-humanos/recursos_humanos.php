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
    <h1 class="mt-5">Recursos Humanos</h1>
    
    <!-- Tarjetas del Dashboard -->
    <div class="row">
        <!-- Card para Empleados -->
        <div class="col-sm-3 mb-3">
            <div class="card shadow border-0 card-seis">
                <a href="../empleados/empleados.php" class="card-body d-flex align-items-center justify-content-start text-decoration-none">
                    <img src="../../assets/images/empleados.png" alt="Empleados" class="me-3 img-fluid" width="40">
                    <span class="h5">Empleados</span>
                </a>
            </div>
        </div>

        <!-- Card para Asistencias -->
        <div class="col-sm-3 mb-3">
            <div class="card shadow border-0 card-nueve">
                <a href="asistencias.php" class="card-body d-flex align-items-center justify-content-start text-decoration-none">
                    <img src="../../assets/images/asistencias.png" alt="Asistencias" class="me-3 img-fluid" width="40">
                    <span class="h5">Asistencias</span>
                </a>
            </div>
        </div>

        <!-- Card para Nómina -->
        <div class="col-sm-3 mb-3">
            <div class="card shadow border-0 card-diez">
                <a href="nominas.php" class="card-body d-flex align-items-center justify-content-start text-decoration-none">
                    <img src="../../assets/images/nominas.png" alt="Nomina" class="me-3 img-fluid" width="40">
                    <span class="h5">Nómina</span>
                </a>
            </div>
        </div>

        <!-- Card para Evaluaciones -->
        <div class="col-sm-3 mb-3">
            <div class="card shadow border-0 card-ocho">
                <a href="evaluaciones.php" class="card-body d-flex align-items-center justify-content-start text-decoration-none">
                    <img src="../../assets/images/evaluaciones.png" alt="Evaluaciones" class="me-3 img-fluid" width="40">
                    <span class="h5">Evaluaciones</span>
                </a>
            </div>
        </div>
    </div>
</div>

<?php
    include '../../includes/footer.php';
?>