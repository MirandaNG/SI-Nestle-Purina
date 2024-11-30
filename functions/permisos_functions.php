<?php
/**
 * Verifica si un usuario tiene un tipo de acceso (lectura, escritura, actualización) sobre una tabla específica.
 *
 * @param string $tabla Nombre de la tabla a verificar.
 * @param string $tipo_acceso Tipo de acceso requerido ('lectura', 'escritura', 'actualización').
 * @param array $permisos Lista de permisos del usuario, obtenido de $_SESSION['permisos'].
 * @return bool Retorna true si el usuario tiene el permiso, false en caso contrario.
 */
function tiene_permiso($tabla, $tipo_acceso, $permisos) {
    return isset($permisos[$tabla]) && in_array($tipo_acceso, $permisos[$tabla]);
}

/**
 * Verifica si el usuario tiene acceso de lectura a una tabla.
 *
 * @param string $tabla Nombre de la tabla a verificar.
 * @param array $permisos Lista de permisos del usuario.
 * @return bool Retorna true si el usuario tiene permiso de lectura, false en caso contrario.
 */
function puede_leer($tabla, $permisos) {
    return tiene_permiso($tabla, 'lectura', $permisos);
}

/**
 * Verifica si el usuario tiene acceso de escritura a una tabla.
 *
 * @param string $tabla Nombre de la tabla a verificar.
 * @param array $permisos Lista de permisos del usuario.
 * @return bool Retorna true si el usuario tiene permiso de escritura, false en caso contrario.
 */
function puede_escribir($tabla, $permisos) {
    return tiene_permiso($tabla, 'escritura', $permisos);
}

/**
 * Verifica si el usuario tiene acceso de actualización a una tabla.
 *
 * @param string $tabla Nombre de la tabla a verificar.
 * @param array $permisos Lista de permisos del usuario.
 * @return bool Retorna true si el usuario tiene permiso de actualización, false en caso contrario.
 */
function puede_actualizar($tabla, $permisos) {
    return tiene_permiso($tabla, 'actualización', $permisos);
}
