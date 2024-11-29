<?php
// Función para crear un cliente
function crear_cliente($clt_nombre, $clt_direccion, $clt_telefono, $clt_email, $clt_tipo, $conexion) {
    $query = "INSERT INTO clientes (clt_nombre, clt_direccion, clt_telefono, clt_email, clt_tipo) 
              VALUES (?, ?, ?, ?, ?)";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param('sssss', $clt_nombre, $clt_direccion, $clt_telefono, $clt_email, $clt_tipo);
    return $stmt->execute();
}

// Función para editar un cliente
function editar_cliente($clt_id, $clt_nombre, $clt_direccion, $clt_telefono, $clt_email, $clt_tipo, $conexion) {
    $query = "UPDATE clientes SET clt_nombre = ?, clt_direccion = ?, clt_telefono = ?, clt_email = ?, clt_tipo = ? 
              WHERE clt_id = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param('sssssi', $clt_nombre, $clt_direccion, $clt_telefono, $clt_email, $clt_tipo, $clt_id);
    return $stmt->execute();
}

// Función para eliminar un cliente
function eliminar_cliente($clt_id, $conexion) {
    $query = "DELETE FROM clientes WHERE clt_id = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param('i', $clt_id);
    return $stmt->execute();
}

// Función para obtener los detalles de un cliente
function obtener_cliente($clt_id, $conexion) {
    $query = "SELECT * FROM clientes WHERE clt_id = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param('i', $clt_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

// Función para listar todos los clientes
function obtener_clientes($conexion) {
    $query = "SELECT clt_id, clt_nombre, clt_direccion, clt_telefono, clt_email, clt_tipo FROM clientes";
    return $conexion->query($query);
}
?>