<?php

// Create detailed notification message with all vehicle information
$notificationTitle = "New Stolen Vehicle Report";
$notificationMessage = "A new stolen vehicle has been reported with the following details:\n\n" .
    "License Plate: $license_plate_number\n" .
    "Owner: $absoluteOwner\n" .
    "Make: $make\n" .
    "Model: $model\n" .
    "Color: $colour\n" .
    "Engine Number: $engineNo\n" .
    "Registration Date: $dateOfRegistration\n" .
    "Date Reported Stolen: $dateReportedStolen\n" .
    "Last Seen Location: $locationLastSeen\n" .
    "Last Seen Date: $lastSeenDate\n" .
    "Current Status: $status";

// Insert into notifications table
$notifyQuery = "INSERT INTO notifications (title, message, reciever_type, source, is_read, created_at) 
                VALUES (?, ?, 'admin', 'stolen_vehicle_system', 0, NOW())";
$notifyStmt = $conn->prepare($notifyQuery);

if ($notifyStmt === false) {
    // Log the error but don't halt the process
    error_log("Failed to create notification: " . $conn->error);
} else {
    $notifyStmt->bind_param("ss", $notificationTitle, $notificationMessage);
    $notifyStmt->execute();
    $notifyStmt->close();
}