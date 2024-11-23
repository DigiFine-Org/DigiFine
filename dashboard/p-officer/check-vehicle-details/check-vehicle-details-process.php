<?php

if($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Get vehicle ID from query parameter
    $license_plate_number = isset($_GET['query']) ? htmlspecialchars($_GET['query']) : '';

    if(!$license_plate_number) {
        die("License Plate Number Required!");
    }

    // fet vehicle details
    $sql = "SELECT * FROM dmt_vehicles WHERE license_plate_number = ?";

    $stmt = $conn->prepare($sql);

    if(!$stmt) {
        die("Database error: " .$conn->error);
    }

    $stmt->bind_param("s", $license_plate_number);

    if(!$stmt->execute()) {
        die("Query execution error: " .$stmt->error);
    }

    $result = $stmt->get_result();
    if($result->num_rows === 0) {
        header("Location: check-vehicle-details.php?query=$license_plate_number&error=Vehicle not found");
    }
    exit();
} else {
    die("Invalid request method!");
}