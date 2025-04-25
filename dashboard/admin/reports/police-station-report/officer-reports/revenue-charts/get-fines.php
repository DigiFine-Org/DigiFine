<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header("Content-Type: application/json");

require_once "../../../../../db/connect.php";
session_start();

$period = $_GET['time_period'] ?? '';
$police_id = $_GET['police_id'] ?? '';

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

// Function to fetch data by fine status
function fetchFineAmounts($conn, $interval, $group_by, $fine_status)
{
    $query = "SELECT $group_by AS label, SUM(fine_amount) AS total_amount 
              FROM fines 
              WHERE CONCAT(issued_date, ' ', issued_time) >= NOW() - $interval
              AND fine_status = ? 
              GROUP BY label 
              ORDER BY label ASC";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $fine_status);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Fetch datasets for each fine status
$paidFines = fetchFineAmounts($conn, $interval, $group_by, 'paid');
$pendingFines = fetchFineAmounts($conn, $interval, $group_by, 'pending');
$overdueFines = fetchFineAmounts($conn, $interval, $group_by, 'overdue');

// Calculate total amounts
$totalPaidAmount = array_sum(array_column($paidFines, 'total_amount'));
$totalIssuedAmount = array_sum(array_column(array_merge($paidFines, $pendingFines, $overdueFines), 'total_amount'));
$totalPendingAmount = array_sum(array_column($pendingFines, 'total_amount'));
$totalOverdueAmount = array_sum(array_column($overdueFines, 'total_amount'));

// Return JSON
echo json_encode([
    "paid" => $paidFines,
    "pending" => $pendingFines,
    "overdue" => $overdueFines,
    "totals" => [
        "paid" => $totalPaidAmount,
        "issued" => $totalIssuedAmount,
        "pending" => $totalPendingAmount,
        "overdue" => $totalOverdueAmount
    ]
]);
