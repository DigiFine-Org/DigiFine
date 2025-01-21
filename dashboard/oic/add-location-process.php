<?php
<<<<<<< HEAD
include '../../../db/connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = htmlspecialchars($_POST['id']);
    $police_station_id = htmlspecialchars($_POST['police_station_id']);
    $location_name = htmlspecialchars($_POST['location_name']);


    $sql = "INSERT INTO offences (id, police_station_id)
            VALUES (?, ?)";

    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("Error preparing statement: " . $conn->error);
    }


    $stmt->bind_param("is", $id, $police_station_id);


    if ($stmt->execute()) {
        echo "Offence added successfully!";
=======
include '../../db/connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate input
    $location_name = isset($_POST['location_name']) ? trim($_POST['location_name']) : null;
    $police_station_id = isset($_POST['police_station_id']) ? intval($_POST['police_station_id']) : null;

    // Check if both fields are provided
    if (empty($location_name) || empty($police_station_id)) {
        die("Location name and police station ID are required.");
    }

    // Prepare SQL query
    $sql = "INSERT INTO duty_locations (police_station_id, location_name) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("Error preparing statement: " . $conn->error);
    }

    // Bind parameters
    $stmt->bind_param("is", $police_station_id, $location_name);

    // Execute query
    if ($stmt->execute()) {
        echo "Duty location added successfully!";
>>>>>>> 4035893fb72d6cee2accb82ad812e8d242fd64c3
        header("Location: /digifine/dashboard/oic/index.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

<<<<<<< HEAD

=======
    // Close resources
>>>>>>> 4035893fb72d6cee2accb82ad812e8d242fd64c3
    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request method.";
}
<<<<<<< HEAD

=======
>>>>>>> 4035893fb72d6cee2accb82ad812e8d242fd64c3
