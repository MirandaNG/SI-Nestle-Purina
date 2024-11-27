<?php
// Este archivo contiene las funciones relacionadas con la gestión de usuarios

// Registrar un nuevo usuario
function registrar_usuario($usu_nombre, $usu_correo, $usu_contraseña, $rol_id, $empl_id, $conexion) {
    $query_usuario_existente = "SELECT usu_id FROM usuarios WHERE usu_nombre = ?";
    $stmt = $conexion->prepare($query_usuario_existente);
    $stmt->bind_param('s', $usu_nombre);
    $stmt->execute();
    $resultado_usuario = $stmt->get_result();

    if ($resultado_usuario->num_rows > 0) {
        return "Error: El nombre de usuario ya está registrado.";
    }

    $query = "INSERT INTO usuarios (usu_nombre, usu_correo, usu_contraseña, rol_id, empl_id) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param('ssssi', $usu_nombre, $usu_correo, $usu_contraseña, $rol_id, $empl_id);
    
    return $stmt->execute() ? "Usuario creado exitosamente." : "Error al registrar el usuario: " . $conexion->error;
}

// Obtener la lista de usuarios
function obtener_usuarios($conexion) {
    $query = "SELECT usuarios.usu_id, usuarios.usu_nombre, usuarios.usu_correo, roles.rol_nombre, empleados.empl_nombre, empleados.empl_apellido 
              FROM usuarios
              JOIN roles ON usuarios.rol_id = roles.rol_id
              JOIN empleados ON usuarios.empl_id = empleados.empl_id";
    return $conexion->query($query);
}

// Obtener información de un usuario específico por su ID
function obtener_usuario_por_id($usu_id, $conexion) {
    $query = "SELECT usuarios.usu_id, usuarios.usu_nombre, usuarios.usu_correo, roles.rol_nombre, empleados.empl_nombre, empleados.empl_apellido 
              FROM usuarios
              JOIN roles ON usuarios.rol_id = roles.rol_id
              JOIN empleados ON usuarios.empl_id = empleados.empl_id
              WHERE usuarios.usu_id = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param('i', $usu_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

// Actualizar información de un usuario
function actualizar_usuario($usu_id, $usu_nombre, $usu_correo, $usu_contraseña, $rol_id, $conexion) {
    $query = "UPDATE usuarios SET usu_nombre = ?, usu_correo = ?, usu_contraseña = ?, rol_id = ? WHERE usu_id = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param('sssii', $usu_nombre, $usu_correo, $usu_contraseña, $rol_id, $usu_id);
    return $stmt->execute() ? "Usuario actualizado exitosamente." : "Error al actualizar el usuario: " . $conexion->error;
}

// Eliminar un usuario
function eliminar_usuario($usu_id, $conexion) {
    $query = "DELETE FROM usuarios WHERE usu_id = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param('i', $usu_id);
    return $stmt->execute() ? "Usuario eliminado exitosamente." : "Error al eliminar el usuario: " . $conexion->error;
}

// Validar login del usuario
function validar_login($usu_nombre, $usu_contraseña, $conexion) {
    $query = "SELECT usu_id, usu_contraseña FROM usuarios WHERE usu_nombre = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param('s', $usu_nombre);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows == 0) {
        return "Error: Usuario no encontrado.";
    }

    $usuario = $resultado->fetch_assoc();
    if (password_verify($usu_contraseña, $usuario['usu_contraseña'])) {
        return $usuario['usu_id']; // Retorna el ID del usuario si las credenciales son correctas
    } else {
        return "Error: Contraseña incorrecta.";
    }
}

// Cambiar la contraseña de un usuario
function cambiar_contraseña($usu_id, $contraseña_actual, $nueva_contraseña, $conexion) {
    $query = "SELECT usu_contraseña FROM usuarios WHERE usu_id = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param('i', $usu_id);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows == 0) {
        return "Error: Usuario no encontrado.";
    }

    $usuario = $resultado->fetch_assoc();
    if (password_verify($contraseña_actual, $usuario['usu_contraseña'])) {
        $nueva_contraseña_hash = password_hash($nueva_contraseña, PASSWORD_DEFAULT);
        $query_update = "UPDATE usuarios SET usu_contraseña = ? WHERE usu_id = ?";
        $stmt_update = $conexion->prepare($query_update);
        $stmt_update->bind_param('si', $nueva_contraseña_hash, $usu_id);
        return $stmt_update->execute() ? "Contraseña actualizada exitosamente." : "Error al actualizar la contraseña: " . $conexion->error;
    } else {
        return "Error: La contraseña actual no coincide.";
    }
}

// Obtener estadísticas de usuarios (por ejemplo, totales por rol)
function obtener_estadisticas_usuarios($conexion) {
    $query = "SELECT roles.rol_nombre, COUNT(usuarios.usu_id) AS total
              FROM usuarios
              JOIN roles ON usuarios.rol_id = roles.rol_id
              GROUP BY roles.rol_nombre";
    return $conexion->query($query);
}
?>
