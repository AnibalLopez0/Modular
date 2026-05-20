<?php
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: ../Ventanas/RegistroLogin.php");
    exit();
}

$rol = $_SESSION['rol'];
$nombre = $_SESSION['nombre'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Conductas</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet"/>
  <link rel="stylesheet" href="../CSS/estilosCentral.css">
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet"/>

</head>
<body>

<div class="main-card">

  <?php if ($rol == 'terapeuta'): ?>

    <!-- PANEL TERAPEUTA -->

    <h1>¡Bienvenido, <?php echo $nombre; ?>!</h1>

    <!-- TOP BOTONES -->

    <div class="top-actions">
      <button class="btn-pill" onclick="irPlantillas()">Gestionar plantillas</button>
      <button id="btnNuevoRecordatorio" onclick="cargarPacientes()" class="btn-pill" data-bs-toggle="modal" data-bs-target="#modalEnviarRecordatorio">
        Enviar recordatorio
      </button>
      <button id="btnNuevoPaciente" class="btn-pill" data-bs-toggle="modal" data-bs-target="#modalNuevoPaciente">
        Nuevo <i class="bi bi-plus-lg"></i>
      </button>
      
      <button class="btn btn-outline-secondary rounded-pill ms-auto" 
      onclick="window.location.href='../Funciones/logout.php'" >Cerrar sesión
      </button>
    </div>

    <!-- ACTIVOS -->
    <div class="section-label">Activos</div>
    <div class="section-wrapper">

    <div id="activos"></div>

    </div>

    <!-- NO ACTIVOS -->
    <div class="section-label">No Activos</div>
    <div class="section-wrapper">
      
      <div id="inactivos"></div>

    </div>

  <?php else: ?>
    <!--PANEL PACIENTE -->
      <h1>¡Bienvenido!</h1>

    <div class="top-actions">
      <button class="btn-pill" onclick="verHistorialCompleto()">Ver historial completo</button>
      <button id="btnNuevoPaciente" onclick="irRecordatorios()" class="btn-pill">Ir a recordatorios</button>
      <button id="btnNuevoRecordatorio" onclick="abrirSelectorPlantilla()" type="button" class="btn-pill">
        Nuevo <i class="bi bi-plus-lg"></i>
      </button>
      <button class="btn btn-outline-secondary rounded-pill ms-auto" 
      onclick="window.location.href='../Funciones/logout.php'" >Cerrar sesión
      </button>
    </div>

    <!-- ACTIVOS -->
    <div class="section-label">Activos</div>
    <div class="section-wrapper">

      <div class="conducta-row">
        <a href="#" class="icon-btn" title="Configurar"><i class="bi bi-gear-fill"></i></a>
        <a href="#" class="icon-btn danger" title="Eliminar"><i class="bi bi-trash-fill"></i></a>
        <span class="conducta-name">Conducta 1</span>
        <a href="#" class="icon-btn" title="Agregar"><i class="bi bi-plus-lg"></i></a>
        <a href="#" class="icon-btn" title="Calendario"><i class="bi bi-calendar-fill"></i></a>
      </div>

    </div>

    <!-- NO ACTIVOS -->
    <div class="section-label">No Activos</div>
    <div class="section-wrapper">

      <div class="conducta-row">
        <a href="#" class="icon-btn" title="Configurar"><i class="bi bi-gear-fill"></i></a>
        <a href="#" class="icon-btn danger" title="Eliminar"><i class="bi bi-trash-fill"></i></a>
        <span class="conducta-name">Conducta 2</span>
        <a href="#" class="icon-btn" title="Agregar"><i class="bi bi-plus-lg"></i></a>
        <a href="#" class="icon-btn" title="Calendario"><i class="bi bi-calendar-fill"></i></a>
      </div>

    </div>

  <?php endif; ?>

</div>

<br>

<!-- MODALS -->

<!-- NUEVO PACIENTE -->
<div class="modal fade" id="modalNuevoPaciente" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header">
        <h1 class="modal-title fs-5">Nuevo Paciente</h1>
      </div>

      <div class="modal-body">
        <input id="username" class="form-control mb-2" type="text" placeholder="Username">
        <input id="email" class="form-control mb-2" type="email" placeholder="Correo">
        <input id="password" class="form-control" type="password" placeholder="Contraseña">
      </div>

      <div class="modal-footer">
        <button class="btn btn-outline-secondary rounded-pill" data-bs-dismiss="modal">Cancelar</button>
        <button class="btn-pill" onclick="guardarPaciente()">Guardar</button>
      </div>

    </div>
  </div>
</div>

<!-- ENVIAR RECORDATORIO -->

<div class="modal fade" id="modalEnviarRecordatorio" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">Enviar Recordatorio</h5>

        <button 
          type="button" 
          class="btn-close" 
          data-bs-dismiss="modal">
        </button>
      </div>

      <div class="modal-body">

        <div class="mb-3">
          <label class="form-label">Paciente</label>

          <select id="rec_paciente" class="form-select">
            <option value="">Seleccionar paciente</option>
          </select>
        </div>

        <div class="mb-3">
          <label class="form-label">Mensaje</label>

          <textarea
            id="rec_mensaje"
            class="form-control"
            rows="4"
            placeholder="Escribe el mensaje..."></textarea>
        </div>

      </div>

      <div class="modal-footer">

        <button 
          type="button"
          class="btn btn-outline-secondary rounded-pill"
          data-bs-dismiss="modal">
          Cancelar
        </button>

        <button 
          type="button"
          class="btn-pill"
          onclick="enviarRecordatorio()">
          Enviar
        </button>
      </div>
    </div>
  </div>
</div>

<!-- REGISTRO CONDUCTA -->

<div class="modal fade" id="modalRegistro" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title" id="reg_titulo"></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <input type="hidden" id="reg_id_plantilla">

        <textarea id="reg_descripcion" class="form-control mb-3" placeholder="Descripción"></textarea>

        <div id="campoIntensidad" class="mb-3">
          <input type="number" id="reg_intensidad" class="form-control" placeholder="Intensidad">
        </div>

        <div id="campoDuracion">
          <input type="number" id="reg_duracion" class="form-control" placeholder="Duración">
        </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary rounded-pill" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" class="btn-pill" onclick="guardarRegistro()">Guardar</button>
      </div>

    </div>
  </div>
</div>

<!-- EDITAR PACIENTE -->

<div class="modal fade" id="modalEditarPaciente" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Editar Paciente</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <input type="hidden" id="edit_id">

                <div class="mb-3">
                    <input type="text" class="form-control" id="edit_username" placeholder="Nombre de usuario">
                </div>

                <div class="mb-3">
                    <input type="email" class="form-control" id="edit_email" placeholder="Correo">
                </div>

                <div class="mb-3">
                    <input type="date" class="form-control" id="edit_fecha">
                </div>

                <div class="mb-3">
                    <select class="form-select" id="edit_sexo">
                        <option value="">Sexo</option>
                        <option value="M">Masculino</option>
                        <option value="F">Femenino</option>
                        <option value="O">Otro</option>
                    </select>
                </div>
            </div>

            <div class="modal-footer">
              <button type="button" class="btn btn-outline-secondary rounded-pill" data-bs-dismiss="modal">Cancelar</button>
              <button type="button" class="btn-pill" onclick="actualizarPaciente()">Guardar cambios</button>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
const ROL = "<?php echo $rol; ?>";
</script>

<script src="../Scripts/central.js"></script>
</body>
</html>