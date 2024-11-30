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

// Función para registrar un nuevo empleado
function registrar_empleado($empl_nombre, $empl_apellido, $empl_puesto, $depa_id, $empl_salario, $empl_fecha_contrat, $empl_estado, $conexion) {
    $query = "INSERT INTO empleados (empl_nombre, empl_apellido, empl_puesto, depa_id, empl_salario, empl_fecha_contrat, empl_estado) 
              VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param('sssiiss', $empl_nombre, $empl_apellido, $empl_puesto, $depa_id, $empl_salario, $empl_fecha_contrat, $empl_estado);
    return $stmt->execute() ? "Empleado creado exitosamente." : "Error al registrar el empleado: " . $conexion->error;
}

// Función para obtener todos los empleados
function obtener_empleados($conexion) {
    $query = "SELECT * FROM empleados";
    return $conexion->query($query);
}

// Función para obtener un empleado por su ID
function obtener_empleado_por_id($empl_id, $conexion) {
    $query = "SELECT * FROM empleados WHERE empl_id = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param('i', $empl_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

// Función para actualizar un empleado
function actualizar_empleado($empl_id, $empl_nombre, $empl_apellido, $empl_puesto, $depa_id, $empl_salario, $empl_fecha_contrat, $empl_estado, $conexion) {
    $query = "UPDATE empleados SET empl_nombre = ?, empl_apellido = ?, empl_puesto = ?, depa_id = ?, empl_salario = ?, empl_fecha_contrat = ?, empl_estado = ? WHERE empl_id = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param('sssiissi', $empl_nombre, $empl_apellido, $empl_puesto, $depa_id, $empl_salario, $empl_fecha_contrat, $empl_estado, $empl_id);
    return $stmt->execute() ? "Empleado actualizado exitosamente." : "Error al actualizar el empleado: " . $conexion->error;
}

// Función para eliminar un empleado
function eliminar_empleado($empl_id, $conexion) {
    $query = "DELETE FROM empleados WHERE empl_id = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param('i', $empl_id);
    return $stmt->execute() ? "Empleado eliminado exitosamente." : "Error al eliminar el empleado: " . $conexion->error;
}

?>