<?php
// Este archivo contiene las funciones relacionadas con la gestión de inventarios (Productos, Entradas y Salidas)

// Obtener todos los productos en el inventario (materia prima y productos terminados)
function obtener_inventario($conexion) {
    $query = "SELECT * FROM inventario";
    return $conexion->query($query);
}

// Obtener motivos filtrados por la opción (1 = entradas, 2 = salidas)
function obtener_motivos_por_opcion($conexion, $opcion) {
    $query = "SELECT * FROM motivos WHERE motivo_opcion = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param('i', $opcion);
    $stmt->execute();
    return $stmt->get_result();
}

// Agregar un nuevo producto o materia prima al inventario
function agregar_producto_inventario($inv_nombre, $inv_tipo, $inv_cantidad_actual, $inv_ubicacion, $inv_precio_unitario, $conexion) {
    $query = "INSERT INTO inventario (inv_nombre, inv_tipo, inv_cantidad_actual, inv_ubicacion, inv_precio_unitario) 
              VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conexion, $query);
    mysqli_stmt_bind_param($stmt, "ssisd", $inv_nombre, $inv_tipo, $inv_cantidad_actual, $inv_ubicacion, $inv_precio_unitario);
    return mysqli_stmt_execute($stmt);
}

// Actualizar un producto o materia prima del inventario
function actualizar_producto_inventario($inv_id, $inv_nombre, $inv_tipo, $inv_cantidad_actual, $inv_ubicacion, $inv_precio_unitario, $conexion) {
    $query = "UPDATE inventario 
    SET inv_nombre = ?, inv_tipo = ?, inv_cantidad_actual = ?, inv_ubicacion = ?, inv_precio_unitario = ? 
    WHERE inv_id = ?";
    $stmt = mysqli_prepare($conexion, $query);
    mysqli_stmt_bind_param($stmt, "ssisd", $inv_nombre, $inv_tipo, $inv_cantidad_actual, $inv_ubicacion, $inv_precio_unitario, $inv_id);
    return mysqli_stmt_execute($stmt);
}

// Eliminar un producto del inventario
function eliminar_producto_inventario($id, $conexion) {
    $query = "DELETE FROM inventario WHERE inv_id = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param('i', $id);
    return $stmt->execute();
}

// Obtener todas las entradas del inventario (materia prima y productos terminados)
function obtener_entradas_inventario($conexion) {
    $query = "SELECT inventario_entradas.inv_entra_id, inventario_entradas.inv_entra_cantidad, inventario_entradas.inv_entra_fecha, inventario_entradas.inv_entra_proveedor, motivos.motivo_nombre, inventario.inv_nombre
                FROM inventario_entradas
                JOIN inventario ON inventario_entradas.inv_id = inventario.inv_id
                JOIN motivos ON inventario_entradas.motivo_id = motivos.motivo_id
                ORDER BY inventario_entradas.inv_entra_fecha ASC";
    return $conexion->query($query);
}

// Obtener todas las salidas del inventario (materia prima y productos terminados)
function obtener_salidas_inventario($conexion) {
    $query = "SELECT inventario_salidas.inv_sal_id, inventario_salidas.inv_sal_cantidad, inventario_salidas.inv_sal_fecha, inventario_salidas.inv_sal_destino, motivos.motivo_nombre, inventario.inv_nombre
                FROM inventario_salidas
                JOIN inventario ON inventario_salidas.inv_id = inventario.inv_id
                JOIN motivos ON inventario_salidas.motivo_id = motivos.motivo_id
                ORDER BY inventario_salidas.inv_sal_fecha ASC";
    return $conexion->query($query);
}

