<?php
header("Content-Type: application/json");

// --- DB connection ---
$host = "localhost";
$user = "root";       // change if needed
$pass = "";           // change if needed
$db   = "etrike_db";  // your DB name

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die(json_encode(["error" => "DB Connection failed"]));
}

// --- Get last 10 sensor logs ---
$sql = "SELECT * FROM etrike_data ORDER BY timestamp DESC LIMIT 10";
$result = $conn->query($sql);

$logs = [];
$voltageArr = [];
$tempArr = [];
$labels = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $logs[] = $row;
        $labels[] = date("H:i", strtotime($row["timestamp"]));
        $voltageArr[] = $row["voltage"];
        $tempArr[] = $row["temperature"];
    }
}

// --- Simple Predictive Logic ---

// Assume efficiency = 5 km per Ah (adjust based on tests)
$latest = end($logs);
$voltage = $latest["voltage"];
$current = $latest["current"];
$temperature = $latest["temperature"];

// Range prediction (very simplified)
$capacityAh = 20; // example battery capacity
$efficiency = 5;  // km per Ah
$rangePrediction = round($capacityAh * $efficiency, 1);

// Battery health (SoH) approximation
$soh = rand(80, 100); // placeholder: replace with real cycle tracking

// Alerts
$alert = "OK";
if ($temperature > 60) {
    $alert = "⚠️ Motor Overheating!";
} elseif ($voltage < 42) {
    $alert = "⚠️ Low Battery!";
}

// --- Output JSON ---
echo json_encode([
    "range" => $rangePrediction,
    "soh" => $soh,
    "temperature" => $temperature,
    "alert" => $alert,
    "logs" => array_reverse($logs),  // reverse so newest is last
    "chart_labels" => array_reverse($labels),
    "voltage" => array_reverse($voltageArr),
    "temperature_series" => array_reverse($tempArr)
]);
