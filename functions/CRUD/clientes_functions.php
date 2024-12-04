<?php
// Este archivo contiene las funciones relacionadas con la gestión de clientes

// Función para obtener todos los clientes
function obtener_clientes($conexion) {
    $query = "SELECT clt_id, clt_nombre, clt_contacto, clt_direccion, clt_telefono, clt_email, clt_tipo FROM clientes";
    $resultado = $conexion->query($query);

    if ($resultado) {
        return $resultado;
    } else {
        return false;
    }
}

// Función para obtener un cliente por su ID
function obtener_cliente_por_id($cliente_id, $conexion) {
    $query = "SELECT clt_id, clt_nombre, clt_contacto, clt_direccion, clt_telefono, clt_email, clt_tipo 
              FROM clientes 
              WHERE clt_id = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param('i', $cliente_id);
    $stmt->execute();
    $resultado = $stmt->get_result();
    return $resultado->num_rows > 0 ? $resultado->fetch_assoc() : null;
}

// Función para obtener las opciones de un campo ENUM
function obtener_opciones_enum($conexion, $tabla, $columna) {
    $tabla = mysqli_real_escape_string($conexion, $tabla);
    $columna = mysqli_real_escape_string($conexion, $columna);

    $query = "SHOW COLUMNS FROM `$tabla` LIKE '$columna'";
    $result = mysqli_query($conexion, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $type = $row['Type'];
        preg_match("/^enum\('(.*)'\)$/", $type, $matches);
        return explode("','", $matches[1]);
    }

    return [];
}

// Función para registrar un nuevo cliente
function agregar_cliente($nombre, $contacto, $direccion, $telefono, $email, $tipo, $conexion) {
    $query = "INSERT INTO clientes (clt_nombre, clt_contacto, clt_direccion, clt_telefono, clt_email, clt_tipo) 
              VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param('ssssss', $nombre, $contacto, $direccion, $telefono, $email, $tipo);

    if ($stmt->execute()) {
        return true;
    } else {
        error_log("Error al registrar el cliente: " . $conexion->error);
        return false;
    }
}

// Función para actualizar un cliente
function editar_cliente($cliente_id, $nombre, $contacto, $direccion, $telefono, $email, $tipo, $conexion) {
    $query = "UPDATE clientes 
              SET clt_nombre = ?, clt_contacto = ?, clt_direccion = ?, clt_telefono = ?, clt_email = ?, clt_tipo = ? 
              WHERE clt_id = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param('ssssssi', $nombre, $contacto, $direccion, $telefono, $email, $tipo, $cliente_id);

    if ($stmt->execute()) {
        return true;
    } else {
        error_log("Error al actualizar el cliente: " . $conexion->error);
        return false;
    }
}

// Función para eliminar un cliente
function eliminar_cliente($cliente_id, $conexion) {
    // Verificar si el cliente tiene dependencias (por ejemplo, pedidos o facturas)
    $dependencias = verificar_dependencias_cliente($cliente_id, $conexion);

    if ($dependencias) {
        return false;
    }

    $query = "DELETE FROM clientes WHERE clt_id = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param('i', $cliente_id);

    if ($stmt->execute()) {
        return true;
    } else {
        error_log("Error al eliminar el cliente: " . $conexion->error);
        return false;
    }
}

// Función para verificar si el cliente tiene dependencias
function verificar_dependencias_cliente($cliente_id, $conexion) {
    $tablas_dependientes = ['pedidos', 'facturas'];
    foreach ($tablas_dependientes as $tabla) {
        $query = "SELECT COUNT(*) AS total FROM $tabla WHERE clt_id = ?";
        $stmt = $conexion->prepare($query);
        $stmt->bind_param('i', $cliente_id);
        $stmt->execute();
        $resultado = $stmt->get_result()->fetch_assoc();
        if ($resultado['total'] > 0) {
            return true;
        }
    }
    return false;
}
?>
