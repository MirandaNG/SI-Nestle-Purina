<?php
// Este archivo contiene las funciones relacionadas con la gestión de ubicaciones

// Obtener todas las ubicaciones
function obtener_ubicaciones($conexion) {
    $query = "SELECT * FROM ubicaciones";
    return $conexion->query($query);
}

// Obtener la ubicacion por el ID
function obtener_ubicacion_por_id($ubica_id, $conexion) {
    $sql = "SELECT * FROM ubicaciones WHERE ubica_id = ?";
    $stmt = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $ubica_id);
    mysqli_stmt_execute($stmt);
    $resultado = mysqli_stmt_get_result($stmt);
    $ubicacion = mysqli_fetch_assoc($resultado);

    // Validar si se encontró la ubicación
    if (!$ubicacion) {
        return null; // No encontró la ubicación, devolver null
    }

    return $ubicacion;
}

// Obtener el nombre de la ubicación dado su ID
function obtener_nombre_ubicacion($ubica_id, $conexion) {
    $query = "SELECT ubica_nombre FROM ubicaciones WHERE ubica_id = ?";
    $stmt = mysqli_prepare($conexion, $query);
    mysqli_stmt_bind_param($stmt, 'i', $ubica_id);
    mysqli_stmt_execute($stmt);
    $resultado = mysqli_stmt_get_result($stmt);

    if ($fila = mysqli_fetch_assoc($resultado)) {
        return $fila['ubica_nombre'];
    }
    return null; // Retorna null si no se encuentra la ubicación
}

// Agregar Nueva Ubicación
function agregar_ubicacion($nombre, $direccion, $conexion) {
    $sql = "INSERT INTO ubicaciones (ubica_nombre, ubica_direccion) VALUES (?, ?)";
    $stmt = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($stmt, 'ss', $nombre, $direccion);
    $resultado = mysqli_stmt_execute($stmt);
    return $resultado;
}

// Editar Ubicación
function editar_ubicacion($ubica_id, $nombre, $direccion, $conexion) {
    $sql = "UPDATE ubicaciones SET ubica_nombre = ?, ubica_direccion = ? WHERE ubica_id = ?";
    $stmt = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($stmt, 'ssi', $nombre, $direccion, $ubica_id);
    $resultado = mysqli_stmt_execute($stmt);
    return $resultado;
}

// Eliminar Ubicación
function eliminar_ubicacion($ubica_id, $conexion) {
    $sql = "DELETE FROM ubicaciones WHERE ubica_id = ?";
    $stmt = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $ubica_id);
    $resultado = mysqli_stmt_execute($stmt);
    return $resultado;
}

// Obtener todas las transferencias
function obtener_transferencias($conexion) {
    $query = "SELECT transferencias.transfe_id, transferencias.transfe_cantidad, transferencias.transfe_fecha, 
                     inventario.inv_nombre, 
                     origen.ubica_nombre AS origen_nombre, 
                     destino.ubica_nombre AS destino_nombre
              FROM transferencias
              JOIN inventario ON transferencias.inv_id = inventario.inv_id
              JOIN ubicaciones AS origen ON transferencias.origen_id = origen.ubica_id
              JOIN ubicaciones AS destino ON transferencias.destino_id = destino.ubica_id";
    $result = mysqli_query($conexion, $query);
    return $result;
}

