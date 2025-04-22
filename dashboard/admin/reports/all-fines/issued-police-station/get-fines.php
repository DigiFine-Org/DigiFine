<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header("Content-Type: application/json");

require_once "../../../../../db/connect.php";
session_start();

$period = $_GET['time_period'] ?? '';

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
    COALESCE(ps.name, 'Unknown') AS label,
    COUNT(*) AS count
    FROM fines f
    LEFT JOIN police_stations ps ON f.police_station = ps.id
    WHERE CONCAT(f.issued_date, ' ', f.issued_time) >= NOW() - $interval
    GROUP BY label
    ORDER BY count DESC;
";

$result = $conn->query($query);
$data = $result->fetch_all(MYSQLI_ASSOC);

// Return JSON
echo json_encode($data);
