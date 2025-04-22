<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header("Content-Type: application/json");

require_once "../../../../db/connect.php";
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

$group_by = ($period === "24h" || $period === "72h")
    ? "DATE_FORMAT(CONCAT(issued_date, ' ', issued_time), '%Y-%m-%d %H:00')"
    : "DATE(issued_date)";

// Fetch Fines with offence_type = 'fine'
$queryFines = "
    SELECT 
        $group_by AS label,
        COUNT(*) AS count
    FROM fines
    WHERE offence_type = 'fine'
      AND CONCAT(issued_date, ' ', issued_time) >= NOW() - $interval
    GROUP BY label
    ORDER BY label ASC;
";
$resultFines = $conn->query($queryFines);
$dataFines = $resultFines->fetch_all(MYSQLI_ASSOC);

// Fetch Court Cases with offence_type = 'court'
$queryCourt = "
    SELECT 
        $group_by AS label,
        COUNT(*) AS count
    FROM fines
    WHERE offence_type = 'court'
      AND CONCAT(issued_date, ' ', issued_time) >= NOW() - $interval
    GROUP BY label
    ORDER BY label ASC;
";
$resultCourt = $conn->query($queryCourt);
$dataCourt = $resultCourt->fetch_all(MYSQLI_ASSOC);

// Combine into JSON response
$response = [
    "Fines" => $dataFines,
    "Court" => $dataCourt
];

echo json_encode($response);
