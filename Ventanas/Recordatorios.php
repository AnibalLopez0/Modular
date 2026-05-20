<?php
session_start();

if ($_SESSION['rol'] != 'paciente') {
    header("Location: Central.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Recordatorios</title>
    <link rel="stylesheet" href="../CSS/estilosCentral.css">
</head>

<body>

<div class="container">

    <div class="panel">

        <h2>Recordatorios</h2>

        <div id="listaRecordatorios"></div>

        <br>
        <a href="Central.php">← Volver</a>

    </div>

</div>

<script src="../Scripts/recordatorios.js"></script>

</body>
</html>