<?php
// Database connection
require_once "../../../db/connect.php";

// Set default filter to "lifetime"
$filter = $_GET['filter'] ?? 'lifetime';

// Prepare SQL queries based on the selected filter
$query = "";
switch ($filter) {
    case 'year':
        $query = "SELECT YEAR(issued_date) AS period, COUNT(*) AS count FROM fines GROUP BY YEAR(issued_date)";
        break;
    case 'month':
        $query = "SELECT DATE_FORMAT(issued_date, '%Y-%m') AS period, COUNT(*) AS count FROM fines GROUP BY DATE_FORMAT(issued_date, '%Y-%m')";
        break;
    case 'week':
        $query = "SELECT CONCAT(YEAR(issued_date), '-W', WEEK(issued_date)) AS period, COUNT(*) AS count FROM fines GROUP BY YEAR(issued_date), WEEK(issued_date)";
        break;
    case 'day':
        $query = "SELECT DATE(issued_date) AS period, COUNT(*) AS count FROM fines GROUP BY DATE(issued_date)";
        break;
    case 'lifetime':
    default:
        $query = "SELECT 'Lifetime' AS period, COUNT(*) AS count FROM fines";
        break;
}

// Check if the query is valid
if (!$query) {
    die("No query generated for filter: $filter");
}

// Execute the query
$result = $conn->query($query);
if (!$result) {
    die("Error executing query: " . $conn->error); // Debug SQL errors
}

// Prepare data for JSON response
$data = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
} else {
    $data = ["message" => "No data found for filter: $filter"];
}

// Return data as JSON
header('Content-Type: application/json');
echo json_encode($data);
