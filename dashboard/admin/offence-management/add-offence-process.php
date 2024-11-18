<?php
include '../../../db/connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $offence_number = htmlspecialchars($_POST['offence_number']);
    $description_sinhala = htmlspecialchars($_POST['description_sinhala']);
    $description_tamil = htmlspecialchars($_POST['description_tamil']);
    $description_english = htmlspecialchars($_POST['description_english']);
    $points_deducted = htmlspecialchars($_POST['points_deducted']);
    $fine = floatval($_POST['fine']);


    $sql = "INSERT INTO offences (offence_number, description_sinhala, description_tamil, description_english, points_deducted, fine)
            VALUES (?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("Error preparing statement: " . $conn->error);
    }


    $stmt->bind_param("ssssid", $offence_number, $description_sinhala, $description_tamil, $description_english, $points_deducted, $fine);


    if ($stmt->execute()) {
        echo "Offence added successfully!";
        header("Location: /digifine/dashboard/admin/offence-management/index.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }


    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request method.";
}

