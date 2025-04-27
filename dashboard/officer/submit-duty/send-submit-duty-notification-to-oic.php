<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);


if (!isset($assigned_duty_id)) {
    error_log("assigned_duty_id not set in send-submit-duty-notification-to-oic.php");
    die("Required variable not set");
}

if (!isset($is_late_submission)) {
    error_log("is_late_submission not set in send-submit-duty-notification-to-oic.php");
    $is_late_submission = 0; 
}


$officer_query = $conn->prepare("
SELECT 
    CONCAT(o.fname, ' ', o.lname) AS officer_name,
    d.duty, d.notes,
    o2.id AS oic_id
FROM 
    assigned_duties d
    JOIN officers o ON d.police_id = o.id
    JOIN officers o2 ON o.police_station = o2.police_station AND o2.is_oic = 1
WHERE 
    d.id = ?
");

if (!$officer_query) {
    error_log("Failed to prepare query: " . $conn->error);
    die("Database error occurred");
}

$officer_query->bind_param("i", $assigned_duty_id);

if (!$officer_query->execute()) {
    error_log("Failed to execute query: " . $officer_query->error);
    die("Database error occurred");
}

$result = $officer_query->get_result();

if ($result->num_rows === 0) {
    error_log("No results found for assigned_duty_id: $assigned_duty_id");
    die("No matching duty assignment found");
}

$row = $result->fetch_assoc();


if (!function_exists('notify_oic')) {
    error_log("notify_oic function not found");
    die("Notification function not available");
}


try {
    $notification_title = $is_late_submission ? "Late Duty Submission" : "Duty Submission";
    $notification_message = $is_late_submission
        ? "Officer {$row['officer_name']} has submitted duty '{$row['duty']}' late."
        : "Officer {$row['officer_name']} has submitted duty '{$row['duty']}'.";

    notify_oic(
        $row['oic_id'],
        $notification_title,
        $notification_message,
        'duty_submission'
    );
} catch (Exception $e) {
    error_log("Failed to send notification: " . $e->getMessage());

}
