<?php
    session_start();
    if (!isset($_SESSION['usuario'])) {
        header('Location: login.php');
        exit();
    }    
    $usuario = $_SESSION['usuario'];
    $rol_id = $_SESSION['rol_id'];
    $permisos = $_SESSION['permisos'];
    
    include '../config/conexion.php';
    include '../includes/navbar.php';
    include '../includes/sidebar.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
    <title>Panel de Control</title>
</head>
<body>
    <div id="page-content-wrapper" class="container">
        <h1 class="mt-5">Bienvenido al Panel de Control</h1>

        <!-- Verificar si tiene permisos de lectura para Clientes -->
        <?php if (isset($permisos['Clientes']) && in_array('lectura', $permisos['Clientes'])): ?>
            <a href="clientes.php" class="btn btn-primary">Ver Clientes</a>
        <?php endif; ?>

        <!-- Verificar si tiene permisos de escritura para Clientes -->
        <?php if (isset($permisos['Clientes']) && in_array('escritura', $permisos['Clientes'])): ?>
            <a href="editar_clientes.php" class="btn btn-secondary">Editar Clientes</a>
        <?php endif; ?>

        <!-- Repite para otras secciones, como Productos, Inventario, etc. -->

    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../js/script.js"></script>
</body>
</html>