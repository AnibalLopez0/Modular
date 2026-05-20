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
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Historial</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../CSS/estilosCentral.css">
</head>
<body>

<div class="main-card">

    <h1>Historial de Conductas</h1>

    <div class="top-actions">
        <a href="Central.php" class="btn-pill">← Volver</a>
    </div>

    <p class="section-label">Registros</p>
    <div class="section-wrapper" id="listaHistorial"></div>

</div>

<script src="../Scripts/Historial.js"></script>
</body>
</html>