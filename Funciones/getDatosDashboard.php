<?php
session_start();
require_once "conn.php";

if (!isset($_SESSION['id'])) {
    die("No autorizado");
}

$id_paciente = $_GET['id'] ?? null;

if (!$id_paciente) {
    die("Paciente no válido");
}

$con = conecta();

// 🔥 traer conductas del paciente
$sql = "SELECT fecha, intensidad, duracion 
        FROM conductas
        WHERE id_paciente = ?
        ORDER BY fecha ASC";

$stmt = $con->prepare($sql);
$stmt->bind_param("i", $id_paciente);
$stmt->execute();

$result = $stmt->get_result();

$dias = [];

while ($row = $result->fetch_assoc()) {

    $fecha = $row['fecha'];

    if (!isset($dias[$fecha])) {
        $dias[$fecha] = [
            "frecuencia" => 0,
            "intensidades" => [],
            "duraciones" => []
        ];
    }

    $dias[$fecha]["frecuencia"]++;

    if ($row['intensidad'] !== null) {
        $dias[$fecha]["intensidades"][] = (int)$row['intensidad'];
    }

    if ($row['duracion'] !== null) {
        $dias[$fecha]["duraciones"][] = (int)$row['duracion'];
    }
}

// ==========================
// 🔄 PROCESAR DATOS
// ==========================

$labels = [];
$frecuencia = [];
$intensidad = [];
$duracion = [];

$totalInt = 0;
$totalDur = 0;
$countInt = 0;
$countDur = 0;

foreach ($dias as $fecha => $d) {

    $labels[] = $fecha;
    $frecuencia[] = $d["frecuencia"];

    // promedio intensidad
    if (count($d["intensidades"]) > 0) {
        $promInt = array_sum($d["intensidades"]) / count($d["intensidades"]);
        $intensidad[] = round($promInt, 1);

        $totalInt += array_sum($d["intensidades"]);
        $countInt += count($d["intensidades"]);
    } else {
        $intensidad[] = 0;
    }

    // promedio duración
    if (count($d["duraciones"]) > 0) {
        $promDur = array_sum($d["duraciones"]) / count($d["duraciones"]);
        $duracion[] = round($promDur);

        $totalDur += array_sum($d["duraciones"]);
        $countDur += count($d["duraciones"]);
    } else {
        $duracion[] = 0;
    }
}

// ==========================
// 📊 PROMEDIOS GENERALES
// ==========================

$promedioIntensidad = $countInt > 0 ? round($totalInt / $countInt, 1) : 0;
$promedioDuracion = $countDur > 0 ? round($totalDur / $countDur) : 0;

echo json_encode([
    "labels" => $labels,
    "frecuencia" => $frecuencia,
    "intensidad" => $intensidad,
    "duracion" => $duracion,
    "total" => array_sum($frecuencia),
    "promedioIntensidad" => $promedioIntensidad,
    "promedioDuracion" => $promedioDuracion
]);