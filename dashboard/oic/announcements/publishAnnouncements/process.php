<?php
session_start();
include '../../../../db/connect.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and sanitize form input
    $title = htmlspecialchars($_POST['title']);
    $message = htmlspecialchars($_POST['message']);
    $role = $_SESSION['user']['role'] ?? '';
    $id = $_SESSION['user']['id'] ?? '';
    $policeStation = $_SESSION['user']['police_station'] ?? '';
    $expiresAt = $_POST['expires_at'] ?? null;

    if (empty($expiresAt)) {
        $expiresAt = null;
    }

    // Target role is automatically "officers in the same station"
    $targetRole = "officer";

    // Insert announcement into the database
    $insertQuery = "INSERT INTO announcements (title, message, published_by, published_id, target_role, expires_at, police_station) 
                    VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($insertQuery);  // Use $conn for mysqli
    $stmt->bind_param("ssssssi", $title, $message, $role, $id, $targetRole, $expiresAt, $policeStation);  // Bind parameters
    $stmt->execute();

    $_SESSION['message'] = 'success';
    header("Location: /digifine/dashboard/oic/announcements/publishAnnouncements/index.php");
    exit();
} else {
    die("Invalid request method!");
}
