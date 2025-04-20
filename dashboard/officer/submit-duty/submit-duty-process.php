<?php
include '../../../db/connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $police_id = htmlspecialchars($_POST['police_id']);
    $assigned_duty_id = htmlspecialchars($_POST['assigned_duty_id']);
    $patrol_location = htmlspecialchars($_POST['patrol_location']);
    $patrol_time_started = htmlspecialchars($_POST['patrol_time_started']);
    $patrol_time_ended = htmlspecialchars($_POST['patrol_time_ended']);
    $patrol_information = htmlspecialchars($_POST['patrol_information']);
    
    // Get current date and time in digital format
    $current_date = date('Y-m-d');
    $current_time = date('H:i:s');
    $current_datetime = date('Y-m-d H:i:s');

    // Check if assigned duty exists and get its time details
    $check_stmt = $conn->prepare("SELECT id, duty_date, duty_time_start, duty_time_end, submitted FROM assigned_duties WHERE id = ?");
    $check_stmt->bind_param("i", $assigned_duty_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows === 0) {
        die("Error: assigned_duty_id does not exist in assigned_duties table.");
    }

    $duty_info = $check_result->fetch_assoc();
    
    // Check if the duty is already submitted
    if ($duty_info['submitted'] == 1) {
        header("Location: /digifine/dashboard/officer/submit-duty/index.php?error=This duty has already been submitted");
        exit();
    }
    
    // Create datetime objects for comparison
    $duty_start_datetime = $duty_info['duty_date'] . ' ' . $duty_info['duty_time_start'];
    $duty_end_datetime = $duty_info['duty_date'] . ' ' . $duty_info['duty_time_end'];
    
    // Check if current time is before duty start time
    if ($current_datetime < $duty_start_datetime) {
        header("Location: /digifine/dashboard/officer/submit-duty/index.php?error=You cannot submit this duty before the assigned start time (" . date('H:i:s', strtotime($duty_info['duty_time_start'])) . ")");
        exit();
    }
    
    // Check if current time is after duty end time
    if ($current_datetime > $duty_end_datetime) {
        header("Location: /digifine/dashboard/officer/submit-duty/index.php?error=This duty has expired. You cannot submit after the end time (" . date('H:i:s', strtotime($duty_info['duty_time_end'])) . ")");
        exit();
    }

    // Insert into duty_submissions table
    $sql = "INSERT INTO duty_submissions (police_id, assigned_duty_id, patrol_location, patrol_time_started, patrol_time_ended, patrol_information) 
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("Error preparing statement: " . $conn->error);
    }

    $stmt->bind_param("iissss", $police_id, $assigned_duty_id, $patrol_location, $patrol_time_started, $patrol_time_ended, $patrol_information);

    if ($stmt->execute()) {
        // Update 'submitted' column in assigned_duties table
        $update_stmt = $conn->prepare("UPDATE assigned_duties SET submitted = 1 WHERE id = ?");
        $update_stmt->bind_param("i", $assigned_duty_id);
        $update_stmt->execute();

        header("Location: /digifine/dashboard/officer/submit-duty/index.php?message=Duty submitted successfully");
        exit();
    } else {
        die("Error inserting duty: " . $stmt->error);
    }

    $stmt->close();
    $conn->close();
} else {
    die("Invalid request method.");
}