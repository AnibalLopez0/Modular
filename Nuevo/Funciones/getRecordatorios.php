<?php
session_start();
require_once "conn.php";

$con = conecta();

$id_paciente = $_SESSION['id'];

$sql = "SELECT r.*, u.username AS remitente
        FROM recordatorios r
        JOIN usuarios u ON r.id_remitente = u.id_usuario
        WHERE r.id_paciente = ? AND r.is_active = 1
        ORDER BY r.fecha DESC"; 

$stmt = $con->prepare($sql);
$stmt->bind_param("i", $id_paciente);
$stmt->execute();

$result = $stmt->get_result();

$data = [];

while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);