// Obtener información de una entrada específica por su ID
function obtener_entrada_por_id($entrada_id, $conexion) {
    $query = "SELECT inventario_entradas.inv_entra_id, inventario_entradas.inv_entra_cantidad, inventario_entradas.inv_entra_fecha, inventario_entradas.inv_entra_proveedor, motivos.motivo_nombre, inventario.inv_nombre
              FROM inventario_entradas
              JOIN inventario ON inventario_entradas.inv_id = inventario.inv_id
              JOIN motivos ON inventario_entradas.motivo_id = motivos.motivo_id
              WHERE inventario_entradas.inv_entra_id = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param('i', $entrada_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

// Obtener información de una salida específica por su ID
function obtener_salida_por_id($salida_id, $conexion) {
    $query = "SELECT inventario_salidas.inv_sal_id, inventario_salidas.inv_sal_cantidad, inventario_salidas.inv_sal_fecha, inventario_salidas.inv_sal_destino, motivos.motivo_nombre, inventario.inv_nombre
              FROM inventario_salidas
              JOIN inventario ON inventario_salidas.inv_id = inventario.inv_id
              JOIN motivos ON inventario_salidas.motivo_id = motivos.motivo_id
              WHERE inventario_salidas.inv_sal_id = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param('i', $salida_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

// Registrar una entrada de inventario y actualizar el stock
function registrar_entrada_inventario($inv_id, $cantidad, $fecha, $proveedor, $motivo, $conexion) {
    // Agregar la entrada al historial
    $query_entrada = "INSERT INTO inventario_entradas (inv_id, inv_entra_cantidad, inv_entra_fecha, inv_entra_proveedor, motivo_id) VALUES (?, ?, ?, ?, ?)";
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
    $query_salida = "INSERT INTO inventario_salidas (inv_id, inv_sal_cantidad, inv_sal_fecha, inv_sal_destino, motivo_id) VALUES (?, ?, ?, ?, ?)";
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

// Editar una entrada de inventario y ajustar el stock
function editar_entrada_inventario($entrada_id, $inv_id, $cantidad, $fecha, $proveedor, $motivo, $conexion) {
    // Obtener la información actual de la entrada
    $query_info = "SELECT inv_entra_cantidad, inv_id FROM inventario_entradas WHERE inv_entra_id = ?";
    $stmt_info = $conexion->prepare($query_info);
    $stmt_info->bind_param('i', $entrada_id);
    $stmt_info->execute();
    $entrada = $stmt_info->get_result()->fetch_assoc();
    $stmt_info->close();

    // Si no se encuentra la entrada, devolvemos un error
    if (!$entrada) {
        return "Error: Entrada no encontrada.";
    }

    // Revertir el ajuste anterior del stock
    $query_revertir_stock = "UPDATE inventario SET inv_cantidad_actual = inv_cantidad_actual - ? WHERE inv_id = ?";
    $stmt_revertir = $conexion->prepare($query_revertir_stock);
    $stmt_revertir->bind_param('ii', $entrada['inv_entra_cantidad'], $entrada['inv_id']);
    if (!$stmt_revertir->execute()) {
        return "Error al revertir el stock: " . $conexion->error;
    }

    // Actualizar los detalles de la entrada
    $query = "UPDATE inventario_entradas 
              SET inv_entra_cantidad = ?, inv_entra_fecha = ?, inv_entra_proveedor = ?, motivo_id = ? 
              WHERE inv_entra_id = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param('isssi', $cantidad, $fecha, $proveedor, $motivo, $entrada_id);
    
    if (!$stmt->execute()) {
        return "Error al actualizar la entrada: " . $conexion->error;
    }

    // Ahora, actualizar el stock con la nueva cantidad
    $query_actualizar_stock = "UPDATE inventario SET inv_cantidad_actual = inv_cantidad_actual + ? WHERE inv_id = ?";
    $stmt_stock = $conexion->prepare($query_actualizar_stock);
    $stmt_stock->bind_param('ii', $cantidad, $entrada['inv_id']);
    return $stmt_stock->execute() ? "Entrada actualizada y stock ajustado." : "Error al ajustar el stock: " . $conexion->error;
}

// Editar una salida de inventario y ajustar el stock
function editar_salida_inventario($salida_id, $inv_id, $cantidad, $fecha, $destino, $motivo, $conexion) {
    // Obtener la información actual de la salida
    $query_info = "SELECT inv_sal_cantidad, inv_id FROM inventario_salidas WHERE inv_sal_id = ?";
    $stmt_info = $conexion->prepare($query_info);
    $stmt_info->bind_param('i', $salida_id);
    $stmt_info->execute();
    $salida = $stmt_info->get_result()->fetch_assoc();
    $stmt_info->close();

    // Si no se encuentra la salida, devolvemos un error
    if (!$salida) {
        return "Error: Salida no encontrada.";
    }

    // Revertir el ajuste anterior del stock
    $query_revertir_stock = "UPDATE inventario SET inv_cantidad_actual = inv_cantidad_actual + ? WHERE inv_id = ?";
    $stmt_revertir = $conexion->prepare($query_revertir_stock);
    $stmt_revertir->bind_param('ii', $salida['inv_sal_cantidad'], $salida['inv_id']);
    if (!$stmt_revertir->execute()) {
        return "Error al revertir el stock: " . $conexion->error;
    }

    // Actualizar los detalles de la salida
    $query = "UPDATE inventario_salidas 
              SET inv_sal_cantidad = ?, inv_sal_fecha = ?, inv_sal_destino = ?, motivo_id = ? 
              WHERE inv_sal_id = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param('isssi', $cantidad, $fecha, $destino, $motivo, $salida_id);
    
    if (!$stmt->execute()) {
        return "Error al actualizar la salida: " . $conexion->error;
    }

    // Ahora, actualizar el stock con la nueva cantidad
    $query_actualizar_stock = "UPDATE inventario SET inv_cantidad_actual = inv_cantidad_actual - ? WHERE inv_id = ?";
    $stmt_stock = $conexion->prepare($query_actualizar_stock);
    $stmt_stock->bind_param('ii', $cantidad, $salida['inv_id']);
    return $stmt_stock->execute() ? "Salida actualizada y stock ajustado." : "Error al ajustar el stock: " . $conexion->error;
}

// Eliminar una entrada de inventario y ajustar el stock
function eliminar_entrada_inventario($entrada_id, $conexion) {
    // Obtener información de la entrada
    $query_info = "SELECT inv_id, inv_entra_cantidad FROM inventario_entradas WHERE inv_entra_id = ?";
    $stmt_info = $conexion->prepare($query_info);
    if (!$stmt_info) {
        return "Error en la preparación de la consulta: " . $conexion->error;
    }
    $stmt_info->bind_param('i', $entrada_id);
    $stmt_info->execute();
    $entrada = $stmt_info->get_result()->fetch_assoc();
    $stmt_info->close();

    // Verificar si se encontró la entrada
    if (!$entrada) {
        return "Error: Entrada no encontrada.";
    }

    // Ajustar el stock del inventario
    $query_ajustar_stock = "UPDATE inventario SET inv_cantidad_actual = inv_cantidad_actual - ? WHERE inv_id = ?";
    $stmt_ajustar = $conexion->prepare($query_ajustar_stock);
    if (!$stmt_ajustar) {
        return "Error en la preparación del ajuste de stock: " . $conexion->error;
    }
    $stmt_ajustar->bind_param('ii', $entrada['inv_entra_cantidad'], $entrada['inv_id']);
    if (!$stmt_ajustar->execute()) {
        return "Error al ajustar el stock: " . $stmt_ajustar->error;
    }
    $stmt_ajustar->close();

    // Eliminar la entrada
    $query_eliminar = "DELETE FROM inventario_entradas WHERE inv_entra_id = ?";
    $stmt_eliminar = $conexion->prepare($query_eliminar);
    if (!$stmt_eliminar) {
        return "Error en la preparación para eliminar la entrada: " . $conexion->error;
    }
    $stmt_eliminar->bind_param('i', $entrada_id);
    $resultado = $stmt_eliminar->execute();
    $stmt_eliminar->close();

    // Resultado final
    return $resultado ? "Entrada eliminada y stock ajustado correctamente." : "Error al eliminar la entrada.";
}

// Eliminar una salida de inventario y ajustar el stock
function eliminar_salida_inventario($salida_id, $conexion) {
    // Obtener información de la salida
    $query_info = "SELECT inv_id, inv_sal_cantidad FROM inventario_salidas WHERE inv_sal_id = ?";
    $stmt_info = $conexion->prepare($query_info);
    if (!$stmt_info) {
        return "Error en la preparación de la consulta: " . $conexion->error;
    }
    $stmt_info->bind_param('i', $salida_id);
    $stmt_info->execute();
    $salida = $stmt_info->get_result()->fetch_assoc();
    $stmt_info->close();

    // Verificar si se encontró la salida
    if (!$salida) {
        return "Error: Salida no encontrada.";
    }

    // Ajustar el stock del inventario
    $query_ajustar_stock = "UPDATE inventario SET inv_cantidad_actual = inv_cantidad_actual + ? WHERE inv_id = ?";
    $stmt_ajustar = $conexion->prepare($query_ajustar_stock);
    if (!$stmt_ajustar) {
        return "Error en la preparación del ajuste de stock: " . $conexion->error;
    }
    $stmt_ajustar->bind_param('ii', $salida['inv_sal_cantidad'], $salida['inv_id']);
    if (!$stmt_ajustar->execute()) {
        return "Error al ajustar el stock: " . $stmt_ajustar->error;
    }
    $stmt_ajustar->close();

    // Eliminar la salida
    $query_eliminar = "DELETE FROM inventario_salidas WHERE inv_sal_id = ?";
    $stmt_eliminar = $conexion->prepare($query_eliminar);
    if (!$stmt_eliminar) {
        return "Error en la preparación para eliminar la salida: " . $conexion->error;
    }
    $stmt_eliminar->bind_param('i', $salida_id);
    $resultado = $stmt_eliminar->execute();
    $stmt_eliminar->close();

    // Resultado final
    return $resultado ? "Salida eliminada y stock ajustado correctamente." : "Error al eliminar la salida.";
}

?>