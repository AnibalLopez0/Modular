<?php
session_start();
require_once "conn.php";

if ($_SESSION['rol'] != 'terapeuta') {
    die("Acceso denegado");
}

$con = conecta();

// 🔹 Recibir datos
$id = $_POST['id'];
$titulo = $_POST['titulo'];
$usa_intensidad = $_POST['intensidad'];
$usa_duracion = $_POST['duracion'];

// 🔹 Query UPDATE
$sql = "UPDATE conductas_plantilla 
        SET titulo = ?, usa_intensidad = ?, usa_duracion = ?
        WHERE id_plantilla = ?";

$stmt = $con->prepare($sql);
$stmt->bind_param("siii", $titulo, $usa_intensidad, $usa_duracion, $id);

// 🔹 Ejecutar
if ($stmt->execute()) {
    echo "Plantilla actualizada correctamente";
} else {
    echo "Error al actualizar plantilla";
}