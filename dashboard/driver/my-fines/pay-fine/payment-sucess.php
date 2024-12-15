<?php
session_start();
$pageConfig = [
    'title' => 'Payment Success',
    'styles' => ["../../../dashboard.css"],
    'authRequired' => true
];

require_once "../../../../db/connect.php";
include_once "../../../../includes/header.php";

// Display success message
?>
<main>
    <div class="dashboard-layout">
        <?php include_once "../../../includes/navbar.php"; ?>
        <div class="content">
            <div class="container large">
                <h1>Payment Successful</h1>
                <p>Your payment was successfully processed. Thank you for paying the fine.</p>
                <a href="/digifine/dashboard/driver/my-fines/" class="btn">Back to Fines</a>
            </div>
        </div>
    </div>
</main>
<?php include_once "../../../../includes/footer.php"; ?>
