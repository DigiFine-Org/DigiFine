<?php
require_once "../../../db/connect.php";

// Check if form is submitted via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $license_plate_number = $_POST['license_plate_number'] ?? '';
    $seizure_date_time = $_POST['seizure_date_time'] ?? '';
    $seized_location = $_POST['seized-location'] ?? '';
    $officer_id = $_POST['officer_id'] ?? '';
    $officer_name = $_POST['officer_name'] ?? '';
    $police_station = $_POST['police_station'] ?? '';
    $driver_NIC = $_POST['driver_NIC'] ?? '';
    $owner_name = $_POST['owner_name'] ?? '';


    // Validate required fields
    if (
        empty($license_plate_number) || empty($seizure_date_time) || empty($seized_location) ||
        empty($officer_id) || empty($officer_name) || empty($police_station) || empty($driver_NIC) || empty($owner_name)
    ) {
        die("All fields are required!");
    }

    // Prepare the SQL query to insert into seized_vehicle table
    $sql = "INSERT INTO seized_vehicle 
            (license_plate_number, seizure_date_time, seized_location, officer_id, officer_name, police_station, driver_NIC,owner_name) 
            VALUES (?, ?, ?, ?, ?, ?, ?,?)";

    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("Error preparing statement: " . $conn->error);
    }

    $stmt->bind_param(
        "ssssssss",
        $license_plate_number,
        $seizure_date_time,
        $seized_location,
        $officer_id,
        $officer_name,
        $police_station,
        $driver_NIC,
        $owner_name
    );

    if ($stmt->execute()) {
        // Update the stolen vehicle status in dmt_vehicles
        $updateSql = "UPDATE dmt_vehicles SET is_stolen = 0 WHERE license_plate_number = ?";
        $updateStmt = $conn->prepare($updateSql);

        if ($updateStmt) {
            $updateStmt->bind_param("s", $license_plate_number);
            $updateStmt->execute();
            $updateStmt->close();
        }

        $deleteSql = "DELETE FROM stolen_vehicles WHERE license_plate_number = ?";
        $deleteStmt = $conn->prepare($deleteSql);

        if ($deleteStmt) {
            $deleteStmt->bind_param("s", $license_plate_number);
            $deleteStmt->execute();
            $deleteStmt->close();
        }

        echo "Vehicle seized successfully!";
        // header("Location: ../../s.php");
        exit();
    } else {
        echo "Error inserting data: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    die("Invalid request method.");
}
?>