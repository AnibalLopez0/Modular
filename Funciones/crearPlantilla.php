<?php
session_start();
require_once "conn.php";

if ($_SESSION['rol'] != 'terapeuta') {
    die("Acceso denegado");
}

$con = conecta();

$titulo = $_POST['titulo'];
$usa_intensidad = $_POST['intensidad'];
$usa_duracion = $_POST['duracion'];

$sql = "INSERT INTO conductas_plantilla 
        (titulo, usa_intensidad, usa_duracion, is_active)
        VALUES (?, ?, ?, 1)";

$stmt = $con->prepare($sql);
$stmt->bind_param("sii", $titulo, $usa_intensidad, $usa_duracion);

if (!$stmt->execute()) {
    echo "Plantilla creada correctamente";
} else {
    echo "Error al crear plantilla";
}