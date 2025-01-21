<?php
// Database connection
require_once "../../../db/connect.php";

// Set default filter to "lifetime"
$filter = $_GET['filter'] ?? 'lifetime';

// Prepare SQL queries based on the selected filter
switch ($filter) {
    case 'year':
        $query = "SELECT YEAR(issued_date) AS period, COUNT(*) AS count FROM fines GROUP BY YEAR(issued_date)";
        break;
    case 'month':
        $query = "SELECT DATE_FORMAT(issued_date, '%Y-%m') AS period, COUNT(*) AS count FROM fines GROUP BY DATE_FORMAT(issued_date, '%Y-%m')";
        break;
    case 'week':
        $query = "SELECT YEARWEEK(issued_date, 1) AS period, COUNT(*) AS count FROM fines GROUP BY YEARWEEK(issued_date, 1)";
        break;
    case 'day':
        $query = "SELECT DATE(issued_date) AS period, COUNT(*) AS count FROM fines GROUP BY DATE(issued_date)";
        break;
    case 'lifetime':
    default:
        $query = "SELECT 'Lifetime' AS period, COUNT(*) AS count FROM fines";
        break;
}

// Fetch data from the database
$result = $conn->query($query);
$data = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

// Return data as JSON
header('Content-Type: application/json');
echo json_encode($data);
