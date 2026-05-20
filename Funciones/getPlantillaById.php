<?php
require_once "conn.php";

$con = conecta();

$id = $_GET['id'];

$sql = "SELECT * FROM conductas_plantilla WHERE id_plantilla = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();

$result = $stmt->get_result();

echo json_encode($result->fetch_assoc());