<?php
    session_start();
    if (isset($_SESSION['usuario'])) {
        header('Location: views/panel.php');
    } else {
        header('Location: views/login.php');
    }
    exit();
?>
