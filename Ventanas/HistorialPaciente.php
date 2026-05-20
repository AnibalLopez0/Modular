<?php
session_start();

if ($_SESSION['rol'] != 'terapeuta') {
    header("Location: Central.php");
    exit();
}

$id_paciente = $_GET['id'] ?? null;

if (!$id_paciente) {
    die("Paciente no válido");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Historial</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
  <link rel="stylesheet" href="../CSS/estilosCentral.css">
</head>

<body>

  <div class="main-card">

    <div class="top-actions">
      <a href="Central.php" class="btn-pill">
        <i class="bi bi-arrow-left"></i> Volver
      </a>
    </div>

    <h1>Historial del paciente</h1>

    <div class="section-label">Registros</div>
    <div class="section-wrapper" id="listaHistorial">

    </div>

  </div>

  <script>
    const ID_PACIENTE = "<?php echo $id_paciente; ?>";
  </script>
  <script src="../Scripts/historialPaciente.js"></script>

</body>
</html>