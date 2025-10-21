<?php
header('Content-Type: application/json');

// Example telemetry data (for one week)
$data = [
    "labels" => ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"],
    "battery" => [48, 47.8, 47.6, 47.4, 47.2, 47.1, 46.9],
    "vibration" => [2.1, 2.3, 2.2, 2.5, 2.4, 2.6, 2.3],
    "speed" => [45, 50, 48, 52, 55, 60, 58],
    "time" => [2.5, 3, 2, 4, 3.5, 5, 4.5],
    "temperature" => [34, 35, 33, 36, 35, 37, 34]
];

echo json_encode($data);
?>
