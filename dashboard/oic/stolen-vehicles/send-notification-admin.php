<?php
require_once __DIR__ . "/../../../notifications/functions.php";


$license_plate_number = $license_plate_number ?? 'N/A';
$absoluteOwner = $absoluteOwner ?? 'N/A';
$model = $model ?? 'N/A';
$colour = $colour ?? 'N/A';
$engineNo = $engineNo ?? 'N/A';
$dateOfRegistration = $dateOfRegistration ?? 'N/A';
$dateReportedStolen = $dateReportedStolen ?? 'N/A';
$locationLastSeen = $locationLastSeen ?? 'N/A';
$lastSeenDate = $lastSeenDate ?? 'N/A';


$notificationTitle = "New Stolen Vehicle Report";
$notificationMessage = "A new stolen vehicle has been reported with the following details:\n\n" .
    "License Plate: " . htmlspecialchars($license_plate_number) . "\n" .
    "Owner: " . htmlspecialchars($absoluteOwner) . "\n" .
    "Model: " . htmlspecialchars($model) . "\n" .
    "Color: " . htmlspecialchars($colour) . "\n" .
    "Engine Number: " . htmlspecialchars($engineNo) . "\n" .
    "Registration Date: " . htmlspecialchars($dateOfRegistration) . "\n" .
    "Date Reported Stolen: " . htmlspecialchars($dateReportedStolen) . "\n" .
    "Last Seen Location: " . htmlspecialchars($locationLastSeen) . "\n" .
    "Last Seen Date: " . htmlspecialchars($lastSeenDate);



notify_admin(
    '1234', 
    $notificationTitle,
    $notificationMessage,
    'stolen_vehicle_system',
    null 
);