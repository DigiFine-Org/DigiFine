<?php
require_once "../../../db/connect.php";
session_start();

if ($_SESSION['user']['role'] !== 'oic') {
    die("Unauthorized user!");
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("DELETE FROM announcements WHERE id = ? AND published_id = ?");
    $stmt->bind_param("ii", $id, $_SESSION['user']['id']);
    if ($stmt->execute()) {
        $_SESSION['message'] = 'success';
        header("Location: index.php");
        exit;
    } else {
        $_SESSION['message'] = 'Failed to delete announcement.';
        header("Location: index.php");
        exit;
    }
} else {
    die("Invalid request.");
}
