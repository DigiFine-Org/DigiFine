<?php

include '../../../db/connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $police_id = htmlspecialchars($_POST['police_id']);
    $patrol_location = htmlspecialchars($_POST['patrol_location']);
    $patrol_time_start = htmlspecialchars($_POST['patrol_time_start']);
    $patrol_time_end = htmlspecialchars($_POST['patrol_time_end']);
    $patrol_information = htmlspecialchars($_POST['patrol_information']);

    // Insert duty submisiion to database
    $sql = "INSERT INTO duty_submissions (police_id, patrol_location, patrol_time_start, patrol_time_end, patrol_information) 
    VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("Error preparing statement: " . $conn->error);
    }

    $stmt->bind_param("issss", $police_id, $patrol_location, $patrol_time_start, $patrol_time_end, $patrol_information);

    if ($stmt->execute()) {
        echo "Duty submitted successfully!";
        header("Location: /digifine/dashboard/officer/submit-duty/index.php?message=Duty submitted successfully");
        exit();
    } else {
        die("Error inserting fine: " . $stmt->error);
    }

    $stmt->close();
    $conn->close();
} else {
    die("Invalid request method.");
}