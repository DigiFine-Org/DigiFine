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

    // Target role is automatically "officers in the same station"
    $targetRole = "officer";

    // Insert announcement into the database
    $insertQuery = "INSERT INTO announcements (title, message, target_role, expires_at, police_station) 
                    VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($insertQuery);  // Use $conn for mysqli
    $stmt->bind_param("sssss", $title, $message, $targetRole, $expiresAt, $oicStationNumber);  // Bind parameters
    $stmt->execute();

    $_SESSION['message'] = 'Announcement published successfully.';
    header("Location: ");
    exit();
} else {
    die("Invalid request method!");
}
