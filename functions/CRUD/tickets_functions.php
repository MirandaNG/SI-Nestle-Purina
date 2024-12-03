<?php
// Este archivo contiene las funciones relacionadas con el soporte

// Función para obtener todos los ticket de soporte


// Funciones relacionadas con Clientes
//  Obtener todos los tickets asociados a un cliente por su ID.
//      @param int $cliente_id ID del cliente.
//      @param mysqli $conexion Conexión a la base de datos.
//      @return mysqli_result|false Resultados de la consulta o false en caso de error.
function obtener_tickets_cliente($cliente_id, $conexion) {
    $query = "SELECT tick_soport_id, tick_soport_descripcion, tick_soport_fecha_creacion, tick_soport_estado 
              FROM tickets_soporte 
              WHERE clt_id = ? 
              ORDER BY tick_soport_fecha_creacion DESC";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param('i', $cliente_id);
    $stmt->execute();
    return $stmt->get_result();
}

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
?>
