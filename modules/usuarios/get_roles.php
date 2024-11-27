<?php
include '../../config/conexion.php';

$depa_id = intval($_GET['depa_id']);
$query = "SELECT * FROM Roles WHERE depa_id = ?";
$stmt = $conexion->prepare($query);
$stmt->bind_param('i', $depa_id);
$stmt->execute();
$result = $stmt->get_result();

$roles = [];
while ($rol = $result->fetch_assoc()) {
    $roles[] = $rol;
}

header('Content-Type: application/json');
echo json_encode($roles);
