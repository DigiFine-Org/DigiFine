<?php
include '../../../db/connect.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Validate and sanitize input
    if(!isset($_POST['offence_number']) || 
       !isset($_POST['description_sinhala']) || 
       !isset($_POST['description_tamil']) || 
       !isset($_POST['description_english']) || 
       !isset($_POST['points_deducted']) || 
       !isset($_POST['fine_amount'])) {
        die("Missing required fields");
    }

    $offence_number = $conn->real_escape_string($_POST['offence_number']);
    $description_sinhala = $conn->real_escape_string($_POST['description_sinhala']);
    $description_tamil = $conn->real_escape_string($_POST['description_tamil']);
    $description_english = $conn->real_escape_string($_POST['description_english']);
    $points_deducted = intval($_POST['points_deducted']);
    $fine_amount = floatval($_POST['fine_amount']);

    $sql = "UPDATE offences SET 
            description_sinhala = ?, 
            description_tamil = ?, 
            description_english = ?, 
            points_deducted = ?, 
            fine_amount = ? 
        WHERE offence_number = ?";

    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("Error preparing statement: " . $conn->error);
    }

    $stmt->bind_param("sssids", $description_sinhala, $description_tamil, $description_english, $points_deducted, $fine_amount, $offence_number);

    if ($stmt->execute()) {
        header("Location: /digifine/dashboard/admin/offence-management/index.php?message=Offence updated successfully");
        exit();
    } else {
        die("Error updating offence: " . $stmt->error);
    }

    if ($stmt) {
        $stmt->close();
    }
       
} else {
    die("Invalid request method.");
}

if ($conn) {
    $conn->close();
}
?>