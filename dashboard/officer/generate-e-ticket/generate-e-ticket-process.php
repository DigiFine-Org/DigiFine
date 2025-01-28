<?php
session_start();

include '../../../db/connect.php';

// Check if user is logged in as police officer
$policeId = $_SESSION['user']['id'] ?? null;

if (!$policeId) {
    die("Unauthorized access. Police ID not found.");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $issued_date = htmlspecialchars($_POST['issued_date']);
    $issued_time = htmlspecialchars($_POST['issued_time']);
    $expire_date = date('Y-m-d', strtotime($issued_date . ' + 14 days'));
    $driver_id = htmlspecialchars($_POST['driver_id']);
    $license_plate_number = htmlspecialchars($_POST['license_plate_number']);
    $offence_type = htmlspecialchars($_POST['offence_type']);
    $nature_of_offence = htmlspecialchars($_POST['nature_of_offence']);
    $offence_number = htmlspecialchars($_POST['offence'] ?? null); // Offence number from dropdown
    $fine_amount = htmlspecialchars($_POST['fine_amount'] ?? 0);

    // Validate if the driver exists in the system
    $driverCheckSql = "SELECT * FROM drivers WHERE id = ?";
    $driverCheckStmt = $conn->prepare($driverCheckSql);
    if (!$driverCheckStmt) {
        die("Error preparing driver check stmt: " . $conn->error);
    }

    $driverCheckStmt->bind_param("s", $driver_id);
    $driverCheckStmt->execute();
    $driverResult = $driverCheckStmt->get_result();

    if ($driverResult->num_rows === 0) {
        // Set the session message and redirect
        $_SESSION["message"] = "Driver not found.";
        header("Location: /digifine/dashboard/officer/generate-e-ticket/index.php");
        exit();
    }

    $offence = null;
    if ($offence_type !== 'court') {
        // Fetch the English description for the offence
        $offenceSql = "SELECT description_english FROM offences WHERE offence_number = ?";
        $offenceStmt = $conn->prepare($offenceSql);
        if (!$offenceStmt) {
            die("Error preparing offence stmt: " . $conn->error);
        }

        $offenceStmt->bind_param("s", $offence_number);
        $offenceStmt->execute();
        $offenceResult = $offenceStmt->get_result();

        if ($offenceResult->num_rows === 0) {
            // Set the session message and redirect
            $_SESSION["message"] = "Invalid offence selected.";
            header("Location: /digifine/dashboard/officer/generate-e-ticket/index.php");
            exit();
        }

        $offence = $offenceResult->fetch_assoc()['description_english']; // Get English description
    }

    // Handle offence for court
    $offence_number = $offence_type === "court" ? null : $offence_number;

    // Insert the fine into the database
    $sql = "INSERT INTO fines (police_id, driver_id, license_plate_number, issued_date, issued_time, expire_date, offence_type, nature_of_offence, offence, fine_amount) VALUES (?, ?, ?, CURRENT_DATE, CURRENT_TIME, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("Error preparing statement: " . $conn->error);
    }

    $stmt->bind_param("issssss", $policeId, $driver_id, $license_plate_number, $offence_type, $nature_of_offence, $offence, $fine_amount);

    if ($stmt->execute()) {
        // Set the success message in the session
        $_SESSION["message"] = "success";
        header("Location: /digifine/dashboard/officer/generate-e-ticket/index.php");
        exit();
    } else {
        die("Error inserting fine: " . $stmt->error);
    }

    $stmt->close();
    $offenceStmt->close();
    $driverCheckStmt->close();
    $conn->close();
} else {
    die("Invalid request method.");
}
