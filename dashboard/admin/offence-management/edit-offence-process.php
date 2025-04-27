<?php
session_start();
require_once "../../../db/connect.php";

if ($_SESSION['user']['role'] !== 'admin') {
    die("Unauthorized user!");
}

// Initialize variables
$errors = [];
$offence_number = $_GET['offence_number'] ?? null;

// Get form data
$values = [
    'offence_number' => $offence_number,
    'description_sinhala' => htmlspecialchars(trim($_POST['description_sinhala'] ?? '')),
    'description_tamil' => htmlspecialchars(trim($_POST['description_tamil'] ?? '')),
    'description_english' => htmlspecialchars(trim($_POST['description_english'] ?? '')),
    'points_deducted' => htmlspecialchars(trim($_POST['points_deducted'] ?? '')),
    'fine_amount' => htmlspecialchars(trim($_POST['fine_amount'] ?? ''))
];

// Validate descriptions
foreach (['description_sinhala', 'description_tamil', 'description_english'] as $desc) {
    if (empty($values[$desc])) {
        $errors[$desc] = "This field is required.";
    } elseif (is_numeric($values[$desc])) {
        $errors[$desc] = "Numbers are not allowed in this field.";
    }
}

// Validate points
if (!preg_match('/^\d+$/', $values['points_deducted']) || $values['points_deducted'] < 0) {
    $errors['points_deducted'] = "Points must be a non-negative integer.";
}

// Validate fine amount
if (!preg_match('/^\d+(\.\d{1,2})?$/', $values['fine_amount']) || $values['fine_amount'] <= 0) {
    $errors['fine_amount'] = "Fine must be a non-negative number.";
}

// Check for duplicate offence number (if changing number)
if (empty($errors) && isset($values['new_offence_number']) && $values['new_offence_number'] != $offence_number) {
    $stmt = $conn->prepare("SELECT * FROM offences WHERE offence_number = ?");
    $stmt->bind_param("s", $values['new_offence_number']);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $errors['duplicate'] = "An offence with this number already exists";
    }
    $stmt->close();
}

// Update if no errors
if (empty($errors)) {
    $sql = "UPDATE offences SET 
        description_sinhala = ?, 
        description_tamil = ?, 
        description_english = ?, 
        points_deducted = ?, 
        fine_amount = ? 
        WHERE offence_number = ?";

    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param('sssdis', 
            $values['description_sinhala'],
            $values['description_tamil'],
            $values['description_english'],
            $values['points_deducted'],
            $values['fine_amount'],
            $values['offence_number']
        );
        
        if ($stmt->execute()) {
            $_SESSION['success_message'] = "Offence updated successfully";
            header("Location: /digifine/dashboard/admin/offence-management/index.php");
            exit();
        } else {
            $errors['database'] = "Database error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $errors['database'] = "Database error: " . $conn->error;
    }
}

// If there are errors, store them in session and redirect back
$_SESSION['form_errors'] = $errors;
$_SESSION['form_values'] = $values;
header("Location: edit-offence.php?offence_number=" . $offence_number);
exit();