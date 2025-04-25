<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header("Content-Type: application/json");

require_once "../../../../../db/connect.php";
session_start();

$period = $_GET['time_period'] ?? '';
$policeStationId = $_GET['police_station'] ?? null;
if (!$policeStationId) {
    echo json_encode(["error" => "Police station ID is required"]);
    exit;
}

// Set time interval
$interval = match ($period) {
    "24h" => "INTERVAL 24 HOUR",
    "72h" => "INTERVAL 72 HOUR",
    "7days" => "INTERVAL 7 DAY",
    "14days" => "INTERVAL 14 DAY",
    "30days" => "INTERVAL 30 DAY",
    "90days" => "INTERVAL 90 DAY",
    "365days" => "INTERVAL 365 DAY",
    default => "INTERVAL 9999 DAY"
};

// Fetch total fines grouped by offence type for the specific police station
$query = "
    SELECT offence_type AS label,
    COUNT(*) AS count
    FROM fines
    WHERE CONCAT(issued_date, ' ', issued_time) >= NOW() - $interval
    AND police_station = ?
    GROUP BY label
    ORDER BY count DESC;
";

// Use prepared statement to prevent SQL injection
$stmt = $conn->prepare($query);
if (!$stmt) {
    echo json_encode(["error" => "Database error: " . $conn->error]);
    exit;
}

$stmt->bind_param("i", $policeStationId);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_all(MYSQLI_ASSOC);

// Return JSON
echo json_encode($data);
