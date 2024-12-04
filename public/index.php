<?php
    session_start();
    if (isset($_SESSION['usuario'])) {
        header('Location: ../modules/usuarios/panel.php');
    } else {
        header('Location: ../modules/usuarios/login.php');
    }
    exit();
?>
