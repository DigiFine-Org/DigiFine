<?php

include '../../../db/connect.php';


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fine_id = isset($_POST['fine_id']) ? intval($_POST['fine_id']) : null;
    $reported_description = isset($_POST['reported_description']) ? htmlspecialchars($_POST['reported_description']) : '';

    
    if (!$fine_id || empty($reported_description)) {
        die("Error: Missing required fields.");
    }

    $sql = "UPDATE fines SET is_reported = 1, reported_description = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("Error preparing statement: " . $conn->error);
    }

    $stmt->bind_param("si", $reported_description, $fine_id);

    if ($stmt->execute()) {
        header("Location: index.php?fine_id=$fine_id&message=Fine reported successfully");
        exit();
    } else {
        die("Error updating fine: " . $stmt->error);
    }

    
    $stmt->close();
    $conn->close();

} else {
    die("Invalid request method.");
}
