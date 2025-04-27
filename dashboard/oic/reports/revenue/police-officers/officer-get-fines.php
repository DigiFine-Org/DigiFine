<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header("Content-Type: application/json");

require_once "../../../../../db/connect.php";
session_start();

$period = $_GET['time_period'] ?? '';
$policeStationId = $_GET['police_station'] ?? null;

// Validate police station ID
if (!$policeStationId) {
    die(json_encode(['error' => 'Police station ID is required']));
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

// Fetch total fines grouped by officers in the specified police station
$query = "
    SELECT po.id AS label,
    SUM(f.fine_amount) AS count
    FROM fines f
    INNER JOIN officers po ON f.police_id = po.id
    WHERE CONCAT(f.issued_date, ' ', f.issued_time) >= NOW() - $interval
    AND po.police_station = ?
    GROUP BY po.id, po.fname, po.lname
    ORDER BY count DESC;
";

// Use prepared statement for security
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $policeStationId);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_all(MYSQLI_ASSOC);

// Return JSON
echo json_encode($data);
