<?php
// Este archivo contiene las funciones relacionadas con las facturas

// Función para obtener todas las facturas
function obtener_todas_facturas($conexion) {
    $query = "SELECT facturas.fact_id, facturas.fact_folio, facturas.fact_tipo, clientes.clt_nombre, proveedores.prov_nombre, facturas.fact_fecha, fact_total, fact_estado, metodos_pago.met_pago_nombre
              FROM facturas
              JOIN clientes ON facturas.clt_id = clientes.clt_id
              JOIN proveedores ON facturas.prov_id = proveedores.prov_id
              JOIN metodos_pago ON facturas.met_pago_id = metodos_pago.met_pago_id
              ORDER BY facturas.fact_fecha ASC";
    $resultado = $conexion->query($query);

    if ($resultado) {
        return $resultado;
    } else {
        return false;
    }
}

// Función para obtener facturas según el tipo
function obtener_todas_facturas_ventas($conexion) {     //  VENTAS
    $query = "SELECT facturas.fact_id, facturas.fact_folio, facturas.fact_tipo, clientes.clt_nombre, proveedores.prov_nombre, facturas.fact_fecha, fact_total, fact_estado, metodos_pago.met_pago_nombre
              FROM facturas
              JOIN clientes ON facturas.clt_id = clientes.clt_id
              JOIN proveedores ON facturas.prov_id = proveedores.prov_id
              JOIN metodos_pago ON facturas.met_pago_id = metodos_pago.met_pago_id
              WHERE facturas.fact_tipo = 'ventas'
              ORDER BY facturas.fact_fecha ASC";
    $resultado = $conexion->query($query);

    if ($resultado) {
        return $resultado;
    } else {
        return false;
    }
}

function obtener_todas_facturas_compras($conexion) {     //  COMPRAS
    $query = "SELECT facturas.fact_id, facturas.fact_folio, facturas.fact_tipo, clientes.clt_nombre, proveedores.prov_nombre, facturas.fact_fecha, fact_total, fact_estado, metodos_pago.met_pago_nombre
              FROM facturas
              JOIN clientes ON facturas.clt_id = clientes.clt_id
              JOIN proveedores ON facturas.prov_id = proveedores.prov_id
              JOIN metodos_pago ON facturas.met_pago_id = metodos_pago.met_pago_id
              WHERE facturas.fact_tipo = 'compras'
              ORDER BY facturas.fact_fecha ASC";
    $resultado = $conexion->query($query);

    if ($resultado) {
        return $resultado;
    } else {
        return false;
    }
}

// Agregar Factura
function agregar_factura($tipo, $cliente_o_proveedor_id, $folio, $total, $estado, $metodo_pago, $conexion) {
    $cliente_o_proveedor_columna = ($tipo === 'venta') ? 'clt_id' : 'prov_id';
    
    $query = "INSERT INTO facturas (fact_folio, fact_tipo, $cliente_o_proveedor_columna, fact_total, fact_estado, met_pago_id, fact_fecha) 
              VALUES ('$folio', '$tipo', '$cliente_o_proveedor_id', '$total', '$estado', '$metodo_pago', NOW())";
    
    if (mysqli_query($conexion, $query)) {
        return true;
    } else {
        return false;
    }
}

?>