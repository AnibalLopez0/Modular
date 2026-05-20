<?php
session_start();
if ($_SESSION['rol'] != 'terapeuta') {
    header("Location: Central.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Plantillas</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="../CSS/estilosCentral.css">
</head>

<body>

<div class="main-card">

    <h1>Plantillas de Conducta</h1>

    <div class="top-actions">
        <button class="btn-pill" onclick="abrirModalNueva()">
            <i class="fa-solid fa-plus"></i> Nueva plantilla
        </button>
        <a href="Central.php" class="btn-pill" style="background: var(--gray-main);">
            <i class="fa-solid fa-arrow-left"></i> Volver
        </a>
    </div>

    <div class="section-label">Activas</div>
    <div class="section-wrapper" id="listaActivas"></div>

    <div class="section-label">Inactivas</div>
    <div class="section-wrapper" id="listaInactivas"></div>

</div>


    <div id="modalNuevaPlantilla" class="modal">
        <div class="modal-content">

            <h3>Nueva Plantilla</h3>

            <input type="text" id="tituloPlantilla" placeholder="Título">

            <label>
                <input type="checkbox" id="usaIntensidad">
                Usa intensidad
            </label>

            <label>
                <input type="checkbox" id="usaDuracion">
                Usa duración
            </label>

            <br><br>

            <button onclick="guardarPlantilla()">Guardar</button>
            <button onclick="cerrarModalNueva()">Cancelar</button>

        </div>

    </div>

    <div id="modalEditarPlantilla" class="modal">
        <div class="modal-content">

            <h3>Editar Plantilla</h3>

            <input type="hidden" id="edit_id_plantilla">

            <input type="text" id="edit_titulo" placeholder="Título">

            <label>
                <input type="checkbox" id="edit_intensidad">
                Usa intensidad
            </label>

            <label>
                <input type="checkbox" id="edit_duracion">
                Usa duración
            </label>

            <br><br>

            <button onclick="actualizarPlantilla()">Guardar cambios</button>
            <button onclick="document.getElementById('modalEditarPlantilla').style.display='none'">Cancelar</button>

        </div>
    </div>




<script src="../Scripts/plantillas.js"></script>

</body>
</html>