<?php
session_start();
require_once "conn.php";

if ($_SESSION['rol'] != 'terapeuta') {
    die("Acceso denegado");
}

$con = conecta();

$id = $_POST['id'];

$sql = "UPDATE conductas_plantilla 
        SET is_active = 1 
        WHERE id_plantilla = ?";

$stmt = $con->prepare($sql);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo "Plantilla restaurada";
} else {
    echo "Error al restaurar";
}