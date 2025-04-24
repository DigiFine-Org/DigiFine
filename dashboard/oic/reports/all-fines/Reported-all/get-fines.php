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

// Grouping format
$group_by = ($period === "24h" || $period === "72h")
    ? "DATE_FORMAT(CONCAT(issued_date, ' ', issued_time), '%Y-%m-%d %H:00')"
    : "DATE(issued_date)";

// Function to fetch data
function fetchFines($conn, $interval, $group_by, $is_reported = null)
{
    $query = "SELECT $group_by AS label, COUNT(*) AS count 
              FROM fines 
              WHERE CONCAT(issued_date, ' ', issued_time) >= NOW() - $interval";

    if (!is_null($is_reported)) {
        $query .= " AND is_reported = ?";
    }

    $query .= " GROUP BY label ORDER BY label ASC";

    $stmt = $conn->prepare($query);
    if (!is_null($is_reported)) {
        $stmt->bind_param("i", $is_reported);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Fetch both datasets
$allFines = fetchFines($conn, $interval, $group_by); // all fines
$reportedFines = fetchFines($conn, $interval, $group_by, 1); // reported fines

// Return JSON
echo json_encode([
    "all" => $allFines,
    "reported" => $reportedFines
]);
