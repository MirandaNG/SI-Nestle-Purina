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

// Obtener todos los roles
function obtener_roles($conexion) {
    $query = "SELECT * FROM roles";
    return $conexion->query($query);
}

// Obtener un rol específico (opcional)
function obtener_rol_por_id($id, $conexion) {
    $query = "SELECT * FROM roles WHERE rol_id = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

?>