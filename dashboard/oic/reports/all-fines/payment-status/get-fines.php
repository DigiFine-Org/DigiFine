<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header("Content-Type: application/json");

require_once "../../../../../db/connect.php";
session_start();

// $policeStationId = $_SESSION['police_station_id'] ?? null;
// if (!$policeStationId) {
//     die(json_encode(["error" => "Police station ID not found in session."]));
// }

$period = $_GET['time_period'] ?? '';
$policeStationId = $_GET['police_station'] ?? null;
// echo "Time Period: " . htmlspecialchars($period) . "<br>";
// echo "Police Station ID: " . htmlspecialchars($policeStationId);


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

// Function to fetch data by fine status AND police station
function fetchFinesByStatus($conn, $interval, $group_by, $fine_status, $policeStationId)
{
    $query = "SELECT $group_by AS label, COUNT(*) AS count 
              FROM fines 
              WHERE CONCAT(issued_date, ' ', issued_time) >= NOW() - $interval
              AND fine_status = ?
              AND police_station = ?
              GROUP BY label 
              ORDER BY label ASC";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("si", $fine_status, $policeStationId); // s = string (status), i = integer (police station)
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Fetch datasets for each fine status
$paidFines = fetchFinesByStatus($conn, $interval, $group_by, 'paid', $policeStationId);
$pendingFines = fetchFinesByStatus($conn, $interval, $group_by, 'pending', $policeStationId);
$overdueFines = fetchFinesByStatus($conn, $interval, $group_by, 'overdue', $policeStationId);

// Return JSON
echo json_encode([
    "paid" => $paidFines,
    "pending" => $pendingFines,
    "overdue" => $overdueFines
]);
