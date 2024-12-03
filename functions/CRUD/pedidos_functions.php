<?php
// Este archivo contiene las funciones relacionadas con los pedidos

// Función para obtener todas los pedidos
function obtener_todos_pedidos($conexion) {
    $query = "SELECT pedidos.pedo_id, clientes.clt_nombre, pedidos.pedo_fecha, pedidos.pedo_estado, pedidos.pedo_total 
              FROM pedidos
              JOIN clientes ON pedidos.clt_id = clientes.clt_id";
    $resultado = $conexion->query($query);

    if ($resultado) {
        return $resultado;
    } else {
        return false;
    }
}

// Función para obtener los detalles del pedido
function obtener_detalles_pedido($pedo_id, $conexion) {
    $query = "SELECT dp.det_pedo_id, dp.pedo_id, dp.prod_id, dp.det_pedo_cantidad, dp.det_pedo_precio_unit, dp.det_pedo_subtotal, p.prod_nombre
              FROM detalle_pedido dp
              JOIN productos p ON dp.prod_id = p.prod_id
              WHERE dp.pedo_id = '$pedo_id'";
    return mysqli_query($conexion, $query);
}

// Función para obtener los datos generales del pedido
function obtener_pedido($pedo_id, $conexion) {
    $query = "SELECT pedo_id, clt_id, pedo_fecha, pedo_total, c.clt_nombre
              FROM pedidos p
              JOIN clientes c ON p.clt_id = c.clt_id
              WHERE pedo_id = '$pedo_id'";
    return mysqli_fetch_assoc(mysqli_query($conexion, $query));
}


// Funciones relacionadas con Clientes
//  Obtener el historial de compras de un cliente por su ID.
//      @param int $cliente_id ID del cliente.
//      @param mysqli $conexion Conexión a la base de datos.
//      @return mysqli_result|false Resultados de la consulta o false en caso de error.
function obtener_pedidos_cliente($cliente_id, $conexion) {
    $query = "SELECT pedo_id, pedo_fecha, pedo_total, pedo_estado 
              FROM pedidos 
              WHERE clt_id = ? 
              ORDER BY pedo_fecha DESC";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param('i', $cliente_id);
    $stmt->execute();
    return $stmt->get_result();
}

//  Crear un nuevo pedido.
function agregar_pedido($clt_id, $productos, $conexion) {
    // Iniciar la transacción
    mysqli_begin_transaction($conexion);

    try {
        // Registrar el pedido en la tabla "pedidos"
        $fecha = date('Y-m-d H:i:s'); // Fecha actual
        $total_pedido = 0; // Total del pedido, se calculará en función de los productos

        $query_pedido = "INSERT INTO pedidos (clt_id, pedo_fecha, pedo_estado, pedo_total) 
                        VALUES ('$clt_id', '$fecha', 'Pendiente', 0)";
        if (!mysqli_query($conexion, $query_pedido)) {
            throw new Exception('Error al registrar el pedido');
        }

        // Obtener el ID del pedido registrado
        $pedo_id = mysqli_insert_id($conexion);

        // Registrar los productos en la tabla "detalle_pedido"
        foreach ($productos as $producto) {
            $prod_id = $producto['prod_id'];
            $cantidad = $producto['cantidad'];
            $precio_unit = $producto['precio_unit'];
            $subtotal = $cantidad * $precio_unit;

            $query_detalle = "INSERT INTO detalle_pedido (pedo_id, prod_id, det_pedo_cantidad, det_pedo_precio_unit, det_pedo_subtotal)
                              VALUES ('$pedo_id', '$prod_id', '$cantidad', '$precio_unit', '$subtotal')";
            if (!mysqli_query($conexion, $query_detalle)) {
                throw new Exception('Error al registrar el detalle del pedido');
            }

            // Sumar al total del pedido
            $total_pedido += $subtotal;
        }

        // Actualizar el total en la tabla "pedidos"
        $query_actualizar_total = "UPDATE pedidos SET pedo_total = '$total_pedido' WHERE pedo_id = '$pedo_id'";
        if (!mysqli_query($conexion, $query_actualizar_total)) {
            throw new Exception('Error al actualizar el total del pedido');
        }

        // Confirmar la transacción
        mysqli_commit($conexion);

        return "Pedido registrado con éxito";
    } catch (Exception $e) {
        // Si ocurre un error, deshacer la transacción
        mysqli_roll_back($conexion);
        return $e->getMessage();
    }
}

// Actualizar pedido
function actualizar_pedido($pedo_id, $clt_id, $productos, $conexion) {
    // Iniciar la transacción
    mysqli_begin_transaction($conexion);

    try {
        // Actualizar el pedido
        $query_pedido = "UPDATE pedidos SET clt_id = '$clt_id' WHERE pedo_id = '$pedo_id'";
        if (!mysqli_query($conexion, $query_pedido)) {
            throw new Exception('Error al actualizar el pedido');
        }

        // Eliminar detalles antiguos
        $query_eliminar_detalles = "DELETE FROM detalle_pedido WHERE pedo_id = '$pedo_id'";
        if (!mysqli_query($conexion, $query_eliminar_detalles)) {
            throw new Exception('Error al eliminar detalles del pedido');
        }

        // Registrar los nuevos detalles del pedido
        $total_pedido = 0;
        foreach ($productos as $producto) {
            $prod_id = $producto['prod_id'];
            $cantidad = $producto['cantidad'];
            $precio_unit = $producto['precio_unit'];
            $subtotal = $cantidad * $precio_unit;

            $query_detalle = "INSERT INTO detalle_pedido (pedo_id, prod_id, det_pedo_cantidad, det_pedo_precio_unit, det_pedo_subtotal)
                              VALUES ('$pedo_id', '$prod_id', '$cantidad', '$precio_unit', '$subtotal')";
            if (!mysqli_query($conexion, $query_detalle)) {
                throw new Exception('Error al actualizar los detalles del pedido');
            }

            // Sumar al total del pedido
            $total_pedido += $subtotal;
        }

        // Actualizar el total del pedido
        $query_actualizar_total = "UPDATE pedidos SET pedo_total = '$total_pedido' WHERE pedo_id = '$pedo_id'";
        if (!mysqli_query($conexion, $query_actualizar_total)) {
            throw new Exception('Error al actualizar el total del pedido');
        }

        // Confirmar la transacción
        mysqli_commit($conexion);
        return "Pedido actualizado con éxito.";
    } catch (Exception $e) {
        // Revertir la transacción en caso de error
        mysqli_rollBack($conexion);
        return "Error: " . $e->getMessage();
    }
}

// Eliminar pedido
function eliminar_pedido($pedo_id, $conexion) {
    // Iniciar la transacción
    mysqli_begin_transaction($conexion);

    try {
        // Eliminar los detalles del pedido
        $query_eliminar_detalles = "DELETE FROM detalle_pedido WHERE pedo_id = '$pedo_id'";
        if (!mysqli_query($conexion, $query_eliminar_detalles)) {
            throw new Exception('Error al eliminar detalles del pedido');
        }

        // Eliminar el pedido
        $query_pedido = "DELETE FROM pedidos WHERE pedo_id = '$pedo_id'";
        if (!mysqli_query($conexion, $query_pedido)) {
            throw new Exception('Error al eliminar el pedido');
        }

        // Confirmar la transacción
        mysqli_commit($conexion);
        return true;
    } catch (Exception $e) {
        // Revertir la transacción en caso de error
        mysqli_rollBack($conexion);
        return false;
    }
}
?>
