<?php
// Este archivo contiene las funciones relacionadas con los proveedores

// Obtener todos los proveedores
function obtener_proveedores($conexion) {
    $query = "SELECT *
              FROM proveedores";
    $resultado = $conexion->query($query);

    if ($resultado) {
        return $resultado;
    } else {
        return false;
    }
}

// Obtener proveedor por id
function obtener_proveedor_por_id($proveedor_id, $conexion) {
    $query = "SELECT prov_id, prov_nombre, prov_contacto, prov_telefono, prov_correo, prov_direccion
              FROM proveedores 
              WHERE prov_id = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param('i', $proveedor_id);
    $stmt->execute();
    $resultado = $stmt->get_result();
    return $resultado->num_rows > 0 ? $resultado->fetch_assoc() : null;
}

// Agregar proveedor
function agregar_proveedor($nombre, $contacto, $telefono, $email, $direccion, $conexion) {
    $query = "INSERT INTO proveedores (prov_nombre, prov_contacto, prov_telefono, prov_correo, prov_direccion) 
              VALUES (?, ?, ?, ?, ?)";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param('sssss', $nombre, $contacto, $telefono, $email, $direccion);
    return $stmt->execute();
}

// Editar proveedor
function editar_proveedor($proveedor_id, $nombre, $contacto, $telefono, $email, $direccion, $conexion) {
    $query = "UPDATE proveedores 
              SET prov_nombre = ?, prov_contacto = ?, prov_telefono = ?, prov_correo = ?, prov_direccion = ?
              WHERE prov_id = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param('sssssi', $nombre, $contacto, $telefono, $email, $direccion, $proveedor_id);

    if ($stmt->execute()) {
        return true;
    } else {
        error_log("Error al actualizar al proveedor: " . $conexion->error);
        return false;
    }
}

// Eliminar proveedor
function eliminar_proveedor($proveedor_id, $conexion) {
    // Verificar si el proveedor tiene dependencias (por ejemplo, compras o facturas)
    $dependencias = verificar_dependencias_proveedor($proveedor_id, $conexion);

    if ($dependencias) {
        return false;
    }

    $query = "DELETE FROM proveedores WHERE prov_id = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param('i', $proveedor_id);

    if ($stmt->execute()) {
        return true;
    } else {
        error_log("Error al eliminar el proveedor: " . $conexion->error);
        return false;
    }
}

// Función para verificar si el proveedor tiene dependencias
function verificar_dependencias_proveedor($proveedor_id, $conexion) {
    $tablas_dependientes = ['ordenes_compra', 'facturas'];
    foreach ($tablas_dependientes as $tabla) {
        $query = "SELECT COUNT(*) AS total FROM $tabla WHERE prov_id = ?";
        $stmt = $conexion->prepare($query);
        $stmt->bind_param('i', $proveedor_id);
        $stmt->execute();
        $resultado = $stmt->get_result()->fetch_assoc();
        if ($resultado['total'] > 0) {
            return true;
        }
    }
    return false;
}

?>