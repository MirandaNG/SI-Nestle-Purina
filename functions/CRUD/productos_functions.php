<?php
// Este archivo contiene las funciones relacionadas con la gestión de productos

// Obtener todos los productos
function obtener_productos($conexion) {
    $query = "
        SELECT 
            p.prod_id, 
            p.prod_nombre, 
            p.prod_descripcion, 
            p.prod_precio, 
            e.esp_nombre AS especie, 
            tc.tip_com_nombre AS tipo_comida, 
            ev.etpa_vida_nombre AS etapa_vida, 
            p.prod_marca 
        FROM productos p
        LEFT JOIN especies e ON p.esp_id = e.esp_id
        LEFT JOIN tipo_comida tc ON p.tip_com_id = tc.tip_com_id
        LEFT JOIN etapa_vida ev ON p.etpa_vida_id = ev.etpa_vida_id
        ORDER BY p.prod_nombre";
    return mysqli_query($conexion, $query);
}

// Agregar producto
function agregar_producto($conexion, $nombre, $descripcion, $precio, $especie_id, $tipo_comida_id, $etapa_vida_id, $marca) {
    $query = "INSERT INTO productos (prod_nombre, prod_descripcion, prod_precio, esp_id, tip_com_id, etpa_vida_id, prod_marca) 
              VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param('ssdiiis', $nombre, $descripcion, $precio, $especie_id, $tipo_comida_id, $etapa_vida_id, $marca);
    return $stmt->execute();
}

// Obtener producto por ID
function obtener_producto_por_id($conexion, $id) {
    $query = "
        SELECT 
            p.prod_id, 
            p.prod_nombre, 
            p.prod_descripcion, 
            p.prod_precio, 
            p.esp_id, 
            p.tip_com_id, 
            p.etpa_vida_id, 
            p.prod_marca
        FROM productos p
        WHERE p.prod_id = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

function obtener_producto_detallado($conexion, $prod_id) {
    $sql = "
        SELECT 
            p.prod_id,
            p.prod_nombre,
            p.prod_descripcion,
            p.prod_precio,
            p.prod_marca,
            e.esp_nombre,
            tc.tip_com_nombre,
            ev.etpa_vida_nombre
        FROM productos p
        LEFT JOIN especies e ON p.esp_id = e.esp_id
        LEFT JOIN tipo_comida tc ON p.tip_com_id = tc.tip_com_id
        LEFT JOIN etapa_vida ev ON p.etpa_vida_id = ev.etpa_vida_id
        WHERE p.prod_id = ?";
    
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $prod_id);
    $stmt->execute();
    $resultado = $stmt->get_result();
    return $resultado->fetch_assoc();
}


// Actualizar producto
function actualizar_producto($conexion, $id, $nombre, $descripcion, $precio, $especie_id, $tipo_comida_id, $etapa_vida_id, $marca) {
    $query = "UPDATE productos 
              SET prod_nombre = ?, prod_descripcion = ?, prod_precio = ?, esp_id = ?, tip_com_id = ?, etpa_vida_id = ?, prod_marca = ?
              WHERE prod_id = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param('ssdiiisi', $nombre, $descripcion, $precio, $especie_id, $tipo_comida_id, $etapa_vida_id, $marca, $id);
    return $stmt->execute();
}

// Eliminar producto
function eliminar_producto($conexion, $id) {
    $query = "DELETE FROM productos WHERE prod_id = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param('i', $id);
    return $stmt->execute();
}

// Obtener opciones para listas desplegables
function obtener_especies($conexion) {
    $query = "SELECT * FROM especies";
    return mysqli_query($conexion, $query);
}

function obtener_tipos_comida($conexion) {
    $query = "SELECT * FROM tipo_comida";
    return mysqli_query($conexion, $query);
}

function obtener_etapas_vida($conexion) {
    $query = "SELECT * FROM etapa_vida";
    return mysqli_query($conexion, $query);
}
?>