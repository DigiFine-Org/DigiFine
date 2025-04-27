<?php
session_start();
require_once "../../../../db/connect.php";

// Get officer ID from request
$officerId = $_GET['officerId'] ?? null;
if (empty($officerId)) {
    echo json_encode(['error' => 'Officer ID is required']);
    exit;
}
if (!ctype_digit($officerId)) {
    echo json_encode(['error' => 'Invalid Officer ID Format']);
    exit;
}

$officerQuery = "SELECT id, fname, lname, is_oic FROM officers WHERE id = ?";
$stmt = $conn->prepare($officerQuery);
$stmt->bind_param("i", $officerId);
$stmt->execute();

// Get the result
$result = $stmt->get_result();
$officerInfo = $result->fetch_assoc();

if (!$officerInfo) {
    echo json_encode(['error' => 'Officer not found']);
    exit;
}

echo json_encode(['success' => true, 'data' => $officerInfo]);


$stmt->close();
