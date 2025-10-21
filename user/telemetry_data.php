<?php
header('Content-Type: application/json');
include '../src/connection.php'; // ✅ your DB connection

// --- PARAMETERS ---
$owner_id = 1; // temporary until login
$week_start = $_GET['start'] ?? '2025-10-20';
$week_end   = $_GET['end'] ?? '2025-10-26';

// --- Fetch battery data ---
$battery = [];
$batteryTemp = [];
$batteryQuery = $mysqli->prepare("
    SELECT voltage, temperature
    FROM battery
    WHERE owner_id = ? 
    AND recorded_at BETWEEN ? AND ?
    ORDER BY recorded_at ASC
");
$batteryQuery->bind_param("iss", $owner_id, $week_start, $week_end);
$batteryQuery->execute();
$batteryResult = $batteryQuery->get_result();
if ($batteryResult && $batteryResult->num_rows > 0) {
    while ($row = $batteryResult->fetch_assoc()) {
        $battery[] = $row['voltage'];
        $batteryTemp[] = $row['temperature'];
    }
}

// --- Fetch motor data ---
$vibration = [];
$motorTemp = [];
$motorQuery = $mysqli->prepare("
    SELECT vibration, temperature
    FROM motor
    WHERE owner_id = ? 
    AND recorded_at BETWEEN ? AND ?
    ORDER BY recorded_at ASC
");
$motorQuery->bind_param("iss", $owner_id, $week_start, $week_end);
$motorQuery->execute();
$motorResult = $motorQuery->get_result();
if ($motorResult && $motorResult->num_rows > 0) {
    while ($row = $motorResult->fetch_assoc()) {
        $vibration[] = $row['vibration'];
        $motorTemp[] = $row['temperature'];
    }
}

// --- Fetch mileage data ---
$speed = [];
$travel_time = [];
$mileageQuery = $mysqli->prepare("
    SELECT average_speed, travel_time
    FROM mileage
    WHERE owner_id = ? 
    AND recorded_at BETWEEN ? AND ?
    ORDER BY recorded_at ASC
");
$mileageQuery->bind_param("iss", $owner_id, $week_start, $week_end);
$mileageQuery->execute();
$mileageResult = $mileageQuery->get_result();
if ($mileageResult && $mileageResult->num_rows > 0) {
    while ($row = $mileageResult->fetch_assoc()) {
        $speed[] = $row['average_speed'];
        $travel_time[] = $row['travel_time'];
    }
}

// --- Fetch tire data ---
$tire = [];
$tireQuery = $mysqli->prepare("
    SELECT pressure
    FROM tire
    WHERE owner_id = ? 
    AND recorded_at BETWEEN ? AND ?
    ORDER BY recorded_at ASC
");
$tireQuery->bind_param("iss", $owner_id, $week_start, $week_end);
$tireQuery->execute();
$tireResult = $tireQuery->get_result();
if ($tireResult && $tireResult->num_rows > 0) {
    while ($row = $tireResult->fetch_assoc()) {
        $tire[] = $row['pressure'];
    }
}

// --- Generate labels for 7 days ---
$labels = [];
$start = new DateTime($week_start);
for ($i = 0; $i < 7; $i++) {
    $labels[] = $start->format('D');
    $start->modify('+1 day');
}

// --- Output JSON ---
echo json_encode([
    "labels" => $labels,
    "battery" => $battery,
    "vibration" => $vibration,
    "speed" => $speed,
    "time" => $travel_time,
    "temperature" => [
        "battery" => $batteryTemp,
        "motor" => $motorTemp
    ],
    "tire" => $tire,
    "start" => $week_start,
    "end" => $week_end
]);

// Optional cleanup
$batteryQuery->close();
$motorQuery->close();
$mileageQuery->close();
$tireQuery->close();
$mysqli->close();
