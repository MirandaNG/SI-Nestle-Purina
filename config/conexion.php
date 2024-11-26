<?php
    $servidor = 'localhost:3306';
    $usuario = 'root';
    $password = 'root';
    $bd = 'erp_purina';

    $conexion = new mysqli($servidor, $usuario, $password, $bd);

    if ($conexion->connect_error) {
        die('Conexion Fallida: ' . $conexion->connect_error);
    }
?>