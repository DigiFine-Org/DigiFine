<?php
require_once "../../../db/connect.php";
session_start();

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'oic') {
    http_response_code(403);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $caseId = intval($_POST['case_id']);

    $stmt = $conn->prepare("UPDATE fines SET is_solved = 1 WHERE id = ?");
    $stmt->bind_param("i", $caseId);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['error' => 'Database error']);
    }

    $stmt->close();
    $conn->close();
}
