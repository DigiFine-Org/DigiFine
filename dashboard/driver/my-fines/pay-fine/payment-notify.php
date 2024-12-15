<?php
require_once "../../../../db/connect.php";

file_put_contents("ipn_debug.log", json_encode($_POST, JSON_PRETTY_PRINT), FILE_APPEND);


// Retrieve the IPN data
$merchant_id = $_POST['merchant_id'];
$order_id = $_POST['order_id'];
$payhere_amount = $_POST['payhere_amount'];
$payhere_currency = $_POST['payhere_currency'];
$status_code = $_POST['status_code'];
$md5sig_received = $_POST['md5sig'];


$merchant_secret = 'MjE0ODIwNjgzNTg4ODgxMzI2MDI4MzA3MDg1NjAzODU4NTA1NTE1';

// Generate the MD5 hash to validate the IPN
$md5sig_calculated = strtoupper(md5(
    $merchant_id . $order_id . $payhere_amount . $payhere_currency . $status_code . strtoupper(md5($merchant_secret))
));

// Validate the MD5 hash
if ($md5sig_received !== $md5sig_calculated) {
    die("Invalid IPN: Hash mismatch!");
}

// Validate the status code (2 = successful payment)
if ($status_code == 2) {
    // Extract Fine ID from the order ID
    $fine_id = str_replace("FINE-", "", $order_id);

    // Update the fine status in the database
    $sql = "UPDATE fines SET fine_status = 'paid' WHERE id = ?";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("Database error: " . $conn->error);
    }

    $stmt->bind_param("i", $fine_id);

    if ($stmt->execute()) {
        http_response_code(200); // Respond with success
        echo "Payment successfully processed for Fine ID: " . $fine_id;
    } else {
        http_response_code(500); // Respond with server error
        die("Failed to update fine status: " . $stmt->error);
    }

    $stmt->close();
} else {
    http_response_code(400); // Invalid payment status
    die("Payment failed or canceled!");
}

$conn->close();
