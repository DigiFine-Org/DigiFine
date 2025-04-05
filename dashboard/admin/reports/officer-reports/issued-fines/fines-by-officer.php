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

// Check if police ID exists in the database
$check_query = "SELECT COUNT(*) AS count FROM fines WHERE police_id = ?";
$check_stmt = $conn->prepare($check_query);
if (!$check_stmt) {
    echo json_encode(["error" => "SQL prepare failed: " . $conn->error]);
    exit;
}
$check_stmt->bind_param("i", $police_id);
$check_stmt->execute();
$check_result = $check_stmt->get_result();
$check_data = $check_result->fetch_assoc();

if ($check_data['count'] == 0) {
    echo json_encode(["error" => "Officer ID Not Found"]);
    exit;
}

// Set interval dynamically based on period
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

// Adjust grouping for hours in 24h and 72h cases
if ($period === "24h" || $period === "72h") {
    $group_by = "DATE_FORMAT(CONCAT(issued_date, ' ', issued_time), '%Y-%m-%d %H:00')"; // Group by hour
} else {
    $group_by = "DATE(issued_date)"; // Group by day
}

// SQL query
$query = "SELECT $group_by AS label, COUNT(*) AS count 
          FROM fines 
          WHERE police_id = ? 
          AND CONCAT(issued_date, ' ', issued_time) >= NOW() - $interval 
          GROUP BY label 
          ORDER BY label ASC";

// Debugging: Check the generated SQL query
$stmt = $conn->prepare($query);
if (!$stmt) {
    echo json_encode(["error" => "SQL prepare failed: " . $conn->error]);
    exit;
}

// Bind parameters and execute
$stmt->bind_param("i", $police_id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_all(MYSQLI_ASSOC);

// Output JSON
echo json_encode($data);
