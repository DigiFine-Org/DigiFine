<?php
include '../../../db/connect.php';
include '../../../notifications/functions.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $police_id = htmlspecialchars($_POST['police_id']);
    $assigned_duty_id = htmlspecialchars($_POST['assigned_duty_id']);
    $patrol_location = htmlspecialchars($_POST['patrol_location']);
    $patrol_time_started = htmlspecialchars($_POST['patrol_time_started']);
    $patrol_time_ended = htmlspecialchars($_POST['patrol_time_ended']);
    $patrol_information = htmlspecialchars($_POST['patrol_information']);
    $is_late_submission = isset($_POST['is_late_submission']) ? (int) $_POST['is_late_submission'] : 0;

    $check_stmt = $conn->prepare("SELECT id FROM assigned_duties WHERE id = ?");
    $check_stmt->bind_param("i", $assigned_duty_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows === 0) {
        die("Error: assigned_duty_id does not exist in assigned_duties table.");
    }

    // Insert into duty_submissions table with late submission status
    $sql = "INSERT INTO duty_submissions (police_id, assigned_duty_id, patrol_location, patrol_time_started, patrol_time_ended, patrol_information, is_late_submission) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("Error preparing statement: " . $conn->error);
    }

    $stmt->bind_param("iissssi", $police_id, $assigned_duty_id, $patrol_location, $patrol_time_started, $patrol_time_ended, $patrol_information, $is_late_submission);

    if ($stmt->execute()) {

        // Get the submission ID for the notification
        $submission_id = $conn->insert_id;

        // Update 'submitted' column in assigned_duties table
        $update_stmt = $conn->prepare("UPDATE assigned_duties SET submitted = 1 WHERE id = ?");
        $update_stmt->bind_param("i", $assigned_duty_id);
        $update_stmt->execute();



        // Send notification to OIC
        include "send-submit-duty-notification-to-oic.php";

        $message = $is_late_submission ?
            "Duty submitted successfully (marked as late submission)" :
            "Duty submitted successfully";

        header("Location: /digifine/dashboard/officer/submit-duty/index.php?message=" . urlencode($message));
        exit();
    } else {
        die("Error inserting duty: " . $stmt->error);
    }

    $stmt->close();
    $conn->close();
} else {
    die("Invalid request method.");
}