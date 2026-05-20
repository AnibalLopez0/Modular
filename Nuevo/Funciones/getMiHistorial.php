<?php
session_start();
require_once "conn.php";

$con = conecta();

$id_paciente = $_SESSION['id'];

$sql = "SELECT c.*, p.titulo 
        FROM conductas c
        JOIN conductas_plantilla p ON c.id_plantilla = p.id_plantilla
        WHERE c.id_paciente = ?
        ORDER BY c.fecha DESC";

$stmt = $con->prepare($sql);
$stmt->bind_param("i", $id_paciente);
$stmt->execute();

$result = $stmt->get_result();

$data = [];

while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);
?>