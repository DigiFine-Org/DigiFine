<?php
include_once "../../../db/connect.php";
session_start();

if ($_SESSION['user']['role'] !== 'oic') {
    die("Unauthorized user!");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dutyId = $_POST['dutyId'];

    $deleteQuery = "DELETE FROM assigned_duties WHERE id = ?";
    $deleteStmt = $conn->prepare($deleteQuery);
    $deleteStmt->bind_param("i", $dutyId);

    if ($deleteStmt->execute()) {
        $_SESSION["message"] = "deleted";
        header("Location: /digifine/dashboard/oic/assign-duties/edit-duties.php");
        exit();
    } else {
        $_SESSION["message"] = "Failed to Delete Duty.";
        header("Location: /digifine/dashboard/oic/assign-duties/edit-duties.php");
        exit();
    }

    $deleteStmt->close();
    header("Location: edit-duties.php");
    exit();
}
