<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header("Content-Type: application/json");

require_once "../../../../../db/connect.php";
session_start();

$police_id = $_GET['police_id'] ?? '';
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

// Fetch All Submissions
$queryAllSubmissions = "
    SELECT 
        DATE(created_at) AS label,
        COUNT(*) AS count
    FROM duty_submissions
    WHERE created_at >= NOW() - $interval
        AND police_id = $police_id
    GROUP BY label
    ORDER BY label ASC;
";
$resultAllSubmissions = $conn->query($queryAllSubmissions);
$dataAllSubmissions = $resultAllSubmissions->fetch_all(MYSQLI_ASSOC);

// Fetch Late Submissions
$queryLateSubmissions = "
    SELECT 
        DATE(created_at) AS label,
        COUNT(*) AS count
    FROM duty_submissions
    WHERE created_at >= NOW() - $interval
        AND police_id = $police_id
        AND is_late_submission = 1
    GROUP BY label
    ORDER BY label ASC;
";
$resultLateSubmissions = $conn->query($queryLateSubmissions);
$dataLateSubmissions = $resultLateSubmissions->fetch_all(MYSQLI_ASSOC);

// Prepare response in the required format
$response = [
    "all_submissions" => $dataAllSubmissions,
    "late_submissions" => $dataLateSubmissions
];


echo json_encode($response);
