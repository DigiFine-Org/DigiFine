<?php
require_once "../../../../db/connect.php";

// Log IPN data for debugging
file_put_contents("ipn_debug.log", json_encode($_POST, JSON_PRETTY_PRINT), FILE_APPEND);

// Retrieve the IPN data
$merchant_id = $_POST['merchant_id'];
$order_id = $_POST['order_id'];
$payhere_amount = $_POST['payhere_amount'];
$payhere_currency = $_POST['payhere_currency'];
$status_code = $_POST['status_code'];
$md5sig_received = $_POST['md5sig'];

// Your Merchant Secret
$merchant_secret = 'MjE0ODIwNjgzNTg4ODgxMzI2MDI4MzA3MDg1NjAzODU4NTA1NTE1';

// Generate the MD5 hash to validate the IPN
$md5sig_calculated = strtoupper(md5(
    $merchant_id . $order_id . $payhere_amount . $payhere_currency . $status_code . strtoupper(md5($merchant_secret))
));

// Validate the MD5 hash
if ($md5sig_received !== $md5sig_calculated) {
    die("Invalid IPN: Hash mismatch!");
}

// Check if the payment status is SUCCESSFUL (2 = successful payment)
if ($status_code == 2) {
    // Extract Fine ID from the order ID (e.g., "FINE-123" -> 123)
    $fine_id = str_replace("FINE-", "", $order_id);

    // Start a transaction for atomic updates
    $conn->begin_transaction();

    try {
        // Update the 'fines' table to mark fine as 'paid'
        $updateFinesSql = "UPDATE fines SET fine_status = 'paid' WHERE id = ?";
        $stmt = $conn->prepare($updateFinesSql);
        if (!$stmt) {
            throw new Exception("Database error: " . $conn->error);
        }
        $stmt->bind_param("i", $fine_id);
        $stmt->execute();
        $stmt->close();

        // Update the 'payments' table to mark payment as 'completed'
        $updatePaymentsSql = "UPDATE payments SET status = 'completed', paid_amount = ?, payment_date = NOW() 
                              WHERE order_id = ?";
        $stmt = $conn->prepare($updatePaymentsSql);
        if (!$stmt) {
            throw new Exception("Database error: " . $conn->error);
        }
        $stmt->bind_param("ds", $payhere_amount, $order_id); // "d" for double (amount), "s" for string (order_id)
        $stmt->execute();
        $stmt->close();

        // Commit the transaction
        $conn->commit();

        // Respond with HTTP 200 (success)
        http_response_code(200);
        echo "Payment successfully processed for Fine ID: " . $fine_id;
    } catch (Exception $e) {
        // Rollback on error
        $conn->rollback();
        http_response_code(500);
        die("Failed to process payment: " . $e->getMessage());
    }
} else {
    // Respond with HTTP 400 if payment was not successful
    http_response_code(400);
    die("Payment failed or canceled!");
}

$conn->close();
