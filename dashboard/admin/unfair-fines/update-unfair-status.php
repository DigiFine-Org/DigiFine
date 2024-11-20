<?php

include('../../../db/connect.php');


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['unfair_fine_id']) && isset($_POST['status'])) {
    $unfair_fine_id = $_POST['unfair_fine_id'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("UPDATE unfair_fines SET status = ?, updated_at = NOW() WHERE unfair_fine_id = ?");
    $stmt->bind_param("si", $status, $unfair_fine_id);

    if ($stmt->execute()) {
        echo "Status updated successfully!";
        header("Location: index.php?message=Status Updated Successfully");
        exit();
    } else {
        echo "Error updating status: " . $conn->error;
    }
    $stmt->close();
} else {
    echo "Invalid request.";
}

$conn->close();
?>
