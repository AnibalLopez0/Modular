<?php
session_start();
require_once "conn.php";

if ($_SESSION['rol'] != 'paciente') {
    die("Acceso denegado");
}

$con = conecta();

$id_paciente = $_SESSION['id'];

$id_plantilla = $_POST['id_plantilla'];
$descripcion = $_POST['descripcion'] ?? null;
$intensidad = $_POST['intensidad'] ?? null;
$duracion = $_POST['duracion'] ?? null;

$fecha = date("Y-m-d");

$sql = "INSERT INTO conductas 
        (id_plantilla, id_paciente, descripcion, fecha, intensidad, duracion)
        VALUES (?, ?, ?, ?, ?, ?)";

$stmt = $con->prepare($sql);
$stmt->bind_param("iissii", 
    $id_plantilla, 
    $id_paciente, 
    $descripcion, 
    $fecha, 
    $intensidad, 
    $duracion
);

if ($stmt->execute()) {
    echo "Conducta registrada correctamente";
} else {
    echo "Error al registrar";
}

