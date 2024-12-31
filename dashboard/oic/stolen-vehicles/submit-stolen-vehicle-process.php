<?php
require_once "../../../db/connect.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $vehicleNumber = $_POST['vehicle_number'] ?? '';
    $absoluteOwner = $_POST['absolute_owner'] ?? '';
    $engineNo = $_POST['engine_no'] ?? '';
    $make = $_POST['make'] ?? '';
    $model = $_POST['model'] ?? '';
    $colour = $_POST['colour'] ?? '';
    $dateOfRegistration = $_POST['date_of_registration'] ?? '';
    $status = $_POST['status'] ?? '';
    $dateReportedStolen = $_POST['date_reported_stolen'] ?? '';
    $locationLastSeen = $_POST['location_last_seen'] ?? '';
    $lastSeenDate = $_POST['last_seen_date'] ?? '';

    // Validate last_seen_date
    if (strtotime($lastSeenDate) > time()) {
        die("Error: 'Date Last Seen' cannot be in the future.");
    }

    // Check if vehicle_number exists in dmt_vehicles
    $checkSql = "SELECT vehicle_number FROM dmt_vehicles WHERE vehicle_number = ?";
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->bind_param("s", $vehicleNumber);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();

    if ($checkResult->num_rows === 0) {
        die("Error: The vehicle number does not exist in the registered vehicles database.");
    }

    $checkStmt->close();

    // Insert data into stolen_vehicles
    $sql = "INSERT INTO stolen_vehicles 
            (vehicle_number, absolute_owner, engine_no, make, model, colour, date_of_registration, 
            status, date_reported_stolen, location_last_seen, last_seen_date) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("Error preparing statement: " . $conn->error);
    }

    $stmt->bind_param("sssssssssss", $vehicleNumber, $absoluteOwner, $engineNo, $make, $model, $colour, 
                      $dateOfRegistration, $status, $dateReportedStolen, $locationLastSeen, $lastSeenDate);

    if ($stmt->execute()) {
        // Update the is_stolen column in dmt_vehicles
        $updateSql = "UPDATE dmt_vehicles SET is_stolen = 1 WHERE vehicle_number = ?";
        $updateStmt = $conn->prepare($updateSql);

        if ($updateStmt) {
            $updateStmt->bind_param("s", $vehicleNumber);
            if ($updateStmt->execute()) {
                echo "Stolen vehicle reported successfully and status updated!";
            } else {
                echo "Error updating is_stolen column: " . $updateStmt->error;
            }
            $updateStmt->close();
        } else {
            echo "Error preparing update statement: " . $conn->error;
        }
    } else {
        echo "Error executing query: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
