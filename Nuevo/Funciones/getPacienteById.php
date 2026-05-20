<?php
require_once "conn.php";

$con = conecta();

$id = $_GET['id'];

$sql = "SELECT id_usuario, username, email, fecha_nacimiento, sexo 
        FROM usuarios 
        WHERE id_usuario = ?";

$stmt = $con->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();

$result = $stmt->get_result();

echo json_encode($result->fetch_assoc());   