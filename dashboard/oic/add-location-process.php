<?php
include '../../db/connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $location_name = isset($_POST['location_name']) ? trim($_POST['location_name']) : null;
    $police_station_id = isset($_POST['police_station_id']) ? intval($_POST['police_station_id']) : null;


    if (empty($location_name) || empty($police_station_id)) {
        die("Location name and police station ID are required.");
    }


    $sql = "INSERT INTO duty_locations (police_station_id, location_name) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("Error preparing statement: " . $conn->error);
    }


    $stmt->bind_param("is", $police_station_id, $location_name);


    if ($stmt->execute()) {
        echo "Duty location added successfully!";
        header("Location: /digifine/dashboard/oic/index.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }



    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request method.";
}
