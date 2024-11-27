<?php
session_start();

include '../../../db/connect.php';

// Check if user is logged in as police officer
$policeId = $_SESSION['user']['id'] ?? null;

if (!$policeId) {
    $_SESSION["message"] = "Unauthorized access. Police ID not found.";
    // $message = "Unauthorized access. Police ID not found.";
    // include 'alert.php';
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Save form data to session
    $_SESSION['form_data'] = $_POST;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $issued_date = htmlspecialchars($_POST['issued_date']);
    $issued_time = htmlspecialchars($_POST['issued_time']);
    $driver_id = htmlspecialchars($_POST['driver_id']);
    $license_plate_number = htmlspecialchars($_POST['license_plate_number']);
    $offence_type = htmlspecialchars($_POST['offence_type']);
    $nature_of_offence = htmlspecialchars($_POST['nature_of_offence']);
    $offence_number = htmlspecialchars($_POST['offence'] ?? null);

    // Validate if the driver exists in the system
    $driverCheckSql = "SELECT * FROM drivers WHERE id = ?";
    $driverCheckStmt = $conn->prepare($driverCheckSql);
    if (!$driverCheckStmt) {
        $message = "Error preparing driver check statement: " . $conn->error;
        include '../../../includes/alerts/failed.php';
        exit();
    }

    $driverCheckStmt->bind_param("s", $driver_id);
    $driverCheckStmt->execute();
    $driverResult = $driverCheckStmt->get_result();

    if ($driverResult->num_rows === 0) {
        $_SESSION["message"] = "Driver not found.";
        header("Location: ./index.php");
        // $message = "Driver not found.";
        // include '../../../includes/alerts/failed.php';
        exit();
    }

    $offence = null;
    if ($offence_type !== 'court') {
        // Fetch the English description for the offence
        $offenceSql = "SELECT description_english FROM offences WHERE offence_number = ?";
        $offenceStmt = $conn->prepare($offenceSql);
        if (!$offenceStmt) {
            $message = "Error preparing offence statement: " . $conn->error;
            // include '../../../includes/alerts/failed.php';
            exit();
        }

        $offenceStmt->bind_param("s", $offence_number);
        $offenceStmt->execute();
        $offenceResult = $offenceStmt->get_result();

        if ($offenceResult->num_rows === 0) {
            $message = "Invalid offence selected.";
            include '../../../includes/alerts/failed.php';
            exit();
        }

        $offence = $offenceResult->fetch_assoc()['description_english']; // Get English description
    }

    // Handle offence for court
    $offence_number = $offence_type === "court" ? null : $offence_number;

    // Insert the fine into the database
    $sql = "INSERT INTO fines (police_id, driver_id, license_plate_number, issued_date, issued_time, offence_type, nature_of_offence, offence) 
            VALUES (?, ?, ?, CURRENT_DATE, CURRENT_TIME, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        $message = "Error preparing statement: " . $conn->error;
        include '../../../includes/alerts/failed.php';
        exit();
    }

    $stmt->bind_param("isssss", $policeId, $driver_id, $license_plate_number, $offence_type, $nature_of_offence, $offence);

    if ($stmt->execute()) {
        $message = "Fine issued successfully!";
        include '../../../includes/alerts/success.php';
        exit();
    } else {
        $message = "Error inserting fine: " . $stmt->error;
        include '../../../includes/alerts/failed.php';
        exit();
    }

    // Clean up
    $stmt->close();
    $offenceStmt->close();
    $driverCheckStmt->close();
    $conn->close();
} else {
    $message = "Invalid request method.";
    include 'alert.php';
    exit();
}
