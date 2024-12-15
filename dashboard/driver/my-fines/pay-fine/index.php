<?php

$pageConfig = [
    'title' => 'Pay Fine',
    'styles' => ["../../../dashboard.css", "./pay-fine.css"],
    'scripts' => ["../../../dashboard.js"],
    'authRequired' => true
];

require_once "../../../../db/connect.php";
include_once "../../../../includes/header.php";

if ($_SESSION['user']['role'] !== 'driver') {
    die("Unauthorized user!");
}

$driver_id = $_SESSION['user']['id'] ?? null;

// Fine ID Numeric Validation
$fine_id = isset($_GET['fine_id']) ? intval($_GET['fine_id']) : 0;

if ($fine_id <= 0) {
    die("Invalid fine ID.");
}

if (!$driver_id) {
    die("Unauthorized access.");
}

// Fetch fine details
$sql = "
    SELECT f.id AS fine_id, f.police_id, f.driver_id, f.license_plate_number, f.issued_date, 
    f.issued_time, f.offence_type, f.nature_of_offence, f.offence, f.fine_status, f.fine_amount 
    FROM fines AS f
    INNER JOIN drivers AS d ON f.driver_id = d.id 
    WHERE f.id = ? AND d.id = ? AND is_discarded = 0;
";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Query preparation failed: " . $conn->error);
}
$stmt->bind_param("ii", $fine_id, $driver_id);

if (!$stmt->execute()) {
    die("Error executing query: " . $stmt->error);
}

$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("No fine found or unauthorized access.");
}

$fine = $result->fetch_assoc();
$stmt->close();
$conn->close();

?>

<main>
    <?php include_once "../../../includes/navbar.php" ?>
    <div class="dashboard-layout">
        <?php include_once "../../../includes/sidebar.php" ?>
        <div class="content">
            <form class="container large" method="post" action="https://sandbox.payhere.lk/pay/checkout">
                <h1>Pay Fine</h1>
                <div class="data-line">
                    <span>Fine ID:</span>
                    <p><?= htmlspecialchars($fine['fine_id']) ?></p>
                </div>
                <div class="data-line">
                    <span>License Plate Number:</span>
                    <p><?= htmlspecialchars($fine['license_plate_number']) ?></p>
                </div>
                <div class="data-line">
                    <span>Issued Date:</span>
                    <p><?= htmlspecialchars($fine['issued_date']) ?></p>
                </div>
                <div class="data-line">
                    <span>Issued Time:</span>
                    <p><?= htmlspecialchars($fine['issued_time']) ?></p>
                </div>
                <div class="data-line">
                    <span>Offence Type:</span>
                    <p><?= htmlspecialchars($fine['offence_type']) ?></p>
                </div>
                <div class="data-line">
                    <span>Offence:</span>
                    <p><?= htmlspecialchars($fine['offence']) ?></p>
                </div>
                <div class="data-line">
                    <span>Nature of Offence:</span>
                    <p><?= htmlspecialchars($fine['nature_of_offence']) ?></p>
                </div>
                <div class="data-line">
                    <span>Fine Amount:</span>
                    <p>Rs. <?= number_format($fine['fine_amount'], 2) ?></p>
                </div>
                <div class="wrapper">
                    <button id="payFineButton" class="btn">Pay Now</button>
                </div>
                <input type="hidden" name="sandbox" value="true">
                <input type="hidden" name="merchant_id" value="1228631">
                <input type="hidden" name="return_url"
                    value="http://localhost/digifine/dashboard/driver/my-fines/pay-fine/payment-success.php">
                <input type="hidden" name="cancel_url"
                    value="http://localhost/digifine/dashboard/driver/my-fines/pay-fine/payment-cancel.php">
                <input type="hidden" name="notify_url"
                    value="http://localhost/digifine/dashboard/driver/my-fines/pay-fine/payment-notify.php">
                <?php
                $merchant_id = "1228631";
                $order_id = htmlspecialchars($fine['fine_id']);
                $amount = htmlspecialchars($fine['fine_amount']);
                $currency = "LKR";
                $merchant_secret = "MjE0ODIwNjgzNTg4ODgxMzI2MDI4MzA3MDg1NjAzODU4NTA1NTE1";
                $hash = strtoupper(
                    md5(
                        $merchant_id .
                        $order_id .
                        number_format($amount, 2, '.', '') .
                        $currency .
                        strtoupper(md5($merchant_secret))
                    )
                );
                ?>

                <input type="hidden" name="first_name" value="<?= htmlspecialchars($_SESSION['user']['fname']) ?>">
                <input type="hidden" name="last_name" value="<?= htmlspecialchars($_SESSION['user']['lname']) ?>">
                <input type="hidden" name="email" value="<?= htmlspecialchars($_SESSION['user']['email']) ?>">
                <input type="hidden" name="phone" value="<?= htmlspecialchars($_SESSION['user']['phone_no']) ?>">
                <input type="hidden" name="address" value="<?= "Haputhale, Sri Lanka" ?>">
                <input type="hidden" name="city" value="<?= "Colombo" ?>">
                <input type="hidden" name="country" value="Sri Lanka">
                <input type="hidden" name="order_id" value="<?= $order_id ?>">
                <input type="hidden" name="items" value="Traffic Fine Payment">
                <input type="hidden" name="currency" value="LKR">
                <input type="hidden" name="amount" value="<?= $amount ?>">
                <input type="hidden" name="hash" value="<?= $hash ?>">
            </form>
        </div>
    </div>
</main>

<!-- PayHere Payment Integration -->
<!-- <script src="https://www.payhere.lk/lib/payhere.js"></script>
<script>
    console.log("asdsad");
    document.getElementById('payFineButton').addEventListener('click', function () {
        console.log("asdsad");
        return;
        const payment = {
            "sandbox": true, // Set to false for production
            "merchant_id": "1228631", // Replace with your PayHere Merchant ID
            "return_url": "http://localhost/digifine/dashboard/driver/my-fines/pay-fine/payment-success.php",
            "cancel_url": "http://localhost/digifine/dashboard/driver/my-fines/pay-fine/payment-cancel.php",
            "notify_url": "http://localhost/digifine/dashboard/driver/my-fines/pay-fine/payment-notify.php",
            "order_id": "FINE-<?= htmlspecialchars($fine['fine_id']) ?>",
            "items": "Traffic Fine Payment",
            "amount": "<?= htmlspecialchars($fine['fine_amount']) ?>", // Avoid formatting numbers here
            "currency": "LKR",
            "first_name": "<?= htmlspecialchars($_SESSION['user']['fname']) ?>",
            "last_name": "<?= htmlspecialchars($_SESSION['user']['lname']) ?>",
            "email": "<?= htmlspecialchars($_SESSION['user']['email']) ?>",
            "phone": "<?= htmlspecialchars($_SESSION['user']['phone_no']) ?>",
            "address": "<?= htmlspecialchars($_SESSION['user']['address']) ?>",
            "city": "<?= htmlspecialchars($_SESSION['user']['city']) ?>"
        };

        console.log({ payment });
        payhere.startPayment(payment);
    });
</script> -->

<?php include_once "../../../../includes/footer.php"; ?>