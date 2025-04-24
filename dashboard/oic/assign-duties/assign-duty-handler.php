<?php
session_start();
include_once "../../../db/connect.php";

if ($_SESSION['user']['role'] !== 'oic' && $_SESSION['user']['is_oic'] != 1) {
    die("Unauthorized access!");
}

// Retrieve form inputs
$policeId = trim($_POST['policeId'] ?? "");
$duty = trim($_POST['duty'] ?? "");
$dutyDate = trim($_POST['dutyDate'] ?? "");
$dutyTimeStart = trim($_POST['duty_time_start'] ?? "");
$dutyTimeEnd = trim($_POST['duty_time_end'] ?? "");
$notes = trim($_POST['notes'] ?? "");

// Validate inputs
$errors = [];
if (empty($policeId) || !is_numeric($policeId)) {
    $errors[] = "Valid Police ID is required.";
}
if (empty($duty)) {
    $errors[] = "Duty is required.";
}
if (empty($dutyDate)) {
    $errors[] = "Duty Date is required.";
} elseif (strtotime($dutyDate) < strtotime(date('Y-m-d'))) {
    $errors[] = "Duty Date cannot be in the past.";
}
if (empty($dutyTimeStart)) {
    $errors[] = "Duty start time is required.";
}
if (empty($dutyTimeEnd)) {
    $errors[] = "Duty end time is required.";
} elseif (strtotime($dutyTimeEnd) <= strtotime($dutyTimeStart)) {
    $errors[] = "Duty end time must be after the start time.";
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

    if ($oicStationStmt === false) {
        throw new Exception("Database error: " . $conn->error);
    }

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
    $officerQuery = "SELECT id, police_station, CONCAT(fname, ' ', lname) as officer_name FROM officers WHERE id = ? AND is_oic = 0";
    $officerStmt = $conn->prepare($officerQuery);

    if ($officerStmt === false) {
        throw new Exception("Database error: " . $conn->error);
    }

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

    // Check for overlapping duties
    $overlapQuery = "SELECT id, duty FROM assigned_duties 
                    WHERE police_id = ? 
                    AND duty_date = ? 
                    AND submitted = 0
                    AND (
                        (duty_time_start <= ? AND duty_time_end > ?) OR  -- New duty starts during existing duty
                        (duty_time_start < ? AND duty_time_end >= ?) OR  -- New duty ends during existing duty
                        (duty_time_start >= ? AND duty_time_end <= ?)    -- Existing duty is within new duty
                    )";

    $overlapStmt = $conn->prepare($overlapQuery);

    if ($overlapStmt === false) {
        throw new Exception("Database error: " . $conn->error);
    }

    $overlapStmt->bind_param("isssssss", $policeId, $dutyDate, $dutyTimeEnd, $dutyTimeStart, $dutyTimeEnd, $dutyTimeStart, $dutyTimeStart, $dutyTimeEnd);
    $overlapStmt->execute();
    $overlapResult = $overlapStmt->get_result();

    if ($overlapResult->num_rows > 0) {
        $overlappingDuty = $overlapResult->fetch_assoc();
        $_SESSION['error'] = "Cannot assign this duty. Officer already has conflicting duty \"" . $overlappingDuty['duty'] . "\" scheduled during this time period.";
        header("Location: index.php");
        exit;
    }

    // First check if the assigned_duties table exists
    $checkTableQuery = "SHOW TABLES LIKE 'assigned_duties'";
    $tableResult = $conn->query($checkTableQuery);

    if ($tableResult->num_rows == 0) {
        // Table doesn't exist, create it
        $createTableQuery = "CREATE TABLE assigned_duties (
            id INT(11) AUTO_INCREMENT PRIMARY KEY,
            police_id INT(11) NOT NULL,
            duty VARCHAR(255) NOT NULL,
            duty_date DATE NOT NULL,
            duty_time_start TIME NOT NULL,
            duty_time_end TIME NOT NULL,
            notes TEXT,
            assigned_by INT(11) NOT NULL,
            submitted TINYINT(1) DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";

        if (!$conn->query($createTableQuery)) {
            throw new Exception("Failed to create required table: " . $conn->error);
        }
    }

    // Insert the duty assignment into the database
    $query = "INSERT INTO assigned_duties (police_id, duty, duty_date, duty_time_start, duty_time_end, notes, assigned_by) 
              VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);

    if ($stmt === false) {
        throw new Exception("Database error: " . $conn->error);
    }

    $assignedBy = $_SESSION['user']['id'];
    $stmt->bind_param("isssssi", $policeId, $duty, $dutyDate, $dutyTimeStart, $dutyTimeEnd, $notes, $assignedBy);

    if ($stmt->execute()) {
        // Notify the officer about the new duty assignment
        include "send-duty-notification-officer.php";
        $_SESSION['success'] = "Duty assigned successfully to Officer: {$officer['officer_name']} (ID: {$policeId}).";
    } else {
        $_SESSION['error'] = "Failed to assign duty: " . $stmt->error;
    }

    // Close statements
    if (isset($oicStationStmt))
        $oicStationStmt->close();
    if (isset($officerStmt))
        $officerStmt->close();
    if (isset($overlapStmt))
        $overlapStmt->close();
    if (isset($stmt))
        $stmt->close();

    header("Location: index.php");
    exit;

} catch (Exception $e) {
    $_SESSION['error'] = "Error: " . $e->getMessage();
    header("Location: index.php");
    exit;
}
?>