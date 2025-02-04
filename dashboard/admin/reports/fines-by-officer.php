<?php
header('Content-Type: application/json');
require_once "../../../db/connect.php";
session_start();

// Validate officer ID and period selection
if (!isset($_GET['police_id']) || !is_numeric($_GET['police_id'])) {
    echo json_encode(["error" => "Invalid Officer ID"]);
    exit;
}
$police_id = intval($_GET['police_id']);

// Validate the time period
if (!isset($_GET['period'])) {
    echo json_encode(["error" => "No time period selected"]);
    exit;
}
$period = $_GET['period'];

// Get the current date
$currentDate = date('Y-m-d H:i:s');

// Query to fetch data based on time period
switch ($period) {
    case '24h':
        // Fetch data for the last 24 hours, grouped by the exact hour
        $query = "SELECT DATE_FORMAT(issued_date, '%Y-%m-%d %H:00') AS label, COUNT(*) AS value
                  FROM fines
                  WHERE police_id = ? AND issued_date >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
                  GROUP BY DATE_FORMAT(issued_date, '%Y-%m-%d %H')
                  ORDER BY issued_date ASC"; // Ensure chronological order
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $police_id); // Bind only police_id as the first parameter
        break;


    case '72h':
        // Fetch data for the last 72 hours
        $query = "SELECT DATE_FORMAT(issued_date, '%Y-%m-%d %H:00') AS label, COUNT(*) AS value
                  FROM fines
                  WHERE police_id = ? AND issued_date >= DATE_SUB(?, INTERVAL 3 DAY)
                  GROUP BY DATE_FORMAT(issued_date, '%Y-%m-%d %H')
                  ORDER BY issued_date";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("is", $police_id, $currentDate);
        break;

    case '7days':
        // Fetch data for the last 7 days
        $query = "SELECT DATE(issued_date) AS label, COUNT(*) AS value
                  FROM fines
                  WHERE police_id = ? AND issued_date >= DATE_SUB(?, INTERVAL 7 DAY)
                  GROUP BY DATE(issued_date)
                  ORDER BY issued_date";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("is", $police_id, $currentDate);
        break;

    case '14days':
        // Fetch data for the last 14 days
        $query = "SELECT DATE(issued_date) AS label, COUNT(*) AS value
                  FROM fines
                  WHERE police_id = ? AND issued_date >= DATE_SUB(?, INTERVAL 14 DAY)
                  GROUP BY DATE(issued_date)
                  ORDER BY issued_date";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("is", $police_id, $currentDate);
        break;

    case '30days':
        // Fetch data for the last 30 days
        $query = "SELECT DATE(issued_date) AS label, COUNT(*) AS value
                  FROM fines
                  WHERE police_id = ? AND issued_date >= DATE_SUB(?, INTERVAL 30 DAY)
                  GROUP BY DATE(issued_date)
                  ORDER BY issued_date";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("is", $police_id, $currentDate);
        break;

    case '90days':
        // Fetch data for the last 90 days
        $query = "SELECT DATE(issued_date) AS label, COUNT(*) AS value
                  FROM fines
                  WHERE police_id = ? AND issued_date >= DATE_SUB(?, INTERVAL 90 DAY)
                  GROUP BY DATE(issued_date)
                  ORDER BY issued_date";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("is", $police_id, $currentDate);
        break;

    case '365days':
        // Fetch data for the last 365 days
        $query = "SELECT DATE_FORMAT(issued_date, '%Y-%m') AS label, COUNT(*) AS value
                  FROM fines
                  WHERE police_id = ? AND issued_date >= DATE_SUB(?, INTERVAL 365 DAY)
                  GROUP BY DATE_FORMAT(issued_date, '%Y-%m')
                  ORDER BY issued_date";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("is", $police_id, $currentDate);
        break;

    case 'lifetime':
        // Fetch lifetime data
        $query = "SELECT 'Lifetime' AS label, COUNT(*) AS value
                  FROM fines
                  WHERE police_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $police_id);
        break;

    default:
        echo json_encode(["error" => "Invalid time period"]);
        exit;
}

// Execute the query
$stmt->execute();
$result = $stmt->get_result();

// Fetch and return the data
$data = $result->fetch_all(MYSQLI_ASSOC);
echo json_encode($data);
