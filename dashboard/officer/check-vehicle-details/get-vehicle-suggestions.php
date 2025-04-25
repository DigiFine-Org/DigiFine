<?php
session_start();
require_once "../../../db/connect.php";

// Check user authentication and role
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'officer') {
    die(json_encode(['error' => 'Unauthorized']));
}

// Get the search term
$search = isset($_GET['term']) ? $_GET['term'] : '';

if (empty($search)) {
    echo json_encode([]);
    exit;
}

// Sanitize and prepare search term for fuzzy matching
$search = preg_replace('/[^a-zA-Z0-9]/', '', $search); // Remove special chars
$search = '%' . $search . '%'; // Add wildcards for LIKE query

// Query database for matching license plates
$sql = "SELECT license_plate_number, vehicle_type, vehicle_owner_fname, vehicle_owner_lname 
        FROM dmt_vehicles 
        WHERE REPLACE(REPLACE(license_plate_number, '|', ''), '-', '') LIKE ?
        LIMIT 10";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $search);
$stmt->execute();
$result = $stmt->get_result();

$suggestions = [];
while ($row = $result->fetch_assoc()) {
    $suggestion = [
        'value' => $row['license_plate_number'],
        'label' => $row['license_plate_number'] . ' - ' . 
                  $row['vehicle_type'] . ' (' . 
                  $row['vehicle_owner_fname'] . ' ' . 
                  $row['vehicle_owner_lname'] . ')'
    ];
    $suggestions[] = $suggestion;
}

echo json_encode($suggestions);