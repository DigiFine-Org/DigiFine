<?php
session_start();
require_once "../../../../db/connect.php";

if ($_SESSION['user']['role'] !== 'admin') {
    die(json_encode(['error' => 'Unauthorized user!']));
}

// Get officer ID from request
$officerId = isset($_GET['officer_id']) ? $_GET['officer_id'] : null;

if (!$officerId) {
    die(json_encode(['error' => 'Officer ID is required']));
}

try {
    // Get officer information
    $officerQuery = "SELECT id, fname, lname, is_oic FROM officers WHERE id = ?";
    $stmt = $conn->prepare($officerQuery);
    $stmt->bind_param("s", $officerId);
    $stmt->execute();

    // Get the result
    $result = $stmt->get_result();
    $officerInfo = $result->fetch_assoc();

    if (!$officerInfo) {
        echo json_encode(['error' => 'Officer not found']);
    } else {
        echo json_encode(['success' => true, 'data' => $officerInfo]);
    }

    $stmt->close();
} catch (Exception $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
