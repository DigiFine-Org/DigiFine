<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header("Content-Type: application/json");

require_once "../../../../../db/connect.php";
session_start();

// Get the time period and police station parameters
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

// Fetch total fines grouped by location, filtered by police station
$query = "
        SELECT 
        CASE 
            WHEN f.location = '' OR f.location IS NULL THEN 'Unknown'
            WHEN dl.location_name IS NOT NULL THEN dl.location_name
            ELSE CONCAT( '*', COALESCE(f.location, 'N/A'))
        END AS label,
        SUM(f.fine_amount) AS count
        FROM fines f
        LEFT JOIN duty_locations dl ON f.location = dl.id
        WHERE CONCAT(f.issued_date, ' ', f.issued_time) >= NOW() - $interval
        AND (f.police_station = ? OR dl.police_station_id = ?)
        GROUP BY label
        ORDER BY count DESC;
";

// Use prepared statement to avoid SQL injection
$stmt = $conn->prepare($query);
$stmt->bind_param("ss", $policeStationId, $policeStationId);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_all(MYSQLI_ASSOC);

// Return JSON
echo json_encode($data);
