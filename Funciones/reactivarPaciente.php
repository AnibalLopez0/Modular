<?php
session_start();
require_once "conn.php";

if ($_SESSION['rol'] != 'terapeuta') {
    die("Acceso denegado");
}

$con = conecta();

$id = $_POST['id'];

// 🔒 Validar relación
$sqlCheck = "SELECT * FROM relaciones 
             WHERE id_terapeuta = ? AND id_paciente = ?";

$stmtCheck = $con->prepare($sqlCheck);
$stmtCheck->bind_param("ii", $_SESSION['id'], $id);
$stmtCheck->execute();

$res = $stmtCheck->get_result();

if ($res->num_rows == 0) {
    die("Paciente no válido");
}

// 🔄 Reactivar
$sql = "UPDATE relaciones 
        SET is_active = 1 
        WHERE id_paciente = ? AND id_terapeuta = ?";

$stmt = $con->prepare($sql);
$stmt->bind_param("ii", $id, $_SESSION['id']);

if ($stmt->execute()) {
    echo "Paciente reactivado";
} else {
    echo "Error al reactivar";
}