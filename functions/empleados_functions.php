<?php
// Este archivo contiene las funciones relacionadas con la gestión de empleados

// Obtener empleado activo
function obtener_empleado_activo($nombre, $apellido, $depa_id, $conexion) {
    $query = "SELECT empl_id, empl_nombre, empl_apellido 
              FROM empleados 
              WHERE empl_nombre = ? AND empl_apellido = ? AND depa_id = ? AND empl_estado = 'activo'";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param('ssi', $nombre, $apellido, $depa_id);
    $stmt->execute();
    $resultado = $stmt->get_result();
    return $resultado->num_rows > 0 ? $resultado->fetch_assoc() : null;
}

?>