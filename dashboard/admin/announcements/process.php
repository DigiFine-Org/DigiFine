<?php
session_start();
include '../../../db/connect.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $message = $_POST['message'];
    $target_role = $_POST['target_role'];
    $expires_at = $_POST['expires_at'];

    $stmt = $conn->prepare("INSERT INTO announcements (title, message, target_role, expires_at) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $title, $message, $target_role, $expires_at);

    if ($stmt->execute()) {
        $_SESSION['message'] = "success";
        header("Location: /digifine/dashboard/admin/announcements/index.php");
        exit();
    } else {
        $_SESSION['message'] = "Error" . $stmt->error;
    }
}
