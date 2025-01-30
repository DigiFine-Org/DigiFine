<?php
session_start();
include '../../../db/connect.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $message = $_POST['message'];
    $role = $_SESSION['user']['role'] ?? '';
    $id = $_SESSION['user']['id'] ?? '';
    $target_role = $_POST['target_role'];
    $expires_at = empty($_POST['expires_at']) ? null : $_POST['expires_at'];
    $stmt = $conn->prepare("INSERT INTO announcements (title, message, published_by, published_id, target_role, expires_at) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $title, $message, $role, $id, $target_role, $expires_at);

    if ($stmt->execute()) {
        $_SESSION['message'] = "success";
        header("Location: /digifine/dashboard/admin/announcements/index.php");
        exit();
    } else {
        $_SESSION['message'] = "Error" . $stmt->error;
    }
}
