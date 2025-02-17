<?php
require_once "../../../db/connect.php";

// Check if user is authenticated
session_start();
$oic_id = $_SESSION['user']['id'] ?? null;
if (!$oic_id) {
    echo "unauthorized";
    exit;
}

// Check if ID is sent and is numeric
if (!isset($_POST['id']) || !is_numeric($_POST['id'])) {
    echo "invalid_id";
    exit;
}

$fine_id = intval($_POST['id']);

$sql = "DELETE FROM fines WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $fine_id);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo "deleted successfully";
} else {
    echo "couldnt delete";
}

$stmt->close();
$conn->close();
exit;
?>
