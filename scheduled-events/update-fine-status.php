<?php
include_once "../includes/header.php";
require_once "../db/connect.php";

$currentDate = date('Y-m-d');

$sql = "
    UPDATE fines
    SET status = 'overdue'
    WHERE status = 'pending' AND DATE(expire_date) < '$currentDate';
";

if ($conn->query($sql) === TRUE) {
    echo "Fine statuses updated successfully.";
} else {
    echo "Error updating records: " . $conn->error;
}

$conn->close();
