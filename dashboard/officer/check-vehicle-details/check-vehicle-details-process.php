<?php
session_start();
require_once "../../../db/connect.php";

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $query = isset($_GET['query']) ? $_GET['query'] : '';

    if (!$query) {
        $_SESSION['message'] = "License Plate Number Required!";
        header("Location: /digifine/dashboard/officer/check-vehicle-details/index.php");
        exit();
    }


    $clean_query = preg_replace('/[^a-zA-Z0-9]/', '', $query);


    $sql = "SELECT * FROM dmt_vehicles WHERE license_plate_number = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $query);
    $stmt->execute();
    $result = $stmt->get_result();


    if ($result->num_rows === 0) {
        $sql = "SELECT * FROM dmt_vehicles 
                WHERE REPLACE(REPLACE(license_plate_number, '|', ''), '-', '') = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $clean_query);
        $stmt->execute();
        $result = $stmt->get_result();
    }

    if ($result->num_rows === 0) {
        $_SESSION['message'] = "Vehicle not found!";
        header("Location: /digifine/dashboard/officer/check-vehicle-details/index.php");
        exit();
    }

    $vehicle = $result->fetch_assoc();

    if ($vehicle['is_stolen'] == 1) {
        header("Location: /digifine/dashboard/officer/check-vehicle-details/caught_stolen_vehicle.php?license_plate_number=" . urlencode($vehicle['license_plate_number']));
    } else {
        header("Location: /digifine/dashboard/officer/check-vehicle-details/index.php?query=" . urlencode($vehicle['license_plate_number']));
    }
    exit();
}