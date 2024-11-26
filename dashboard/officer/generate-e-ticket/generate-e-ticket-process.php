<?php
session_start();

include '../../../db/connect.php';

// Check if user is logged in as police officer
$policeId = $_SESSION['user']['id'] ?? null;

if (!$policeId) {
    echo "<script>alert('Unauthorized access. Police ID not found.'); window.location.href = '/digifine/dashboard/officer/login.php';</script>";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Save form data to session
    $_SESSION['form_data'] = $_POST;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    var_dump($_POST);

    $issued_date = htmlspecialchars($_POST['issued_date']);
    $issued_time = htmlspecialchars($_POST['issued_time']);
    $driver_id = htmlspecialchars($_POST['driver_id']);
    $license_plate_number = htmlspecialchars($_POST['license_plate_number']);
    $offence_type = htmlspecialchars($_POST['offence_type']);
    $nature_of_offence = htmlspecialchars($_POST['nature_of_offence']);
    $offence_number = htmlspecialchars($_POST['offence'] ?? null); // Offence number from dropdown

    // Validate if the driver exists in the system
    $driverCheckSql = "SELECT * FROM drivers WHERE id = ?";
    $driverCheckStmt = $conn->prepare($driverCheckSql);
    if (!$driverCheckStmt) {
        echo "<script>alert('Error preparing driver check statement: " . $conn->error . "'); window.location.href = '/digifine/dashboard/officer/generate-e-ticket/index.php';</script>";
        exit();
    }

    $driverCheckStmt->bind_param("s", $driver_id);
    $driverCheckStmt->execute();
    $driverResult = $driverCheckStmt->get_result();

    if ($driverResult->num_rows === 0) {
        echo "<script>alert('Driver not found'); window.location.href = '/digifine/dashboard/officer/generate-e-ticket/index.php';</script>";
        exit();
    }

    $offence = null;
    if ($offence_type !== 'court') {
        // Fetch the English description for the offence
        $offenceSql = "SELECT description_english FROM offences WHERE offence_number = ?";
        $offenceStmt = $conn->prepare($offenceSql);
        if (!$offenceStmt) {
            echo "<script>alert('Error preparing offence statement: " . $conn->error . "'); window.location.href = '/digifine/dashboard/officer/generate-e-ticket/index.php';</script>";
            exit();
        }

        $offenceStmt->bind_param("s", $offence_number);
        $offenceStmt->execute();
        $offenceResult = $offenceStmt->get_result();

        if ($offenceResult->num_rows === 0) {
            echo "<script>alert('Invalid offence selected.'); window.location.href = '/digifine/dashboard/officer/generate-e-ticket/index.php';</script>";
            exit();
        }

        $offence = $offenceResult->fetch_assoc()['description_english']; // Get English description
    }

    // Handle offence for court
    $offence_number = $offence_type === "court" ? null : $offence_number;

    // Insert the fine into the database
    $sql = "INSERT INTO fines (police_id, driver_id, license_plate_number, issued_date, issued_time, offence_type, nature_of_offence, offence) VALUES (?, ?, ?, CURRENT_DATE, CURRENT_TIME, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        echo "<script>alert('Error preparing statement: " . $conn->error . "'); window.location.href = '/digifine/dashboard/officer/generate-e-ticket/index.php';</script>";
        exit();
    }

    $stmt->bind_param("isssss", $policeId, $driver_id, $license_plate_number, $offence_type, $nature_of_offence, $offence);

    if ($stmt->execute()) {
        echo "<script>alert('Fine issued successfully!'); window.location.href = '/digifine/dashboard/officer/generate-e-ticket/index.php?message=Fine issued successfully';</script>";
        exit();
    } else {
        echo "<script>alert('Error inserting fine: " . $stmt->error . "'); window.location.href = '/digifine/dashboard/officer/generate-e-ticket/index.php';</script>";
        exit();
    }

    $stmt->close();
    $offenceStmt->close();
    $driverCheckStmt->close();
    $conn->close();
} else {
    echo "<script>alert('Invalid request method.'); window.location.href = '/digifine/dashboard/officer/generate-e-ticket/index.php';</script>";
    exit();
}
