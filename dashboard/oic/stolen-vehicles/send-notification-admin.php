<?php

require_once __DIR__ . "/../../../notifications/functions.php";


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

// Call notify_admin function with the notification details
notify_admin(
    '1234',
    $notificationTitle,
    $notificationMessage,
    'stolen_vehicle_system',
    null // Using default expiration
);
