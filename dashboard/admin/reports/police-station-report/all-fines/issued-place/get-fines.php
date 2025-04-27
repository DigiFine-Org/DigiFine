<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header("Content-Type: application/json");

require_once "../../../../../../db/connect.php";
session_start();

$period = $_GET['time_period'] ?? '';
$policeStationId = $_GET['police_station'] ?? null;

// Check if police station ID is provided
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

// Fetch total fines grouped by location only for the specific police station
$query = "
        SELECT 
        CASE 
            WHEN f.location = '' OR f.location IS NULL THEN 'Unknown'
            WHEN dl.location_name IS NOT NULL THEN dl.location_name
            ELSE CONCAT('*', COALESCE(f.location, 'N/A'))
        END AS label,
        COUNT(*) AS count
        FROM fines f
        LEFT JOIN duty_locations dl ON f.location = dl.id
        WHERE CONCAT(f.issued_date, ' ', f.issued_time) >= NOW() - $interval
        AND f.police_station = ?
        GROUP BY label
        ORDER BY count DESC
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
