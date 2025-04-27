<?php
session_start();
$pageConfig = [
    'title' => 'Payment Canceled',
    'styles' => ["../../../dashboard.css"],
    'authRequired' => true
];

include_once "../../../../includes/header.php";
?>
<main>
    <?php include_once "../../../includes/navbar.php"; ?>
    <div class="dashboard-layout">
        <?php include_once "../../../includes/sidebar.php" ?>
        <div class="content">
            <div class="container">
                <h1>Payment Canceled</h1>
                <p>Your payment was canceled. Please try again.</p>
                <a href="/digifine/dashboard/driver/my-fines/" class="btn">Back to Fines</a>
            </div>
        </div>
    </div>
</main>
<?php include_once "../../../../includes/footer.php"; ?>