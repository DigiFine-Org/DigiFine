<?php
require_once "../../../../db/connect.php";

// Log IPN data for debugging with timestamps
file_put_contents("ipn_debug.log", "[" . date("Y-m-d H:i:s") . "] " . json_encode($_POST, JSON_PRETTY_PRINT) . PHP_EOL, FILE_APPEND);


// Ensure necessary POST data is received
if (!isset($_POST['merchant_id'], $_POST['order_id'], $_POST['payhere_amount'], $_POST['payhere_currency'], $_POST['status_code'], $_POST['md5sig'])) {
    http_response_code(400);
    die("Missing required parameters.");
}

// Sanitize POST data
$merchant_id = htmlspecialchars($_POST['merchant_id']);
$order_id = htmlspecialchars($_POST['order_id']);
$payhere_amount = floatval($_POST['payhere_amount']);
$payhere_currency = htmlspecialchars($_POST['payhere_currency']);
$status_code = intval($_POST['status_code']);
$md5sig_received = htmlspecialchars($_POST['md5sig']);

// Your Merchant Secret
$merchant_secret = 'MjE0ODIwNjgzNTg4ODgxMzI2MDI4MzA3MDg1NjAzODU4NTA1NTE1';

// Generate the MD5 hash to validate the IPN
$md5sig_calculated = strtoupper(md5(
    $merchant_id . $order_id . $payhere_amount . $payhere_currency . $status_code . strtoupper(md5($merchant_secret))
));

// Validate the MD5 hash
if ($md5sig_received !== $md5sig_calculated) {
    http_response_code(400);
    die("Invalid IPN: Hash mismatch!");
}

// Check if the payment status is SUCCESSFUL (2 = successful payment)
if ($status_code == 2) {
    // Extract Fine ID from the order ID (assuming format "FINE-123")
    if (!preg_match('/^FINE-(\d+)$/', $order_id, $matches)) {
        http_response_code(400);
        die("Invalid order ID format.");
    }
    $fine_id = intval($matches[1]);

    // Fetch payer details
    $payer_first_name = htmlspecialchars($_POST['first_name'] ?? '');
    $payer_last_name = htmlspecialchars($_POST['last_name'] ?? '');
    $payer_email = htmlspecialchars($_POST['email'] ?? '');
    $payer_phone = htmlspecialchars($_POST['phone'] ?? '');
    $payer_address = htmlspecialchars($_POST['address'] ?? '');
    $payer_city = htmlspecialchars($_POST['city'] ?? '');
    $payer_country = htmlspecialchars($_POST['country'] ?? '');
    $hash = htmlspecialchars($_POST['hash'] ?? '');

    // Start a transaction for atomic updates
    $conn->begin_transaction();

    try {
        // Verify the fine exists before updating
        $checkFineSql = "SELECT fine_status FROM fines WHERE id = ? FOR UPDATE";
        $stmt = $conn->prepare($checkFineSql);
        if (!$stmt) {
            throw new Exception("Database error: " . $conn->error);
        }
        $stmt->bind_param("i", $fine_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows === 0) {
            throw new Exception("Fine not found.");
        }

        $fine = $result->fetch_assoc();
        if ($fine['fine_status'] === 'paid') {
            throw new Exception("Fine is already paid.");
        }
        $stmt->close();

        // Update the 'fines' table to mark fine as 'paid'
        $updateFinesSql = "UPDATE fines SET fine_status = 'paid' WHERE id = ?";
        $stmt = $conn->prepare($updateFinesSql);
        if (!$stmt) {
            throw new Exception("Database error: " . $conn->error);
        }
        $stmt->bind_param("i", $fine_id);
        $stmt->execute();
        $stmt->close();

        // Insert into 'payments' table if not exists (prevents duplicates)
        $insertPaymentsSql = "INSERT INTO payments (fine_id, merchant_id, order_id, amount, currency, payer_first_name, payer_last_name, payer_email, payer_phone, 
            payer_address, payer_city, payer_country, status, hash, created_at) 
            VALUES 
            (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'success', ?, NOW()) 
            ON DUPLICATE KEY UPDATE 
            status = 'success', updated_at = NOW()";

        $stmt = $conn->prepare($insertPaymentsSql);

        if (!$stmt) {
            file_put_contents("ipn_error.log", "[" . date("Y-m-d H:i:s") . "] SQL Prepare Error: " . $conn->error . "\n", FILE_APPEND);
            die("SQL Prepare Error: " . $conn->error);
        }

        $stmt->bind_param("issdsssssssss", 
            $fine_id, $merchant_id, $order_id, $payhere_amount, $payhere_currency, 
            $payer_first_name, $payer_last_name, $payer_email, $payer_phone, 
            $payer_address, $payer_city, $payer_country, $hash
        );

        if (!$stmt->execute()) {
            file_put_contents("ipn_error.log", "[" . date("Y-m-d H:i:s") . "] SQL Execution Error: " . $stmt->error . "\n", FILE_APPEND);
            die("SQL Execution Error: " . $stmt->error);
        }

        $conn->commit();

        http_response_code(200);
        echo "Payment successfully recorded for Fine ID: " . $fine_id;
        


    } catch (Exception $e) {
        // Rollback on error
        $conn->rollback();
        http_response_code(500);
        die("Failed to process payment: " . $e->getMessage());
    }
} else {
    // Log unsuccessful payment
    file_put_contents("ipn_failed.log", "[" . date("Y-m-d H:i:s") . "] Payment failed for Order ID: $order_id, Status Code: $status_code.\n", FILE_APPEND);

    // Respond with HTTP 400 if payment was not successful
    http_response_code(400);
    die("Payment failed or canceled!");
}


// Fetch the driver's email
$driverEmailSql = "SELECT email FROM drivers WHERE id = (SELECT driver_id FROM fines WHERE id = ?)";
$stmt = $conn->prepare($driverEmailSql);
$stmt->bind_param("i", $fine_id);
$stmt->execute();
$driverEmailResult = $stmt->get_result();
$stmt->close();

if ($driverEmailResult->num_rows > 0) {
    $driverEmail = $driverEmailResult->fetch_assoc()['email'];
    
    // Email content
    $subject = "Payment Confirmation for Fine ID: " . $fine_id;
    $message = "
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; }
                .container { width: 80%; margin: auto; padding: 20px; border: 1px solid #ddd; border-radius: 5px; }
                h2 { color: green; }
                .details { font-size: 14px; line-height: 1.6; }
            </style>
        </head>
        <body>
            <div class='container'>
                <h2>Payment Successful</h2>
                <p class='details'>Your fine payment (Fine ID: <strong>$fine_id</strong>) has been successfully processed.</p>
                <p class='details'><strong>Amount:</strong> Rs. " . number_format($payhere_amount, 2) . "</p>
                <p class='details'>Thank you for your payment!</p>
            </div>
        </body>
        </html>
    ";

    // Send email
    require "../../../PHPMailer/mail.php";
    send_mail($driverEmail, $subject, $message);
}


$conn->close();

