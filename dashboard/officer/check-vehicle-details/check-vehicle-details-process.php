<?php
session_start(); // Ensure session is initialized
require_once "../../../db/connect.php"; // Include DB connection

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $license_plate_number = isset($_GET['query']) ? htmlspecialchars($_GET['query']) : '';

    if (!$license_plate_number) {
        $_SESSION['message'] = "License Plate Number Required!"; // Use session for error message
        header("Location: /digifine/dashboard/officer/check-vehicle-details/index.php");
        exit();
    }

    $sql = "SELECT * FROM dmt_vehicles WHERE license_plate_number = ?";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("Database error: " . $conn->error);
    }

    $stmt->bind_param("s", $license_plate_number);

    if (!$stmt->execute()) {
        die("Query execution error: " . $stmt->error);
    }

    $result = $stmt->get_result();
    if ($result->num_rows === 0) {
        $_SESSION['message'] = "Vehicle not found!"; // Use session for consistent error messaging
        header("Location: /digifine/dashboard/officer/check-vehicle-details/index.php");
        exit();
    }
    header("Location: /digifine/dashboard/officer/check-vehicle-details/index.php?query=$license_plate_number");
    exit();
} else {
    die("Invalid request method!");
}
