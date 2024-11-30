<?php
// Este archivo contiene las funciones relacionadas con la gestión de órdenes de compra

// Función para registrar una orden de compra
function registrar_orden_compra($prov_id, $ord_comp_fecha, $ord_comp_estado, $conexion) {
    $query = "INSERT INTO ordenes_compra (prov_id, ord_comp_fecha, ord_comp_estado) VALUES (?, ?, ?)";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param('iss', $prov_id, $ord_comp_fecha, $ord_comp_estado);
    return $stmt->execute() ? "Orden de compra registrada exitosamente." : "Error al registrar la orden de compra: " . $conexion->error;
}

// Función para obtener todas las órdenes de compra
function obtener_ordenes_compra($conexion) {
    $query = "SELECT * FROM ordenes_compra";
    return $conexion->query($query);
}

// Función para obtener una orden de compra por su ID
function obtener_orden_compra_por_id($ord_comp_id, $conexion) {
    $query = "SELECT * FROM ordenes_compra WHERE ord_comp_id = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param('i', $ord_comp_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

// Función para actualizar una orden de compra
function actualizar_orden_compra($ord_comp_id, $prov_id, $ord_comp_fecha, $ord_comp_estado, $conexion) {
    $query = "UPDATE ordenes_compra SET prov_id = ?, ord_comp_fecha = ?, ord_comp_estado = ? WHERE ord_comp_id = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param('issi', $prov_id, $ord_comp_fecha, $ord_comp_estado, $ord_comp_id);
    return $stmt->execute() ? "Orden de compra actualizada exitosamente." : "Error al actualizar la orden de compra: " . $conexion->error;
}

// Función para eliminar una orden de compra
function eliminar_orden_compra($ord_comp_id, $conexion) {
    $query = "DELETE FROM ordenes_compra WHERE ord_comp_id = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param('i', $ord_comp_id);
    return $stmt->execute() ? "Orden de compra eliminada exitosamente." : "Error al eliminar la orden de compra: " . $conexion->error;
}
?>
