<?php
session_start();
require_once "conn.php";

//  Validar rol
if ($_SESSION['rol'] != 'terapeuta') {
    die("Acceso denegado");
}

$con = conecta();

// 📥 Datos
$id_remitente = $_SESSION['id'];
$id_paciente = $_POST['id_paciente'] ?? null;
$mensaje = $_POST['mensaje'] ?? '';

//  Validaciones básicas
if (!$id_paciente || trim($mensaje) == "") {
    die("Datos incompletos");
}

//  Query
$sql = "INSERT INTO recordatorios 
        (id_remitente, id_paciente, mensaje, fecha, is_active)
        VALUES (?, ?, ?, NOW(), 1)";

$stmt = $con->prepare($sql);
$stmt->bind_param("iis", $id_remitente, $id_paciente, $mensaje);

// 🚀 Ejecutar
if ($stmt->execute()) {
    echo "Recordatorio enviado correctamente";
} else {
    echo "Error al enviar recordatorio";
}