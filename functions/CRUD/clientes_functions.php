<?php
// Este archivo contiene las funciones relacionadas con la gestión de clientes

// Función para registrar un nuevo cliente
function registrar_cliente($clt_nombre, $clt_direccion, $clt_telefono, $clt_email, $clt_tipo, $conexion) {
    $query = "INSERT INTO clientes (clt_nombre, clt_direccion, clt_telefono, clt_email, clt_tipo) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param('sssss', $clt_nombre, $clt_direccion, $clt_telefono, $clt_email, $clt_tipo);
    return $stmt->execute() ? "Cliente registrado exitosamente." : "Error al registrar el cliente: " . $conexion->error;
}

// Función para obtener todos los clientes
function obtener_clientes($conexion) {
    $query = "SELECT * FROM clientes";
    return $conexion->query($query);
}

// Función para obtener un cliente por su ID
function obtener_cliente_por_id($clt_id, $conexion) {
    $query = "SELECT * FROM clientes WHERE clt_id = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param('i', $clt_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

// Función para actualizar un cliente
function actualizar_cliente($clt_id, $clt_nombre, $clt_direccion, $clt_telefono, $clt_email, $clt_tipo, $conexion) {
    $query = "UPDATE clientes SET clt_nombre = ?, clt_direccion = ?, clt_telefono = ?, clt_email = ?, clt_tipo = ? WHERE clt_id = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param('sssssi', $clt_nombre, $clt_direccion, $clt_telefono, $clt_email, $clt_tipo, $clt_id);
    return $stmt->execute() ? "Cliente actualizado exitosamente." : "Error al actualizar el cliente: " . $conexion->error;
}

// Función para eliminar un cliente
function eliminar_cliente($clt_id, $conexion) {
    $query = "DELETE FROM clientes WHERE clt_id = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param('i', $clt_id);
    return $stmt->execute() ? "Cliente eliminado exitosamente." : "Error al eliminar el cliente: " . $conexion->error;
}
?>