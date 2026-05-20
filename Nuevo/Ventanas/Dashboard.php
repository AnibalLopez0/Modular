<?php
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: RegistroLogin.php");
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
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>Dashboard Conducta</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet"/>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>

<style>

.selector-periodo {
  display: none;
}

* { box-sizing: border-box; }

body {
  background-color: #ddd5f0;
  font-family: 'DM Sans', sans-serif;
  min-height: 100vh;
  display: flex;
  align-items: flex-start;
  justify-content: center;
  padding: 2rem 1rem;
}

.tarjeta-principal {
  background-color: #ffffff;
  border-radius: 18px;
  box-shadow: 0 4px 24px rgba(100, 80, 160, 0.08);
  padding: 1.75rem 2rem 2rem;
  width: 100%;
  max-width: 820px;
}

.fila-encabezado {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 1.5rem;
}

.selector-periodo {
  background: #f0ecf8;
  border: 1px solid #d8d0ec;
  border-radius: 8px;
  padding: 5px 12px;
}

.tarjeta-grafica {
  background-color: #f8f5fd;
  border-radius: 14px;
  padding: 1.1rem 1.25rem 1rem;
}

.titulo-grafica {
  font-size: 0.92rem;
  font-weight: 600;
  color: #555;
}

.valor-grafica {
  font-size: 1.6rem;
  font-weight: 700;
  margin-bottom: 0.75rem;
}

.contenedor-canvas {
  height: 160px;
}

.pequeno {
  height: 140px;
}
</style>

</head>

<body>

<div class="tarjeta-principal">

  <div class="fila-encabezado">
    <h1>Dashboard del Paciente</h1>
    <select class="selector-periodo">
      <option>Últimos 7 días</option>
      <option>Últimas 2 semanas</option>
      <option>Último mes</option>
    </select>
  </div>

  <!-- FRECUENCIA -->
  <div class="row g-3 mb-3">
    <div class="col-12">
      <div class="tarjeta-grafica">
        <div class="titulo-grafica">Frecuencia</div>
        <div class="valor-grafica" id="valorFrecuencia"></div>
        <div class="contenedor-canvas">
          <canvas id="graficaFrecuencia"></canvas>
        </div>
      </div>
    </div>
  </div>

  <!-- INTENSIDAD + DURACION -->
  <div class="row g-3">
    <div class="col-12 col-md-6">
      <div class="tarjeta-grafica">
        <div class="titulo-grafica">Intensidad</div>
        <div class="valor-grafica" id="valorIntensidad"></div>
        <div class="contenedor-canvas pequeno">
          <canvas id="graficaIntensidad"></canvas>
        </div>
      </div>
    </div>

    <div class="col-12 col-md-6">
      <div class="tarjeta-grafica">
        <div class="titulo-grafica">Duración</div>
        <div class="valor-grafica" id="valorDuracion"></div>
        <div class="contenedor-canvas pequeno">
          <canvas id="graficaDuracion"></canvas>
        </div>
      </div>
    </div>
  </div>

</div>

<script>
const ID_PACIENTE = <?php echo $id_paciente; ?>;
</script>

<script src="../Scripts/dashboard.js"></script>

</body>
</html>