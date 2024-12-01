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

    if ($nombre_origen === 'Nestlé Purina Silao') {
        // Es una salida
        registrar_salida_inventario($inv_id, $cantidad, $fecha, $nombre_destino, $motivo_id_transferencia, $conexion);
    } elseif ($nombre_destino === 'Nestlé Purina Silao') {
        // Es una entrada
        registrar_entrada_inventario($inv_id, $cantidad, $fecha, $nombre_origen, $motivo_id_transferencia, $conexion);
    }

    // Registrar la transferencia en la tabla de transferencias
    $query = "INSERT INTO transferencias (inv_id, origen_id, destino_id, transfe_cantidad, transfe_fecha) 
              VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conexion, $query);
    mysqli_stmt_bind_param($stmt, 'iiids', $inv_id, $origen_id, $destino_id, $cantidad, $fecha);

    return mysqli_stmt_execute($stmt);
}


// Editar una transferencia
function editar_transferencia($transfe_id, $inv_id, $origen_id, $destino_id, $cantidad, $fecha, $conexion) {
    // Obtén los nombres de las ubicaciones origen y destino
    $nombre_origen = obtener_nombre_ubicacion($origen_id, $conexion);
    $nombre_destino = obtener_nombre_ubicacion($destino_id, $conexion);

    // Define el motivo_id correspondiente a "Transferencia"
    $motivo_id_transferencia = 3; // 'Transferencia'

    // Si el origen es 'Nestlé Purina Silao', es una salida
    if ($nombre_origen === 'Nestlé Purina Silao') {
        editar_salida_inventario($transfe_id, $inv_id, $cantidad, $fecha, $nombre_destino, $motivo_id_transferencia, $conexion);
    }
    // Si el destino es 'Nestlé Purina Silao', es una entrada
    elseif ($nombre_destino === 'Nestlé Purina Silao') {
        editar_entrada_inventario($transfe_id, $inv_id, $cantidad, $fecha, $nombre_origen, $motivo_id_transferencia, $conexion);
    }
}

// Eliminar una transferencia
function eliminar_transferencia($transfe_id, $conexion) {
    // Primero obtenemos los registros correspondientes en entradas y salidas
    $entrada = obtener_entrada_por_transferencia($transfe_id, $conexion);
    $salida = obtener_salida_por_transferencia($transfe_id, $conexion);

    // Eliminar de las tablas de entradas y salidas
    if ($entrada) {
        eliminar_entrada_inventario($entrada['inv_entra_id'], $conexion);
    }
    if ($salida) {
        eliminar_salida_inventario($salida['inv_sal_id'], $conexion);
    }

    // Ahora elimina la transferencia
    $query = "DELETE FROM transferencias WHERE transfe_id = ?";
    $stmt = mysqli_prepare($conexion, $query);
    mysqli_stmt_bind_param($stmt, 'i', $transfe_id);
    return mysqli_stmt_execute($stmt);
}
?>