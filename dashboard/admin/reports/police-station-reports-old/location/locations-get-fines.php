<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
header("Content-Type: application/json");

require_once "../../../../../db/connect.php";
session_start();

$police_station_id = intval($_GET['policeStation'] ?? 0);
$period = $_GET['period'] ?? '';

if ($police_station_id == 0) {
    echo json_encode(["error" => "Invalid police station ID"]);
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

// Fetch total fines grouped by location
$query = "
        SELECT 
        CASE 
            WHEN f.location = '' OR f.location IS NULL THEN 'Unknown'
            WHEN dl.location_name IS NOT NULL THEN dl.location_name
            ELSE f.location
        END AS label,
        COUNT(*) AS count
        FROM fines f
        LEFT JOIN duty_locations dl ON f.location = dl.id
        WHERE CONCAT(f.issued_date, ' ', f.issued_time) >= NOW() - $interval and f.police_station = $police_station_id
        GROUP BY label
        ORDER BY count DESC;
        ";

$result = $conn->query($query);

if (!$result) {
    echo json_encode(["error" => "Database query failed", "details" => $conn->error]);
    exit;
}

$data = $result->fetch_all(MYSQLI_ASSOC);

// Return JSON
echo json_encode($data);
