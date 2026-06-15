<?php
session_start();
if (!isset($_SESSION['id'])) {
    header("Location: RegistroLogin.php");
    exit();
}
$id_terapeuta = $_SESSION['id'];
$id_paciente  = $_GET['id'] ?? null;
if (!$id_paciente) {
    header("Location: Central.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>Dashboard Conductas</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet"/>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>
<style>
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
  max-width: 860px;
}

.fila-encabezado {
  display: flex;
  align-items: center;
  gap: 1rem;
  margin-bottom: 1.75rem;
}
.btn-volver {
  border-radius: 50px;
  padding: 7px 18px;
  font-size: 0.85rem;
  font-weight: 600;
  border: none;
  background: #7c5cbf;
  color: #fff;
  text-decoration: none;
  display: inline-flex;
  align-items: center;
  gap: 5px;
  transition: background 0.18s;
  white-space: nowrap;
}
.btn-volver:hover { background: #6a4dab; color: #fff; }
.fila-encabezado h1 {
  font-size: 1.5rem;
  font-weight: 700;
  color: #1a1a2e;
  margin: 0;
}

/* Bloque por conducta */
.conducta-bloque {
  background: #f8f5fd;
  border-radius: 16px;
  padding: 1.25rem 1.5rem 1.5rem;
  margin-bottom: 1.5rem;
  animation: fadeUp 0.35s ease both;
}
@keyframes fadeUp {
  from { opacity: 0; transform: translateY(12px); }
  to   { opacity: 1; transform: translateY(0); }
}
.conducta-encabezado {
  display: flex;
  align-items: flex-start;
  justify-content: space-between;
  gap: 1rem;
  margin-bottom: 0.25rem;
}
.conducta-titulo { font-size: 1.05rem; font-weight: 700; color: #1a1a2e; }
.conducta-desc   { font-size: 0.82rem; color: #888; margin-bottom: 1rem; line-height: 1.5; }

/* Badge nivel */
.nivel-badge {
  font-size: 0.72rem;
  font-weight: 700;
  text-transform: uppercase;
  padding: 3px 12px;
  border-radius: 20px;
  white-space: nowrap;
}
.nivel-bajo  { background: #d4f7e4; color: #1a7a45; }
.nivel-medio { background: #fff3cd; color: #856404; }
.nivel-alto  { background: #fde8e8; color: #c0392b; }

/* Métricas */
.metricas { display: flex; gap: 0.75rem; margin-bottom: 1.1rem; flex-wrap: wrap; }
.metrica {
  background: #fff;
  border-radius: 12px;
  padding: 0.6rem 1rem;
  flex: 1;
  min-width: 140px;
  box-shadow: 0 1px 4px rgba(100,70,180,0.07);
}
.metrica-label { font-size: 0.7rem; color: #aaa; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 2px; }
.metrica-valor { font-size: 1.25rem; font-weight: 700; color: #1a1a2e; }
.metrica-valor.bajo  { color: #1a7a45; }
.metrica-valor.medio { color: #856404; }
.metrica-valor.alto  { color: #c0392b; }
.metrica-sub   { font-size: 0.68rem; color: #aaa; }

/* Barra de probabilidad */
.prob-barra-wrap {
  background: #fff;
  border-radius: 12px;
  padding: 0.75rem 1rem;
  margin-bottom: 1.1rem;
  box-shadow: 0 1px 4px rgba(100,70,180,0.07);
}
.prob-barra-label {
  display: flex;
  justify-content: space-between;
  font-size: 0.75rem;
  color: #888;
  margin-bottom: 6px;
}
.prob-barra-label span:last-child { font-weight: 700; color: #1a1a2e; }
.prob-barra-bg {
  background: #ede8f7;
  border-radius: 10px;
  height: 10px;
  overflow: hidden;
}
.prob-barra-fill {
  height: 100%;
  border-radius: 10px;
  transition: width 0.8s ease;
}

/* Leyenda */
.leyenda { display: flex; gap: 1rem; margin-bottom: 0.75rem; flex-wrap: wrap; }
.leyenda-item { display: flex; align-items: center; gap: 6px; font-size: 0.75rem; color: #888; }
.leyenda-dot  { width: 10px; height: 10px; border-radius: 50%; }

/* Gráficas */
.tarjeta-grafica {
  background-color: #ffffff;
  border-radius: 12px;
  padding: 1rem 1.1rem 0.9rem;
  box-shadow: 0 1px 4px rgba(100,70,180,0.07);
}
.titulo-grafica { font-size: 0.88rem; font-weight: 600; color: #555; margin-bottom: 0.5rem; }
.contenedor-canvas        { height: 155px; }
.contenedor-canvas.pequeno { height: 135px; }

.modelo-tag { font-size: 0.68rem; color: #bbb; margin-top: 0.75rem; text-align: right; }

/* Estado */
.estado { text-align: center; padding: 3rem 0; color: #aaa; }
.spinner {
  width: 32px; height: 32px;
  border: 3px solid #e8e1f5;
  border-top-color: #7c5cbf;
  border-radius: 50%;
  animation: spin .8s linear infinite;
  margin: 0 auto 1rem;
}
@keyframes spin { to { transform: rotate(360deg); } }
</style>
</head>
<body>
<div class="tarjeta-principal">

  <div class="fila-encabezado">
    <a href="javascript:history.back()" class="btn-volver">← Volver</a>
    <h1>Dashboard del Paciente</h1>
  </div>

  <div id="estado" class="estado">
    <div class="spinner"></div>
    <p>Cargando conductas...</p>
  </div>

  <div id="contenido"></div>

</div>

<script>
const ID_TERAPEUTA = <?= (int)$id_terapeuta ?>;
const ID_PACIENTE  = <?= (int)$id_paciente ?>;
const API_BASE     = "http://<?= $_SERVER['HTTP_HOST'] ?>:5000";

const COLOR_HIST = "#7b5ea7";
const COLOR_PRED = "#e0930a";

async function cargarDashboard() {
  try {
    const res = await fetch(`${API_BASE}/dashboard/${ID_TERAPEUTA}/${ID_PACIENTE}`);
    if (!res.ok) throw new Error("Error " + res.status);
    const data = await res.json();

    document.getElementById("estado").style.display = "none";

    if (!data.conductas || data.conductas.length === 0) {
      document.getElementById("estado").style.display = "block";
      document.getElementById("estado").innerHTML = "<p>No hay conductas registradas para este paciente.</p>";
      return;
    }

    data.conductas.forEach((c, i) => renderConducta(c, i));

  } catch (e) {
    document.getElementById("estado").innerHTML =
      `<p style="color:#c0392b">Error al conectar con el servidor: ${e.message}</p>`;
  }
}

function renderConducta(c, idx) {
  const cont = document.getElementById("contenido");
  const div  = document.createElement("div");
  div.className = "conducta-bloque";
  div.style.animationDelay = (idx * 80) + "ms";

  if (c.error) {
    div.innerHTML = `
      <div class="conducta-encabezado">
        <div class="conducta-titulo">${c.titulo}</div>
        <span class="nivel-badge nivel-bajo">Sin datos suficientes</span>
      </div>
      <p class="conducta-desc">${c.error}</p>`;
    cont.appendChild(div);
    return;
  }

  const nivelClass = { bajo:"nivel-bajo", medio:"nivel-medio", alto:"nivel-alto" }[c.nivel] || "nivel-bajo";
  const probPct    = Math.round(c.probabilidad * 100);
  const barColor   = { bajo:"#4ade80", medio:"#facc15", alto:"#f87171" }[c.nivel] || "#4ade80";

  const idFrec = `frec-${idx}`;
  const idDur  = `dur-${idx}`;

  div.innerHTML = `
    <div class="conducta-encabezado">
      <div class="conducta-titulo">${c.titulo}</div>
      <span class="nivel-badge ${nivelClass}">Probabilidad ${c.nivel} · ${probPct}%</span>
    </div>
    ${c.descripcion ? `<p class="conducta-desc">${c.descripcion}</p>` : ""}

    <!-- Barra de probabilidad -->
    <div class="prob-barra-wrap">
      <div class="prob-barra-label">
        <span>Probabilidad de ocurrencia — próxima semana (${c.prediccion.proxima_semana})</span>
        <span>${probPct}%</span>
      </div>
      <div class="prob-barra-bg">
        <div class="prob-barra-fill" style="width:${probPct}%; background:${barColor}"></div>
      </div>
    </div>

    <!-- Métricas -->
    <div class="metricas">
      <div class="metrica">
        <div class="metrica-label">Episodios predichos</div>
        <div class="metrica-valor ${c.nivel}">${c.prediccion.episodios}</div>
        <div class="metrica-sub">próxima semana</div>
      </div>
      <div class="metrica">
        <div class="metrica-label">Promedio histórico</div>
        <div class="metrica-valor">${c.prediccion.promedio_historico}</div>
        <div class="metrica-sub">episodios / semana</div>
      </div>
      <div class="metrica">
        <div class="metrica-label">Registros totales</div>
        <div class="metrica-valor">${c.historial.fechas.length}</div>
        <div class="metrica-sub">modelo: ${c.modelo}</div>
      </div>
    </div>

    <!-- Leyenda -->
    <div class="leyenda">
      <div class="leyenda-item">
        <div class="leyenda-dot" style="background:${COLOR_HIST}"></div>Historial semanal
      </div>
      <div class="leyenda-item">
        <div class="leyenda-dot" style="background:${COLOR_PRED}; border-radius:2px"></div>Predicción próx. semana
      </div>
    </div>

    <!-- Gráficas -->
    <div class="row g-3">
      <div class="col-12">
        <div class="tarjeta-grafica">
          <div class="titulo-grafica">Frecuencia semanal</div>
          <div class="contenedor-canvas"><canvas id="${idFrec}"></canvas></div>
        </div>
      </div>
      ${c.usa_duracion ? `
      <div class="col-12">
        <div class="tarjeta-grafica">
          <div class="titulo-grafica">Duración por episodio (min)</div>
          <div class="contenedor-canvas pequeno"><canvas id="${idDur}"></canvas></div>
        </div>
      </div>` : ""}
    </div>

    <div class="modelo-tag">modelo: ${c.modelo}</div>
  `;

  cont.appendChild(div);

  const commonOpts = () => ({
    responsive: true,
    maintainAspectRatio: false,
    interaction: { mode: "index", intersect: false },
    plugins: { legend: { display: false } },
    scales: {
      x: { grid: { color: "rgba(0,0,0,0.04)" }, ticks: { maxTicksLimit: 8, maxRotation: 0, font: { family: "'DM Sans'" } } },
      y: { grid: { color: "rgba(0,0,0,0.04)" }, beginAtZero: true, ticks: { font: { family: "'DM Sans'" } } }
    }
  });

  // Frecuencia semanal + predicción
  const semanas     = [...c.historial.semanas, c.prediccion.proxima_semana];
  const freqHist    = [...c.historial.freq_semanal, null];
  const freqPred    = [
    ...Array(c.historial.semanas.length - 1).fill(null),
    c.historial.freq_semanal[c.historial.freq_semanal.length - 1],
    c.prediccion.episodios
  ];

  new Chart(document.getElementById(idFrec), {
    type: "line",
    data: {
      labels: semanas,
      datasets: [
        {
          label: "Historial",
          data: freqHist,
          borderColor: COLOR_HIST,
          backgroundColor: COLOR_HIST + "22",
          pointRadius: 4,
          tension: 0.4,
          fill: true,
          spanGaps: false,
        },
        {
          label: "Predicción",
          data: freqPred,
          borderColor: COLOR_PRED,
          backgroundColor: COLOR_PRED + "18",
          borderDash: [5, 4],
          pointRadius: 5,
          pointStyle: "rectRot",
          tension: 0.4,
          fill: true,
          spanGaps: false,
        }
      ]
    },
    options: commonOpts()
  });

  // Duración
  if (c.usa_duracion && document.getElementById(idDur)) {
    new Chart(document.getElementById(idDur), {
      type: "bar",
      data: {
        labels: c.historial.fechas,
        datasets: [{
          label: "Duración",
          data: c.historial.duracion,
          backgroundColor: COLOR_HIST + "99",
          borderColor: COLOR_HIST,
          borderWidth: 1,
          borderRadius: 4,
        }]
      },
      options: commonOpts()
    });
  }
}

cargarDashboard();
</script>
</body>
</html>