// Obtener una transferencia por ID
function obtener_transferencia_por_id($transfe_id, $conexion) {
    $query = "SELECT transferencias.transfe_id, transferencias.transfe_cantidad, transferencias.transfe_fecha, 
                    inventario.inv_nombre, 
                    origen.ubica_nombre AS origen_nombre, 
                    destino.ubica_nombre AS destino_nombre
            FROM transferencias
            JOIN inventario ON transferencias.inv_id = inventario.inv_id
            JOIN ubicaciones AS origen ON transferencias.origen_id = origen.ubica_id
            JOIN ubicaciones AS destino ON transferencias.destino_id = destino.ubica_id
            WHERE transferencias.transfe_id = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param('i', $transfe_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

// Agregar una nueva transferencia
function registrar_transferencia($inv_id, $origen_id, $destino_id, $cantidad, $fecha, $conexion) {
    // Obtén el nombre de la ubicación origen y destino
    $nombre_origen = obtener_nombre_ubicacion($origen_id, $conexion);
    $nombre_destino = obtener_nombre_ubicacion($destino_id, $conexion);

    // Define el motivo_id correspondiente a "Transferencia"
    $motivo_id_transferencia = 3; // Este es el motivo_id para 'Transferencia' en la tabla 'motivos'

    // Registrar la transferencia en la tabla de transferencias
    $query = "INSERT INTO transferencias (inv_id, origen_id, destino_id, transfe_cantidad, transfe_fecha) 
              VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conexion, $query);
    mysqli_stmt_bind_param($stmt, 'iiids', $inv_id, $origen_id, $destino_id, $cantidad, $fecha);

    if (!$stmt->execute()) {
        return "Error al registrar la transferencia: " . $stmt->error;
    }

    // Obtener el transfe_id generado automáticamente
    $transfe_id = mysqli_insert_id($conexion);

    // Si el origen es 'Nestlé Purina Silao', es una salida
    if ($nombre_origen === 'Nestlé Purina Silao') {
        registrar_salida_inventario($inv_id, $cantidad, $fecha, $nombre_destino, $motivo_id_transferencia, $transfe_id, $conexion);
    } 
    // Si el destino es 'Nestlé Purina Silao', es una entrada
    elseif ($nombre_destino === 'Nestlé Purina Silao') {
        registrar_entrada_inventario($inv_id, $cantidad, $fecha, $nombre_origen, $motivo_id_transferencia, $transfe_id, $conexion);
    }

    return "Transferencia registrada correctamente con transfe_id: $transfe_id";
}


// Editar una transferencia
function editar_transferencia($transfe_id, $inv_id, $origen_id, $destino_id, $cantidad, $fecha, $conexion) {
    // Iniciar transacción para consistencia
    $conexion->begin_transaction();

    try {
        // Actualizar la transferencia
        $query_actualizar_transferencia = "UPDATE transferencias 
                                           SET inv_id = ?, origen_id = ?, destino_id = ?, transfe_cantidad = ?, transfe_fecha = ? 
                                           WHERE transfe_id = ?";
        $stmt_transferencia = $conexion->prepare($query_actualizar_transferencia);
        $stmt_transferencia->bind_param('iiidsi', $inv_id, $origen_id, $destino_id, $cantidad, $fecha, $transfe_id);
        if (!$stmt_transferencia->execute()) {
            throw new Exception("Error al actualizar la transferencia: " . $stmt_transferencia->error);
        }

        // Obtener el nombre de las ubicaciones
        $nombre_origen = obtener_nombre_ubicacion($origen_id, $conexion);
        $nombre_destino = obtener_nombre_ubicacion($destino_id, $conexion);

        // Define el motivo para "Transferencia"
        $motivo_id_transferencia = 3;

        // Actualizar entrada y salida asociadas
        if ($nombre_origen === 'Nestlé Purina Silao') {
            // Es una salida desde el origen
            $query_salida_id = "SELECT inv_sal_id FROM inventario_salidas WHERE transfe_id = ?";
            $stmt_salida_id = $conexion->prepare($query_salida_id);
            $stmt_salida_id->bind_param('i', $transfe_id);
            $stmt_salida_id->execute();
            $salida = $stmt_salida_id->get_result()->fetch_assoc();
            if ($salida) {
                editar_salida_inventario($salida['inv_sal_id'], $inv_id, $cantidad, $fecha, $nombre_destino, $motivo_id_transferencia, $conexion);
            }
        }

        if ($nombre_destino === 'Nestlé Purina Silao') {
            // Es una entrada al destino
            $query_entrada_id = "SELECT inv_entra_id FROM inventario_entradas WHERE transfe_id = ?";
            $stmt_entrada_id = $conexion->prepare($query_entrada_id);
            $stmt_entrada_id->bind_param('i', $transfe_id);
            $stmt_entrada_id->execute();
            $entrada = $stmt_entrada_id->get_result()->fetch_assoc();
            if ($entrada) {
                editar_entrada_inventario($entrada['inv_entra_id'], $inv_id, $cantidad, $fecha, $nombre_origen, $motivo_id_transferencia, $conexion);
            }
        }

        // Confirmar transacción
        $conexion->commit();
        return "Transferencia, entrada y salida actualizadas correctamente.";
    } catch (Exception $e) {
        // Revertir transacción en caso de error
        $conexion->rollback();
        return "Error al editar la transferencia: " . $e->getMessage();
    }
}

// Eliminar una transferencia
function eliminar_transferencia($transfe_id, $conexion) {
    // Iniciar transacción
    $conexion->begin_transaction();

    try {
        // Obtener registros de entradas y salidas asociadas
        $entrada = obtener_entrada_por_transferencia($transfe_id, $conexion);
        $salida = obtener_salida_por_transferencia($transfe_id, $conexion);

        // Eliminar de las tablas de entradas y salidas
        if ($entrada) {
            eliminar_entrada_inventario($entrada['inv_entra_id'], $conexion);
        }
        if ($salida) {
            eliminar_salida_inventario($salida['inv_sal_id'], $conexion);
        }

        // Eliminar la transferencia
        $query = "DELETE FROM transferencias WHERE transfe_id = ?";
        $stmt = mysqli_prepare($conexion, $query);
        mysqli_stmt_bind_param($stmt, 'i', $transfe_id);
        if (!$stmt->execute()) {
            throw new Exception("Error al eliminar la transferencia: " . $conexion->error);
        }

        // Confirmar transacción
        $conexion->commit();
        return "Transferencia y registros asociados eliminados correctamente.";
    } catch (Exception $e) {
        // Revertir transacción en caso de error
        $conexion->rollback();
        return "Error al eliminar la transferencia: " . $e->getMessage();
    }
}
?>