<?php
require_once "../../../../db/connect.php";


file_put_contents("ipn_debug.log", "[" . date("Y-m-d H:i:s") . "] " . json_encode($_POST, JSON_PRETTY_PRINT) . PHP_EOL, FILE_APPEND);



if (!isset($_POST['merchant_id'], $_POST['order_id'], $_POST['payhere_amount'], $_POST['payhere_currency'], $_POST['status_code'], $_POST['md5sig'])) {
    http_response_code(400);
    die("Missing required parameters.");
}


$merchant_id = htmlspecialchars($_POST['merchant_id']);
$order_id = htmlspecialchars($_POST['order_id']);
$payhere_amount = floatval($_POST['payhere_amount']);
$payhere_currency = htmlspecialchars($_POST['payhere_currency']);
$status_code = intval($_POST['status_code']);
$md5sig_received = htmlspecialchars($_POST['md5sig']);


$merchant_secret = 'MjE0ODIwNjgzNTg4ODgxMzI2MDI4MzA3MDg1NjAzODU4NTA1NTE1';


$md5sig_calculated = strtoupper(md5(
    $merchant_id . $order_id . $payhere_amount . $payhere_currency . $status_code . strtoupper(md5($merchant_secret))
));


if ($md5sig_received !== $md5sig_calculated) {
    http_response_code(400);
    die("Invalid IPN: Hash mismatch!");
}


if ($status_code == 2) {
    if (!preg_match('/^FINE-(\d+)$/', $order_id, $matches)) {
        http_response_code(400);
        die("Invalid order ID format.");
    }
    $fine_id = intval($matches[1]);

    $payer_first_name = htmlspecialchars($_POST['first_name'] ?? '');
    $payer_last_name = htmlspecialchars($_POST['last_name'] ?? '');
    $payer_email = htmlspecialchars($_POST['email'] ?? '');
    $payer_phone = htmlspecialchars($_POST['phone'] ?? '');
    $payer_address = htmlspecialchars($_POST['address'] ?? '');
    $payer_city = htmlspecialchars($_POST['city'] ?? '');
    $payer_country = htmlspecialchars($_POST['country'] ?? '');
    $hash = htmlspecialchars($_POST['hash'] ?? '');

    $conn->begin_transaction();

    try {
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

        $updateFinesSql = "UPDATE fines SET fine_status = 'paid' WHERE id = ?";
        $stmt = $conn->prepare($updateFinesSql);
        if (!$stmt) {
            throw new Exception("Database error: " . $conn->error);
        }
        $stmt->bind_param("i", $fine_id);
        $stmt->execute();
        $stmt->close();

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

        $stmt->bind_param(
            "issdsssssssss",
            $fine_id,
            $merchant_id,
            $order_id,
            $payhere_amount,
            $payhere_currency,
            $payer_first_name,
            $payer_last_name,
            $payer_email,
            $payer_phone,
            $payer_address,
            $payer_city,
            $payer_country,
            $hash
        );

        if (!$stmt->execute()) {
            file_put_contents("ipn_error.log", "[" . date("Y-m-d H:i:s") . "] SQL Execution Error: " . $stmt->error . "\n", FILE_APPEND);
            die("SQL Execution Error: " . $stmt->error);
        }

        $conn->commit();

        http_response_code(200);
        echo "Payment successfully recorded for Fine ID: " . $fine_id;



    } catch (Exception $e) {
        $conn->rollback();
        http_response_code(500);
        die("Failed to process payment: " . $e->getMessage());
    }
} else {
    file_put_contents("ipn_failed.log", "[" . date("Y-m-d H:i:s") . "] Payment failed for Order ID: $order_id, Status Code: $status_code.\n", FILE_APPEND);

    http_response_code(400);
    die("Payment failed or canceled!");
}


$driverEmailSql = "SELECT email FROM drivers WHERE id = (SELECT driver_id FROM fines WHERE id = ?)";
$stmt = $conn->prepare($driverEmailSql);
$stmt->bind_param("i", $fine_id);
$stmt->execute();
$driverEmailResult = $stmt->get_result();
$stmt->close();

if ($driverEmailResult->num_rows > 0) {
    $driverEmail = $driverEmailResult->fetch_assoc()['email'];

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

    require "../../../PHPMailer/mail.php";
    send_mail($driverEmail, $subject, $message);
}


$conn->close();

