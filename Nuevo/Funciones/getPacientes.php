<?php
session_start();
require_once "conn.php";

$con = conecta();

$id_terapeuta = $_SESSION['id'];

$sql = "SELECT u.id_usuario, u.username, r.is_active
        FROM relaciones r
        JOIN usuarios u 
            ON r.id_paciente = u.id_usuario
        WHERE r.id_terapeuta = ?";

$stmt = $con->prepare($sql);
$stmt->bind_param("i", $id_terapeuta);
$stmt->execute();

$result = $stmt->get_result();

$data = [];

while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);
?>