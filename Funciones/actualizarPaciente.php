<?php
session_start();
require_once "conn.php";

if ($_SESSION['rol'] != 'terapeuta') {
    die("Acceso denegado");
}

$con = conecta();

$id = $_POST['id'];
$username = $_POST['username'];
$email = $_POST['email'];
$fecha = $_POST['fecha'];
$sexo = $_POST['sexo'];

$sql = "UPDATE usuarios 
        SET username = ?, email = ?, fecha_nacimiento = ?, sexo = ?
        WHERE id_usuario = ?";

$stmt = $con->prepare($sql);
$stmt->bind_param("ssssi", $username, $email, $fecha, $sexo, $id);

$stmt->execute();
header("Location: central.php");
exit;