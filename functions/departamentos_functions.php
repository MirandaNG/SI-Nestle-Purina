<?php
// Este archivo contiene las funciones relacionadas con la gestión de departamentos

// Obtener departamentos
function obtener_departamentos($conexion) {
    $query = "SELECT depa_id, depa_nombre FROM Departamentos";
    return $conexion->query($query);
}

?>
