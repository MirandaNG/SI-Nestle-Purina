<?php
// Este archivo contiene las funciones relacionadas con la gestión de productos

function obtener_especies($conexion) {
    $query = "SELECT * FROM especies";
    return $conexion->query($query);
}

function obtener_etapas($conexion) {
    $query = "SELECT * FROM etapa_vida";
    return $conexion->query($query);
}

function obtener_tipos($conexion) {
    $query = "SELECT * FROM tipo_comida";
    return $conexion->query($query);
}

// Función para obtener todos los productos
function obtener_productos($conexion) {
    $query = "SELECT productos.prod_id, productos.prod_nombre, productos.prod_descripcion, productos.prod_precio, especies.esp_nombre, tipo_comida.tip_com_nombre, etapa_vida.etpa_vida_nombre, productos.prod_marca
              FROM productos
              JOIN especies ON productos.esp_id = especies.esp_id
              JOIN tipo_comida ON productos.tip_com_id = tipo_comida.tip_com_id
              JOIN etapa_vida ON productos.etpa_vida_id = etapa_vida.etpa_vida_id";
    $resultado = $conexion->query($query);
    
    if ($resultado) {
        return $resultado;
    } else {
        return false;
    }
}

// Función para registrar un nuevo producto
function registrar_producto($nombre, $descripcion, $precio, $especie, $tipo_comida, $etapa_vida, $marca, $conexion) {
    $query = "INSERT INTO productos (prod_nombre, prod_descripcion, prod_precio, esp_id, tip_com_id, etpa_vida_id, prod_marca) 
              VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param('ssdiiis', $nombre, $descripcion, $precio, $especie, $tipo_comida, $etapa_vida, $marca);
    return $stmt->execute() ? "Producto registrado exitosamente." : "Error al registrar el producto: " . $conexion->error;
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
