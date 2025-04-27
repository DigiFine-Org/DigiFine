<?php
require_once "../../../db/connect.php";
session_start();
header('Content-Type: application/json');


if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'oic') {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $case_id = isset($_POST['case_id']) ? intval($_POST['case_id']) : null;
    $reference_number = $_POST['reference_number'] ?? null;
    $license_action = isset($_POST['license_action']) ? intval($_POST['license_action']) : 0;

    if (!$case_id || !$reference_number) {
        echo json_encode(['success' => false, 'message' => 'Required fields are missing']);
        exit;
    }

    $sql="UPDATE fines SET is_solved = 1, reference_number = ?, license_status = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    
    $stmt->bind_param("sii", $reference_number, $license_action, $case_id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Database error']);
    }

    $stmt->close();
    $conn->close();
}
?>
