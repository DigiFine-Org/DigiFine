<?php
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

