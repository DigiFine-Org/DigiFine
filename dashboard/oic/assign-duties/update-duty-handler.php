<?php
include_once "../../../db/connect.php";
session_start();

if ($_SESSION['user']['role'] !== 'oic') {
    die("Unauthorized user!");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dutyId = $_POST['dutyId'];
    $duty = $_POST['duty'];
    $dutyDate = $_POST['dutyDate'];
    $dutyTimeStart = $_POST['dutyTimeStart'];
    $dutyTimeEnd = $_POST['dutyTimeEnd'];
    $notes = $_POST['notes'];

    $updateQuery = "UPDATE assigned_duties SET duty = ?, duty_date = ?, duty_time_start = ?, duty_time_end = ?, notes = ? WHERE id = ?";
    $updateStmt = $conn->prepare($updateQuery);
    $updateStmt->bind_param("sssssi", $duty, $dutyDate, $dutyTimeStart, $dutyTimeEnd, $notes, $dutyId);

    if ($updateStmt->execute()) {
        $_SESSION["message"] = "edited";
        header("Location: /digifine/dashboard/oic/assign-duties/edit-duties.php");
        exit();
    } else {
        $_SESSION["message"] = "Failed to Delete Duty.";
        header("Location: /digifine/dashboard/oic/assign-duties/edit-duties.php");
        exit();
    }

    $updateStmt->close();
    header("Location: edit-duties.php");
    exit();
}
