<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header("Content-Type: application/json");

require_once "../../../../../db/connect.php";
session_start();

$police_id = intval($_GET['police_id'] ?? 0);
$period = $_GET['period'] ?? '';

if ($police_id == 0) {
    echo json_encode(["error" => "Invalid police ID"]);
    exit;
}

// Set interval based on period
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

// Determine grouping format
if ($period === "24h" || $period === "72h") {
    $group_by = "DATE_FORMAT(CONCAT(issued_date, ' ', issued_time), '%Y-%m-%d %H:00')";
} else {
    $group_by = "DATE(issued_date)";
}

// Function to fetch data based on is_reported flag
function fetchData($conn, $police_id, $interval, $group_by, $is_reported = null)
{
    $query = "SELECT $group_by AS label, COUNT(*) AS count 
              FROM fines 
              WHERE police_id = ? 
              AND CONCAT(issued_date, ' ', issued_time) >= NOW() - $interval";

    if (!is_null($is_reported)) {
        $query .= " AND is_reported = ?";
    }

    $query .= " GROUP BY label ORDER BY label ASC";

    $stmt = $conn->prepare($query);
    if (!is_null($is_reported)) {
        $stmt->bind_param("ii", $police_id, $is_reported);
    } else {
        $stmt->bind_param("i", $police_id);
    }

    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Get both datasets
$allFines = fetchData($conn, $police_id, $interval, $group_by);
$reportedFines = fetchData($conn, $police_id, $interval, $group_by, 1);

// Return both datasets
echo json_encode([
    "all" => $allFines,
    "reported" => $reportedFines
]);
