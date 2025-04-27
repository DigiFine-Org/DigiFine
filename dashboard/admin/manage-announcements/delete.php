<?php
session_start();
require_once "../../../db/connect.php";

// Ensure the user is logged in and has the role of admin
if ($_SESSION['user']['role'] !== 'admin') {
    die("Unauthorized access!");
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("DELETE FROM announcements WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        $_SESSION['message'] = 'deleted';
    } else {
        $_SESSION['message'] = 'Failed to delete announcement.';
    }
    header("Location: index.php");
    exit;
} else {
    die("Invalid request.");
}
