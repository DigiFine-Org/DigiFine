<?php
$pageConfig = [
    'title' => 'Driver Fines',
    'styles' => ["../../dashboard.css"],
    'scripts' => ["../../dashboard.js"],
    'authRequired' => true
];

include_once "../../../includes/header.php";
include('./backend/get-ticket-data.php');
?>

<main>
    <?php include_once "../../includes/navbar.php" ?>
    <div class="dashboard-layout">
        <?php include_once "../../includes/sidebar.php" ?>
        <div class="content">
            <div class="tickets-container">
                <?php foreach ($tickets as $ticket): ?>
                    <div class="ticket">
                        <h3>Ticket ID : <?= $ticket['fine_id']; ?> <br>
                            <? $ticket = $description; ?></h3>
                        <p>Driver ID : <?= $ticket['license_number']; ?></p>
                        <p>Full Name : <?= $ticket['full_name']; ?></p>
                        <p>Issued Officer : <?= $ticket['officer_id']; ?></p>
                        <p>Date and Time : <?= $ticket['issued_on']; ?></p>
                        <p>place : <?= $ticket['issued_place']; ?></p>
                        <p>Violation Category : <?= $ticket['category_name']; ?></p>
                        <p>Violation : <?= $ticket['violation_name']; ?></p>
                        <p>Description : <?= $ticket['description']; ?></p>
                        <p>Status : <?= $ticket['payment_status']; ?></p>
                        <p class="fine-amount">Fine : Rs.<?= $ticket['price']; ?></p>
                        <button class="pay-btn">Pay Now</button>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</main>

<?php include_once "../../../includes/footer.php" ?>

<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>