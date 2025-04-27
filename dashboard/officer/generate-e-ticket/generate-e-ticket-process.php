<?php
session_start();

include '../../../db/connect.php';
require_once "send-fine-mail.php";
require_once "../../../notifications/functions.php";


$policeId = $_SESSION['user']['id'] ?? null;

if (!$policeId) {
    die("Unauthorized access. Police ID not found.");
}



if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $policeStation = $_SESSION['police_station_id'];
    $issued_date = htmlspecialchars($_POST['issued_date']);
    $issued_time = htmlspecialchars($_POST['issued_time']);
    $expire_date = date('Y-m-d', strtotime($issued_date . ' + 28 days'));
    $driver_id = strtoupper(($_POST['driver_id']));
    $license_plate_number = strtoupper(($_POST['license_plate_number']));
    $offence_type = htmlspecialchars($_POST['offence_type']);
    $nature_of_offence = htmlspecialchars($_POST['nature_of_offence']);
    $location = htmlspecialchars($_POST['location']);
    if ($location === 'other') {
        $location = htmlspecialchars(trim($_POST['other_location'] ?? ''));
    }
    $offence_number = htmlspecialchars($_POST['offence'] ?? null);
    $fine_amount = htmlspecialchars($_POST['fine_amount'] ?? 0);


    $driverCheckSql = "SELECT points FROM drivers WHERE id = ?";
    $driverCheckStmt = $conn->prepare($driverCheckSql);
    if (!$driverCheckStmt) {
        die("Error preparing driver check stmt: " . $conn->error);
    }

    $driverCheckStmt->bind_param("s", $driver_id);
    $driverCheckStmt->execute();
    $driverResult = $driverCheckStmt->get_result();

    if ($driverResult->num_rows === 0) {
        $_SESSION["message"] = "Driver not found.";
        header("Location: /digifine/dashboard/officer/generate-e-ticket/index.php");
        exit();
    }

    $driverData = $driverResult->fetch_assoc();
    $current_points = $driverData['points'];

    $points_deducted = 0;
    if ($offence_type !== 'court') {

        $offenceSql = "SELECT description_english, points_deducted FROM offences WHERE offence_number = ?";
        $offenceStmt = $conn->prepare($offenceSql);
        if (!$offenceStmt) {
            die("Error preparing offence stmt: " . $conn->error);
        }

        $offenceStmt->bind_param("s", $offence_number);
        $offenceStmt->execute();
        $offenceResult = $offenceStmt->get_result();

        if ($offenceResult->num_rows === 0) {
            $_SESSION["message"] = "Invalid offence selected.";
            header("Location: /digifine/dashboard/officer/generate-e-ticket/index.php");
            exit();
        }

        $offenceData = $offenceResult->fetch_assoc();
        $offence = $offenceData['description_english'];
        $points_deducted = $offenceData['points_deducted'];
    }

    $offence_number = $offence_type === "court" ? null : $offence_number;


    $sql = "INSERT INTO fines (
        police_id, driver_id, police_station, license_plate_number, issued_date, issued_time, expire_date, offence_type, nature_of_offence, location, offence, fine_amount
    ) VALUES (
        ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
    )";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("Error preparing statement: " . $conn->error);
    }

    $stmt->bind_param("isissssssssd", $policeId, $driver_id, $policeStation, $license_plate_number, $issued_date, $issued_time, $expire_date, $offence_type, $nature_of_offence, $location, $offence_number, $fine_amount);
    if ($stmt->execute()) {


        $fine_id = $conn->insert_id;


        if ($points_deducted > 0) {
            $new_points = max(0, $current_points - $points_deducted); 

            $updatePointsSql = "UPDATE drivers SET points = ? WHERE id = ?";
            $updatePointsStmt = $conn->prepare($updatePointsSql);
            if ($updatePointsStmt) {
                $updatePointsStmt->bind_param("is", $new_points, $driver_id);
                $updatePointsStmt->execute();
                $updatePointsStmt->close();
            } else {
                die("Error preparing update points stmt: " . $conn->error);
            }
        }


        $fineDetails = [
            'police_id' => $policeId,
            'issued_date' => $issued_date,
            'issued_time' => $issued_time,
            'offence_type' => $offence_type,
            'location' => $location,
            'fine_amount' => $fine_amount,
            'nature_of_offence' => $nature_of_offence,
        ];

 
        $driverEmailSql = "SELECT email FROM drivers WHERE id = ?";
        $driverEmailStmt = $conn->prepare($driverEmailSql);
        $driverEmailStmt->bind_param("s", $driver_id);
        $driverEmailStmt->execute();
        $driverEmailResult = $driverEmailStmt->get_result();

        if ($driverEmailResult->num_rows > 0) {
            $driverEmail = $driverEmailResult->fetch_assoc()['email'];
            sendFineEmail($driverEmail, $fineDetails);
        }

        include "send-notification-driver.php";


        $_SESSION["message"] = "success";
        $_SESSION["redirect_after_alert"] = "/digifine/dashboard/officer/index.php";
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
