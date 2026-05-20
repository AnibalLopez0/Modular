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
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Recordatorios</title>
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet"/>
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
      max-width: 620px;
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

    /* Lista recordatorios */
    #listaRecordatorios {
      display: flex;
      flex-direction: column;
      gap: 0.75rem;
    }

    /* Cada recordatorio lo genera el JS, estos estilos los aplica */
    .recordatorio-item {
      background: #f8f5fd;
      border-radius: 12px;
      padding: 1rem 1.25rem;
      display: flex;
      align-items: flex-start;
      gap: 0.85rem;
      transition: background 0.15s;
    }
    .recordatorio-item:hover { background: #ede8f7; }

    .recordatorio-icono {
      width: 36px;
      height: 36px;
      background: #e6dff7;
      border-radius: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.1rem;
      color: #7c5cbf;
      flex-shrink: 0;
    }

    .recordatorio-body { flex: 1; }

    .recordatorio-mensaje {
      font-size: 0.92rem;
      font-weight: 500;
      color: #1a1a2e;
      margin-bottom: 3px;
    }

    .recordatorio-fecha {
      font-size: 0.75rem;
      color: #aaa;
    }

    /* Vacío */
    .sin-datos {
      text-align: center;
      padding: 2.5rem 0;
      color: #b0a0d0;
      font-size: 0.9rem;
      font-weight: 500;
    }
    .sin-datos i { font-size: 2rem; display: block; margin-bottom: 0.5rem; }
  </style>
</head>
<body>

<div class="tarjeta-principal">
  <div class="fila-encabezado">
    <a href="Central.php" class="btn-volver">← Volver</a>
    <h1>Recordatorios</h1>
  </div>

  <div id="listaRecordatorios">
    <div class="sin-datos">
      <i class="bi bi-bell"></i>
      Cargando recordatorios...
    </div>
  </div>
</div>

<script src="../Scripts/recordatorios.js"></script>
</body>
</html>
