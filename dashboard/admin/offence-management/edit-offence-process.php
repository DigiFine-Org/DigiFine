<?php
include '../../../db/connect.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $offence_number = $conn->real_escape_string($_POST['offence_number']);
    $description_sinhala = $conn->real_escape_string($_POST['description_sinhala']);
    $description_tamil = $conn->real_escape_string($_POST['description_tamil']);
    $description_english = $conn->real_escape_string($_POST['description_english']);
    $points_deducted = intval($_POST['points_deducted']);
    $fine = floatval($_POST['fine']);

    $sql = "UPDATE offences SET 
            description_sinhala = ?, 
            description_tamil = ?, 
            description_english = ?, 
            points_deducted = ?, 
            fine = ? 
        WHERE offence_number = ?";

    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("Error preparing statement: " . $conn->error);
    }

    $stmt->bind_param("sssids", $description_sinhala, $description_tamil, $description_english, $points_deducted, $fine, $offence_number);

    if ($stmt->execute()) {
        header("Location: /digifine/dashboard/admin/offence-management/index.php?message=Offence updated successfully");
        exit();
    } else {
        die("Error updating offence: " . $stmt->error);
    }
    

    ...
} else {
    die("Invalid request method.");
}


?>