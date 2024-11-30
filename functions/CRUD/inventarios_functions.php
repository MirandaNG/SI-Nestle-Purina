<?php
// Este archivo contiene las funciones relacionadas con la gestión de inventarios (Productos, Entradas y Salidas)

// Obtener todos los productos en el inventario (materia prima y productos terminados)
function obtener_inventario($conexion) {
    $query = "SELECT * FROM inventario";
    return $conexion->query($query);
}

// Agregar un nuevo producto o materia prima al inventario
function agregar_producto_inventario($nombre, $descripcion, $tipo, $cantidad_actual, $ubicacion, $precio_unitario, $conexion) {
    $query = "INSERT INTO inventario (inv_nombre, inv_descripcion, inv_tipo, inv_cantidad_actual, inv_ubicacion, inv_precio_unitario) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param('sssisi', $nombre, $descripcion, $tipo, $cantidad_actual, $ubicacion, $precio_unitario);
    return $stmt->execute();
}

// Actualizar un producto o materia prima del inventario
function actualizar_producto_inventario($id, $nombre, $descripcion, $tipo, $cantidad_actual, $ubicacion, $precio_unitario, $conexion) {
    $query = "UPDATE inventario SET inv_nombre = ?, inv_descripcion = ?, inv_tipo = ?, inv_cantidad_actual = ?, inv_ubicacion = ?, inv_precio_unitario = ? WHERE inv_id = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param('sssisii', $nombre, $descripcion, $tipo, $cantidad_actual, $ubicacion, $precio_unitario, $id);
    return $stmt->execute();
}

// Eliminar un producto del inventario
function eliminar_producto_inventario($id, $conexion) {
    $query = "DELETE FROM inventario WHERE inv_id = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param('i', $id);
    return $stmt->execute();
}

// Registrar una entrada de inventario y actualizar el stock
function registrar_entrada_inventario($inv_id, $cantidad, $fecha, $proveedor, $motivo, $conexion) {
    // Agregar la entrada al historial
    $query_entrada = "INSERT INTO inventario_entradas (inv_id, inv_entra_cantidad, inv_entra_fecha, inv_entra_proveedor, inv_entra_motivo) VALUES (?, ?, ?, ?, ?)";
    $stmt_entrada = $conexion->prepare($query_entrada);
    $stmt_entrada->bind_param('iisss', $inv_id, $cantidad, $fecha, $proveedor, $motivo);
    
    if (!$stmt_entrada->execute()) {
        return "Error al registrar la entrada: " . $conexion->error;
    }

    // Actualizar el stock del inventario
    $query_actualizar_stock = "UPDATE inventario SET inv_cantidad_actual = inv_cantidad_actual + ? WHERE inv_id = ?";
    $stmt_stock = $conexion->prepare($query_actualizar_stock);
    $stmt_stock->bind_param('ii', $cantidad, $inv_id);
    return $stmt_stock->execute() ? "Entrada registrada y stock actualizado." : "Error al actualizar el stock: " . $conexion->error;
}

// Registrar una salida de inventario y actualizar el stock
function registrar_salida_inventario($inv_id, $cantidad, $fecha, $destino, $motivo, $conexion) {
    // Agregar la salida al historial
    $query_salida = "INSERT INTO inventario_salidas (inv_id, inv_sal_cantidad, inv_sal_fecha, inv_sal_destino, inv_sal_motivo) VALUES (?, ?, ?, ?, ?)";
    $stmt_salida = $conexion->prepare($query_salida);
    $stmt_salida->bind_param('iisss', $inv_id, $cantidad, $fecha, $destino, $motivo);
    
    if (!$stmt_salida->execute()) {
        return "Error al registrar la salida: " . $conexion->error;
    }

    // Actualizar el stock del inventario
    $query_actualizar_stock = "UPDATE inventario SET inv_cantidad_actual = inv_cantidad_actual - ? WHERE inv_id = ?";
    $stmt_stock = $conexion->prepare($query_actualizar_stock);
    $stmt_stock->bind_param('ii', $cantidad, $inv_id);
    return $stmt_stock->execute() ? "Salida registrada y stock actualizado." : "Error al actualizar el stock: " . $conexion->error;
}

// Eliminar una entrada de inventario y ajustar el stock
function eliminar_entrada_inventario($inv_entra_id, $conexion) {
    // Obtener información de la entrada
    $query_info = "SELECT inv_id, inv_entra_cantidad FROM inventario_entradas WHERE inv_entra_id = ?";
    $stmt_info = $conexion->prepare($query_info);
    $stmt_info->bind_param('i', $inv_entra_id);
    $stmt_info->execute();
    $entrada = $stmt_info->get_result()->fetch_assoc();

    if (!$entrada) {
        return "Error: Entrada no encontrada.";
    }

    // Ajustar el stock del inventario
    $query_ajustar_stock = "UPDATE inventario SET inv_cantidad_actual = inv_cantidad_actual - ? WHERE inv_id = ?";
    $stmt_ajustar = $conexion->prepare($query_ajustar_stock);
    $stmt_ajustar->bind_param('ii', $entrada['inv_entra_cantidad'], $entrada['inv_id']);
    
    if (!$stmt_ajustar->execute()) {
        return "Error al ajustar el stock: " . $conexion->error;
    }

    // Eliminar la entrada
    $query_eliminar = "DELETE FROM inventario_entradas WHERE inv_entra_id = ?";
    $stmt_eliminar = $conexion->prepare($query_eliminar);
    $stmt_eliminar->bind_param('i', $inv_entra_id);
    return $stmt_eliminar->execute() ? "Entrada eliminada y stock ajustado." : "Error al eliminar la entrada: " . $conexion->error;
}

// Eliminar una salida de inventario y ajustar el stock
function eliminar_salida_inventario($inv_sal_id, $conexion) {
    // Obtener información de la salida
    $query_info = "SELECT inv_id, inv_sal_cantidad FROM inventario_salidas WHERE inv_sal_id = ?";
    $stmt_info = $conexion->prepare($query_info);
    $stmt_info->bind_param('i', $inv_sal_id);
    $stmt_info->execute();
    $salida = $stmt_info->get_result()->fetch_assoc();

    if (!$salida) {
        return "Error: Salida no encontrada.";
    }

    // Ajustar el stock del inventario
    $query_ajustar_stock = "UPDATE inventario SET inv_cantidad_actual = inv_cantidad_actual + ? WHERE inv_id = ?";
    $stmt_ajustar = $conexion->prepare($query_ajustar_stock);
    $stmt_ajustar->bind_param('ii', $salida['inv_sal_cantidad'], $salida['inv_id']);
    
    if (!$stmt_ajustar->execute()) {
        return "Error al ajustar el stock: " . $conexion->error;
    }

    // Eliminar la salida
    $query_eliminar = "DELETE FROM inventario_salidas WHERE inv_sal_id = ?";
    $stmt_eliminar = $conexion->prepare($query_eliminar);
    $stmt_eliminar->bind_param('i', $inv_sal_id);
    return $stmt_eliminar->execute() ? "Salida eliminada y stock ajustado." : "Error al eliminar la salida: " . $conexion->error;
}
?>