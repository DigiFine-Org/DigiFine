<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header("Content-Type: application/json");

require_once "../../../../../db/connect.php";
session_start();

$period = $_GET['time_period'] ?? '';
if (!$period) {
    echo json_encode(["error" => "Time period is required"]);
    exit;
}
$policeStationId = $_GET['police_station'] ?? null;

if (!$policeStationId) {
    echo json_encode(["error" => "Police station ID is required"]);
    exit;
}

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

// Fetch total fines grouped by location for the specific police station
$query = "
    SELECT o.offence_number, o.description_english AS label,
    SUM(f.fine_amount) AS count
    FROM fines f
    LEFT JOIN offences o ON f.offence = o.offence_number
    WHERE CONCAT(f.issued_date, ' ', f.issued_time) >= NOW() - $interval 
    AND f.offence IS NOT NULL
    AND o.offence_number IS NOT NULL
    AND f.police_station = ?
    GROUP BY o.offence_number
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
