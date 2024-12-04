<?php
// Este archivo contiene las funciones relacionadas con la gestión de proveedores

// Función para registrar un nuevo proveedor
function registrar_proveedor($prov_nombre, $prov_direccion, $prov_telefono, $prov_email, $prov_estado, $conexion) {
    $query = "INSERT INTO proveedores (prov_nombre, prov_direccion, prov_telefono, prov_email, prov_estado) 
              VALUES (?, ?, ?, ?, ?)";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param('sssss', $prov_nombre, $prov_direccion, $prov_telefono, $prov_email, $prov_estado);
    return $stmt->execute() ? "Proveedor registrado exitosamente." : "Error al registrar el proveedor: " . $conexion->error;
}

// Función para obtener todos los proveedores
function obtener_proveedores($conexion) {
    $query = "SELECT * FROM proveedores";
    return $conexion->query($query);
}

// Función para obtener un proveedor por su ID
function obtener_proveedor_por_id($prov_id, $conexion) {
    $query = "SELECT * FROM proveedores WHERE prov_id = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param('i', $prov_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

// Función para actualizar un proveedor
function actualizar_proveedor($prov_id, $prov_nombre, $prov_direccion, $prov_telefono, $prov_email, $prov_estado, $conexion) {
    $query = "UPDATE proveedores SET prov_nombre = ?, prov_direccion = ?, prov_telefono = ?, prov_email = ?, prov_estado = ? 
              WHERE prov_id = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param('sssssi', $prov_nombre, $prov_direccion, $prov_telefono, $prov_email, $prov_estado, $prov_id);
    return $stmt->execute() ? "Proveedor actualizado exitosamente." : "Error al actualizar el proveedor: " . $conexion->error;
}

// Función para eliminar un proveedor
function eliminar_proveedor($prov_id, $conexion) {
    $query = "DELETE FROM proveedores WHERE prov_id = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param('i', $prov_id);
    return $stmt->execute() ? "Proveedor eliminado exitosamente." : "Error al eliminar el proveedor: " . $conexion->error;
}
?>
