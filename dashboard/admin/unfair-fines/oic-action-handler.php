<?php
include('../../../db/connect.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fine_id = $_POST['fine_id'];
    $action = $_POST['action'];

    if ($action == 'mark_unfair') {
        $stmt = $conn->prepare("DELETE FROM unfair_fines WHERE fine_id = ?");
        $stmt->bind_param("i", $fine_id);
        $stmt->execute();

        $stmt = $conn->prepare("DELETE FROM driver_fines WHERE fine_id = ?");
        $stmt->bind_param("i", $fine_id);
        $stmt->execute();

        header("Location: index.php?message=Marked as Unfair and removed.");
        exit();

    } elseif ($action == 'mark_fair') {
        $stmt = $conn->prepare("UPDATE unfair_fines SET status = 'Fair' WHERE fine_id = ?");
        $stmt->bind_param("i", $fine_id);
        $stmt->execute();

        // Add a notification for the driver
        $stmt = $conn->prepare("INSERT INTO notifications (driver_id, message) VALUES (?, ?)");
        $driver_id = /* fetch driver_id associated with fine_id */;
        $message = "Your reported fine (ID: $fine_id) has been reviewed and marked as fair.";
        $stmt->bind_param("is", $driver_id, $message);
        $stmt->execute();

        header("Location: index.php?message=Marked as Fair and driver notified.");
        exit();
    }
}
?>
