<?php
session_start();
include_once "../../../db/connect.php"; 

if ($_SESSION['user']['role'] !== 'oic' && $_SESSION['user']['is_oic'] != 1) {
    die("Unauthorized access!");
}

$policeId = trim($_POST['policeId'] ?? "");
$duty = trim($_POST['duty'] ?? "");
$notes = trim($_POST['notes'] ?? "");

// Validate inputs
$errors = [];
if (empty($policeId)) {
    $errors[] = "Police ID is required.";
}
if (empty($duty)) {
    $errors[] = "Duty is required.";
}

if (!empty($errors)) {
    $_SESSION['errors'] = $errors;
    header("Location: index.php");
    exit;
}

try {
    // Fetch OIC's station
    $oicStationQuery = "SELECT police_station FROM officers WHERE id = ? AND is_oic = 1";
    $oicStationStmt = $conn->prepare($oicStationQuery);
    $oicStationStmt->bind_param("i", $_SESSION['user']['id']);
    $oicStationStmt->execute();
    $oicStationResult = $oicStationStmt->get_result();
    $oicStation = $oicStationResult->fetch_assoc();

    if (!$oicStation) {
        $_SESSION['error'] = "Unable to fetch your station information.";
        header("Location: index.php");
        exit;
    }

    $stationId = $oicStation['police_station'];

    // Verify Police ID and that the officer belongs to the same station
    $officerQuery = "SELECT id, police_station FROM officers WHERE id = ? AND is_oic = 0";
    $officerStmt = $conn->prepare($officerQuery);
    $officerStmt->bind_param("i", $policeId);
    $officerStmt->execute();
    $officerResult = $officerStmt->get_result();
    $officer = $officerResult->fetch_assoc();

    if (!$officer) {
        $_SESSION['error'] = "Invalid Police ID.";
        header("Location: index.php");
        exit;
    }

    if ($officer['police_station'] != $stationId) {
        $_SESSION['error'] = "The officer does not belong to your station.";
        header("Location: index.php");
        exit;
    }

    $query = "INSERT INTO assigned_duties (police_id, duty, notes, assigned_by) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);

    $assignedBy = $_SESSION['user']['id'];
    $stmt->bind_param("issi", $policeId, $duty, $notes, $assignedBy);
    
    if ($stmt->execute()) {
        $_SESSION['success'] = "Duty assigned successfully to Police ID: {$policeId}.";
    } else {
        $_SESSION['error'] = "Failed to assign duty. Please try again.";
    }

    header("Location: index.php");
} catch (Exception $e) {
    $_SESSION['error'] = "Error: " . $e->getMessage();
    header("Location: index.php");
}
?>
