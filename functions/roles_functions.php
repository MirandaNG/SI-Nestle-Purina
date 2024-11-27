<?php
// Este archivo contiene las funciones relacionadas con la gestión de usuarios

// Obtener los roles disponibles para un departamento específico
function obtener_roles_departamento($depa_id, $conexion) {
    $query = "SELECT rol_id, rol_nombre FROM roles WHERE depa_id = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param('i', $depa_id);
    $stmt->execute();
    return $stmt->get_result();
}
?>