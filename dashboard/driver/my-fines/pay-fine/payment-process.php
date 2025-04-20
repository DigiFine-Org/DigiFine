<?php

session_start();
include '../../../../db/connect.php';
$driver_id = $_SESSION['user']['id'];

$fine_id = htmlspecialchars($_POST['fine_id']);

$sql = "
    SELECT *
    FROM fines WHERE id=?;
";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Query preparation failed: " . $conn->error);
}
$stmt->bind_param("i", $fine_id);

if (!$stmt->execute()) {
    die("Error executing query: " . $stmt->error);
}

$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("No fine found or unauthorized access.");
}

$fine = $result->fetch_assoc();
$stmt->close();

$amount = $fine['fine_amount'];

$stmt = $conn->prepare("SELECT id AS fine_id, fine_amount, paid_at FROM fines WHERE driver_id = ? AND fine_status = 'paid' ORDER BY paid_at DESC");

if (!$stmt) {
    die("Query preparation failed: " . $conn->error);
}


$stmt->bind_param("i", $driver_id);

if (!$stmt->execute()) {
    die("Error executing query: " . $stmt->error);
}

?>

<form method='post' action='https://sandbox.payhere.lk/pay/checkout'>
    <input type="hidden" name="sandbox" value="true">
    <input type="hidden" name="merchant_id" value="1228631">
    <input type="hidden" name="return_url"
        value="http://localhost/digifine/dashboard/driver/my-fines/pay-fine/payment-success.php">
    <input type="hidden" name="cancel_url"
        value="http://localhost/digifine/dashboard/driver/my-fines/pay-fine/payment-cancel.php?id=<?= $fine_id ?>">
    <input type="hidden" name="notify_url"
        value="http://localhost/digifine/dashboard/driver/my-fines/pay-fine/payment-notify.php">
    <?php
    $merchant_id = "1228631";
    $currency = "LKR";
    $merchant_secret = "MjE0ODIwNjgzNTg4ODgxMzI2MDI4MzA3MDg1NjAzODU4NTA1NTE1";
    $hash = strtoupper(
        md5(
            $merchant_id .
            $fine_id .
            number_format($amount, 2, '.', '') .
            $currency .
            strtoupper(md5($merchant_secret))
        )
    );
    ?>

    <input type="hidden" name="first_name" value="<?php echo htmlspecialchars($_SESSION['user']['fname']) ?>">
    <input type="hidden" name="last_name" value="<?php echo htmlspecialchars($_SESSION['user']['lname']) ?>">
    <input type="hidden" name="email" value="<?php echo htmlspecialchars($_SESSION['user']['email']) ?>">
    <input type="hidden" name="phone" value="<?php echo htmlspecialchars($_SESSION['user']['phone_no']) ?>">
    <input type="hidden" name="address" value="<?php echo "Haputhale, Sri Lanka" ?>">
    <input type="hidden" name="city" value="<?php echo "Colombo" ?>">
    <input type="hidden" name="country" value="Sri Lanka">
    <input type="hidden" name="order_id" value="<?php echo $fine_id ?>">
    <input type="hidden" name="items" value="Traffic Fine Payment">
    <input type="hidden" name="currency" value="LKR">
    <input type="hidden" name="amount" value="<?php echo $amount ?>">
    <input type="hidden" name="hash" value="<?php echo $hash ?>">
</form>

<script>
    document.forms[0].submit();
</script>