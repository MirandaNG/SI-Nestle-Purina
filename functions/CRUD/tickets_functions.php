<?php
// Este archivo contiene las funciones relacionadas con el soporte

// Función para obtener todos los ticket de soporte
function obtener_todos_tickets($conexion) {
    $query = "SELECT tickets_soporte.tick_soport_id, clientes.clt_nombre, tickets_soporte.tick_soport_descripcion, tickets_soporte.tick_soport_fecha_creacion, tickets_soporte.tick_soport_estado
              FROM tickets_soporte
              JOIN clientes ON tickets_soporte.clt_id = clientes.clt_id";
    $resultado = $conexion->query($query);

    if ($resultado) {
        return $resultado;
    } else {
        return false;
    }
}

function obtener_ticket_por_id($ticket_id, $conexion) {
    $query = "
        SELECT 
            ts.tick_soport_id,
            c.clt_nombre,
            ts.tick_soport_descripcion,
            ts.tick_soport_fecha_creacion,
            ts.tick_soport_estado
        FROM tickets_soporte ts
        LEFT JOIN clientes c ON ts.clt_id = c.clt_id
        WHERE ts.tick_soport_id = ?";

        $stmt = $conexion->prepare($query);
        $stmt->bind_param('i', $ticket_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
}

function obtener_ticket_detallado($ticket_id, $conexion) {
    $sql = "
        SELECT 
            ts.tick_soport_id,
            c.clt_nombre,
            ts.tick_soport_descripcion,
            ts.tick_soport_fecha_creacion,
            ts.tick_soport_estado
        FROM tickets_soporte ts
        LEFT JOIN clientes c ON ts.clt_id = c.clt_id
        WHERE ts.tick_soport_id = ?";
    
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $ticket_id);
    $stmt->execute();
    $resultado = $stmt->get_result();
    return $resultado->fetch_assoc();
}

// Funciones relacionadas con Clientes
//  Crear un nuevo ticket de soporte.
//      @param int $cliente_id ID del cliente.
//      @param string $descripcion Descripción del problema.
//      @param string $estado Estado del ticket.
//      @param mysqli $conexion Conexión a la base de datos.
//      @return bool True si se insertó correctamente, False en caso contrario.

function crear_ticket_soporte($cliente_id, $descripcion, $estado, $conexion) {
    $query = "INSERT INTO tickets_soporte (clt_id, tick_soport_descripcion, tick_soport_estado, tick_soport_fecha_creacion) 
              VALUES (?, ?, ?, NOW())";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param('iss', $cliente_id, $descripcion, $estado);
    return $stmt->execute();
}


function actualizar_ticket($ticket_id, $descripcion, $estado, $conexion) {
    $query = "UPDATE tickets_soporte
              SET tick_soport_descripcion = ?, tick_soport_estado = ?
              WHERE tick_soport_id = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param('ssi', $descripcion, $estado, $ticket_id);
    if ($stmt->execute()) {
        return true;
    } else {
        error_log("Error al actualizar el ticket: " . $conexion->error);
        return false;
    }
}

function eliminar_ticket($ticket_id, $conexion) {
    $query = "DELETE FROM tickets_soporte WHERE tick_soport_id = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param('i', $ticket_id);
    return $stmt->execute();
}

?>