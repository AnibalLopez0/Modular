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
        <button class="btn-pill" type="button" data-bs-toggle="modal" data-bs-target="#modalNuevaPlantilla">
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


<div class="modal fade" id="modalNuevaPlantilla" tabindex="-1"  aria-labelledby="modalNuevaPlantillaLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content"> 

            <div class="modal-header">
                <h5 class="modal-title" id="modalNuevaPlantillaLabel">Nueva Plantilla</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">

                <input type="text" class="form-control mb-3" id="tituloPlantilla" placeholder="Título">

                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" id="usaIntensidad">
                    <label class="form-check-label" for="usaIntensidad">Usa intensidad</label>
                </div>

                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="usaDuracion">
                    <label class="form-check-label" for="usaDuracion">Usa duración</label>
                </div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary rounded-pill" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn-pill" onclick="guardarPlantilla()">Guardar</button>
            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="modalEditarPlantilla" tabindex="-1" aria-labelledby="modalEditarPlantillaLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title" id="modalEditarPlantillaLabel">Editar Plantilla</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <input type="hidden" id="edit_id_plantilla">

        <input type="text" class="form-control mb-3" id="edit_titulo" placeholder="Título">

        <div class="form-check mb-2">
          <input class="form-check-input" type="checkbox" id="edit_intensidad">
          <label class="form-check-label" for="edit_intensidad">Usa intensidad</label>
        </div>

        <div class="form-check">
          <input class="form-check-input" type="checkbox" id="edit_duracion">
          <label class="form-check-label" for="edit_duracion">Usa duración</label>
        </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary rounded-pill" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" class="btn-pill" onclick="actualizarPlantilla()">Guardar cambios</button>
      </div>

    </div>
  </div>
</div>



<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
<script src="../Scripts/plantillas.js"></script>

</body>
</html>