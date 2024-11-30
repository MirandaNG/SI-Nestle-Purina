<?php
// Este archivo contiene las funciones relacionadas con la gestión de productos

// Función para registrar un nuevo producto
function registrar_producto($prod_nombre, $prod_descripcion, $prod_precio, $esp_id, $tip_com_id, $etpa_vida_id, $prod_marca, $conexion) {
    $query = "INSERT INTO productos (prod_nombre, prod_descripcion, prod_precio, esp_id, tip_com_id, etpa_vida_id, prod_marca) 
              VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param('ssdiiss', $prod_nombre, $prod_descripcion, $prod_precio, $esp_id, $tip_com_id, $etpa_vida_id, $prod_marca);
    return $stmt->execute() ? "Producto registrado exitosamente." : "Error al registrar el producto: " . $conexion->error;
}

// Función para obtener todos los productos
function obtener_productos($conexion) {
    $query = "SELECT * FROM productos";
    return $conexion->query($query);
}

// Función para obtener un producto por su ID
function obtener_producto_por_id($prod_id, $conexion) {
    $query = "SELECT * FROM productos WHERE prod_id = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param('i', $prod_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

// Función para actualizar un producto
function actualizar_producto($prod_id, $prod_nombre, $prod_descripcion, $prod_precio, $esp_id, $tip_com_id, $etpa_vida_id, $prod_marca, $conexion) {
    $query = "UPDATE productos SET prod_nombre = ?, prod_descripcion = ?, prod_precio = ?, esp_id = ?, tip_com_id = ?, etpa_vida_id = ?, prod_marca = ? WHERE prod_id = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param('ssdiisssi', $prod_nombre, $prod_descripcion, $prod_precio, $esp_id, $tip_com_id, $etpa_vida_id, $prod_marca, $prod_id);
    return $stmt->execute() ? "Producto actualizado exitosamente." : "Error al actualizar el producto: " . $conexion->error;
}

// Función para eliminar un producto
function eliminar_producto($prod_id, $conexion) {
    $query = "DELETE FROM productos WHERE prod_id = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param('i', $prod_id);
    return $stmt->execute() ? "Producto eliminado exitosamente." : "Error al eliminar el producto: " . $conexion->error;
}
?>
