<?php
require_once "conn.php";

$con = conecta();

$sql = "SELECT * FROM conductas_plantilla";
$result = $con->query($sql);

$data = []; // 🔥 IMPORTANTE

while ($row = $result->fetch_assoc()) {
    $row['is_active'] = (int)$row['is_active']; // 🔥 conversión correcta
    $data[] = $row;
}

echo json_encode($data);