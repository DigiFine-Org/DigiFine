<?php

$dutyId = $conn->insert_id;

$notificationTitle = "New Duty Assigned";
$notificationMessage = "You have been assigned a new duty: {$duty} on " . 
                           date('Y-m-d', strtotime($dutyDate)) . 
                           " from " . date('h:i A', strtotime($dutyTimeStart)) . 
                           " to " . date('h:i A', strtotime($dutyTimeEnd)) . 
                           ($notes ? "\n\nNotes: {$notes}" : "");

 // Insert into notifications table
 $notifyQuery = "INSERT INTO notifications (title, message, reciever_id, reciever_type, source, is_read, created_at) 
 VALUES (?, ?, ?, 'officer', 'duty_assignment', 0, NOW())";
$notifyStmt = $conn->prepare($notifyQuery);

if ($notifyStmt === false) {
// Log the error but don't halt the process
error_log("Failed to create notification: " . $conn->error);
} else {
$notifyStmt->bind_param("ssi", $notificationTitle, $notificationMessage, $policeId);
$notifyStmt->execute();
$notifyStmt->close();
}