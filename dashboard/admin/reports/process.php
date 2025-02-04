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

// Query to fetch data based on time period
switch ($period) {
    case 'monthly':
        $query = "SELECT MONTH(issued_date) AS label, COUNT(*) AS value
                  FROM fines
                  WHERE police_id = ? 
                  GROUP BY MONTH(issued_date)
                  ORDER BY MONTH(issued_date)";
        break;

    case 'weekly':
        $query = "SELECT WEEK(issued_date) AS label, COUNT(*) AS value
                  FROM fines
                  WHERE police_id = ? 
                  GROUP BY WEEK(issued_date)
                  ORDER BY WEEK(issued_date)";
        break;

    case 'yearly':
        $query = "SELECT YEAR(issued_date) AS label, COUNT(*) AS value
                  FROM fines
                  WHERE police_id = ? 
                  GROUP BY YEAR(issued_date)
                  ORDER BY YEAR(issued_date)";
        break;

    case 'lifetime':
        $query = "SELECT 'Lifetime' AS label, COUNT(*) AS value
                  FROM fines
                  WHERE police_id = ?";
        break;

    default:
        echo json_encode(["error" => "Invalid time period"]);
        exit;
}

// Prepare and execute the query
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $police_id);
$stmt->execute();
$result = $stmt->get_result();

// Fetch and return the data
$data = $result->fetch_all(MYSQLI_ASSOC);
echo json_encode($data);
