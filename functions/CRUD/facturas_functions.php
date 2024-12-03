<?php
// Este archivo contiene las funciones relacionadas con las facturas

// Función para obtener todas las facturas
function obtener_todas_facturas($conexion) {
    $query = "SELECT facturas.fact_id, facturas.fact_folio, facturas.fact_tipo, 
                     CASE 
                         WHEN facturas.clt_id IS NOT NULL THEN clientes.clt_nombre 
                         WHEN facturas.prov_id IS NOT NULL THEN proveedores.prov_nombre 
                         ELSE 'Desconocido' 
                     END AS nombre_cliente_o_proveedor,
                     facturas.fact_fecha, facturas.fact_total, facturas.fact_estado, 
                     metodos_pago.met_pago_nombre
              FROM facturas
              LEFT JOIN clientes ON facturas.clt_id = clientes.clt_id
              LEFT JOIN proveedores ON facturas.prov_id = proveedores.prov_id
              LEFT JOIN metodos_pago ON facturas.met_pago_id = metodos_pago.met_pago_id
              ORDER BY facturas.fact_fecha ASC";

    $resultado = $conexion->query($query);

    if ($resultado) {
        return $resultado;
    } else {
        return false;
    }
}

function obtener_metodos_pago($conexion) {
    // Consulta SQL para obtener los métodos de pago
    $query = "SELECT met_pago_id, met_pago_nombre FROM metodos_pago";
    
    // Ejecutar la consulta
    $resultado = $conexion->query($query);

    // Verificar si la consulta fue exitosa
    if ($resultado) {
        return $resultado;  // Devuelve el resultado de la consulta
    } else {
        return false;  // Si hubo un error, devuelve false
    }
}

// Agregar Factura
function agregar_factura($tipo, $cliente_o_proveedor_id, $folio, $total, $estado, $metodo_pago, $conexion) {
    // Preparar la consulta SQL según el tipo de factura (venta o compra)
    if ($tipo == 'venta') {
        // Si es una factura de venta, se debe asociar al cliente
        $query = "INSERT INTO facturas (fact_folio, fact_tipo, clt_id, fact_fecha, fact_total, fact_estado, met_pago_id)
                  VALUES (?, ?, ?, NOW(), ?, ?, ?)";
    } else if ($tipo == 'compra') {
        // Si es una factura de compra, se debe asociar al proveedor
        $query = "INSERT INTO facturas (fact_folio, fact_tipo, prov_id, fact_fecha, fact_total, fact_estado, met_pago_id)
                  VALUES (?, ?, ?, NOW(), ?, ?, ?)";
    }

    // Preparar la declaración SQL
    if ($stmt = $conexion->prepare($query)) {
        // Enlazar los parámetros de la consulta (usamos 's' para string, 'd' para números decimales)
        if ($tipo == 'venta') {
            $stmt->bind_param("ssssss", $folio, $tipo, $cliente_o_proveedor_id, $total, $estado, $metodo_pago);
        } else {
            $stmt->bind_param("ssssss", $folio, $tipo, $cliente_o_proveedor_id, $total, $estado, $metodo_pago);
        }

        // Ejecutar la consulta
        if ($stmt->execute()) {
            return true; // Si la factura se agregó con éxito
        } else {
            return false; // Si hubo un error al ejecutar la consulta
        }

        $stmt->close(); // Cerrar la declaración
    } else {
        return false; // Si hubo un error al preparar la consulta
    }
}

function editar_factura($fact_id, $tipo, $cliente_o_proveedor_id, $folio, $total, $estado, $metodo_pago, $conexion) {
    // Preparar la consulta SQL según el tipo de factura (venta o compra)
    if ($tipo == 'venta') {
        // Si es una factura de venta, se debe asociar al cliente
        $query = "UPDATE facturas 
                  SET fact_folio = ?, fact_tipo = ?, clt_id = ?, fact_total = ?, fact_estado = ?, met_pago_id = ?
                  WHERE fact_id = ?";
    } else if ($tipo == 'compra') {
        // Si es una factura de compra, se debe asociar al proveedor
        $query = "UPDATE facturas 
                  SET fact_folio = ?, fact_tipo = ?, prov_id = ?, fact_total = ?, fact_estado = ?, met_pago_id = ?
                  WHERE fact_id = ?";
    }

    // Preparar la declaración SQL
    if ($stmt = $conexion->prepare($query)) {
        // Enlazar los parámetros de la consulta
        if ($tipo == 'venta') {
            $stmt->bind_param("ssssssi", $folio, $tipo, $cliente_o_proveedor_id, $total, $estado, $metodo_pago, $fact_id);
        } else {
            $stmt->bind_param("ssssssi", $folio, $tipo, $cliente_o_proveedor_id, $total, $estado, $metodo_pago, $fact_id);
        }

        // Ejecutar la consulta
        if ($stmt->execute()) {
            return true; // Si la factura se actualizó con éxito
        } else {
            return false; // Si hubo un error al ejecutar la consulta
        }

        $stmt->close(); // Cerrar la declaración
    } else {
        return false; // Si hubo un error al preparar la consulta
    }
}

function eliminar_factura($fact_id, $conexion) {
    // Consulta SQL para eliminar la factura
    $query = "DELETE FROM facturas WHERE fact_id = ?";

    // Preparar la declaración SQL
    if ($stmt = $conexion->prepare($query)) {
        // Enlazar el parámetro de la consulta
        $stmt->bind_param("i", $fact_id);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            return true; // Si la factura se eliminó con éxito
        } else {
            return false; // Si hubo un error al ejecutar la consulta
        }

        $stmt->close(); // Cerrar la declaración
    } else {
        return false; // Si hubo un error al preparar la consulta
    }
}

?>