<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header("Content-Type: application/json");

require_once "../../../../../db/connect.php";
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

// Grouping format
$group_by = ($period === "24h" || $period === "72h")
    ? "DATE_FORMAT(CONCAT(issued_date, ' ', issued_time), '%Y-%m-%d %H:00')"
    : "DATE(issued_date)";

// Function to fetch data
function fetchFines($conn, $interval, $group_by, $policeStationId, $is_reported = null)
{
    $query = "SELECT $group_by AS label, COUNT(*) AS count 
              FROM fines 
              WHERE CONCAT(issued_date, ' ', issued_time) >= NOW() - $interval
              AND police_station = ?";

    if (!is_null($is_reported)) {
        $query .= " AND is_reported = ?";
    }

    $query .= " GROUP BY label ORDER BY label ASC";

    $stmt = $conn->prepare($query);
    if (!$stmt) {
        error_log("Prepare error: " . $conn->error);
        return [];
    }

    if (!is_null($is_reported)) {
        $stmt->bind_param("ii", $policeStationId, $is_reported);
    } else {
        $stmt->bind_param("i", $policeStationId);
    }

    $stmt->execute();
    $result = $stmt->get_result();
    if (!$result) {
        error_log("Result error: " . $stmt->error);
        return [];
    }

    return $result->fetch_all(MYSQLI_ASSOC);
}

// Fetch both datasets for the specific police station
$allFines = fetchFines($conn, $interval, $group_by, $policeStationId); // all fines for this police station
$reportedFines = fetchFines($conn, $interval, $group_by, $policeStationId, 1); // reported fines for this police station

// Return JSON
echo json_encode([
    "all" => $allFines,
    "reported" => $reportedFines
]);
