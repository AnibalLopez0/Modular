<?php
session_start();
require_once "conn.php";

if ($_SESSION['rol'] != 'terapeuta') {
    die("Acceso denegado");
}

$con = conecta();

$username = $_POST['username'];
$email = $_POST['email'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);

$id_terapeuta = $_SESSION['id'];

// Insertar usuario (paciente)
$sql = "INSERT INTO usuarios (username, email, password, rol)
        VALUES (?, ?, ?, 'paciente')";

$stmt = $con->prepare($sql);
$stmt->bind_param("sss", $username, $email, $password);

if ($stmt->execute()) {

    $id_paciente = $stmt->insert_id;

    // Crear relación
    $sql2 = "INSERT INTO relaciones (id_terapeuta, id_paciente, fecha_inicio, is_active)
             VALUES (?, ?, NOW(), 1)";

    $stmt2 = $con->prepare($sql2);
    $stmt2->bind_param("ii", $id_terapeuta, $id_paciente);
    $stmt2->execute();


} else {
    echo "Error al crear paciente";
}