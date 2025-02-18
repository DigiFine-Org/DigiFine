<?php

include '../../../db/connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fine_id = isset($_POST['fine_id']) ? intval($_POST['fine_id']) : null;
    $reported_description = isset($_POST['reported_description']) ? htmlspecialchars($_POST['reported_description'], ENT_QUOTES, 'UTF-8') : '';
    $evidence_path = null;

    // Validate inputs
    if (!$fine_id || empty($reported_description)) {
        die("Error: Missing required fields.");
    }

    // Debugging: Check if the fine_id exists in the database
    $check_sql = "SELECT id, evidence FROM fines WHERE id = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("i", $fine_id);
    $check_stmt->execute();
    $result = $check_stmt->get_result();
    $fine_data = $result->fetch_assoc();
    $check_stmt->close();

    if (!$fine_data) {
        die("Error: Fine ID does not exist in the database.");
    }

    // File Upload Process
    if (!empty($_FILES['evidence']['name'])) {
        $upload_dir = '../../../uploads/evidence/';
        if (!is_dir($upload_dir)) {
            if (!mkdir($upload_dir, 0777, true)) {
                die("Error: Failed to create upload directory.");
            }
        }

        $file_name = basename($_FILES['evidence']['name']);
        $unique_file_name = uniqid() . '_' . $file_name; 
        $target_file = $upload_dir . $unique_file_name;
        $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Allowed file types
        $allowed_types = ['jpg', 'jpeg', 'png', 'pdf'];
        if (!in_array($file_type, $allowed_types)) {
            die("Error: Only JPG, JPEG, PNG, and PDF files are allowed.");
        }

        // File size check (limit: 5MB)
        if ($_FILES['evidence']['size'] > 5 * 1024 * 1024) {
            die("Error: File size exceeds 5MB.");
        }

        // Move the uploaded file
        if (move_uploaded_file($_FILES['evidence']['tmp_name'], $target_file)) {
            $evidence_path = 'uploads/evidence/' . $unique_file_name;
        } else {
            die("Error: Failed to upload file.");
        }
    } else {
        // If no new file is uploaded, retain the existing evidence path
        $evidence_path = $fine_data['evidence'];
    }

    // Debugging: Check final evidence path
    if ($evidence_path) {
        echo "Evidence Path: " . $evidence_path . "<br>";
    } else {
        echo "No evidence provided or retained.<br>";
    }

    // Update the fines table
    $sql = "UPDATE fines SET is_reported = 1, reported_description = ?, evidence = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("Error preparing statement: " . $conn->error);
    }

    $stmt->bind_param("ssi", $reported_description, $evidence_path, $fine_id);

    if ($stmt->execute()) {
        echo "Database updated successfully.<br>";
        header("Location: index.php?fine_id=$fine_id&message=Fine reported successfully");
        exit();
    } else {
        die("Error updating fine: " . $stmt->error);
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
} else {
    die("Invalid request method.");
}
