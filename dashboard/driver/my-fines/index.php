<?php
$pageConfig = [
    'title' => 'Driver Fines',
    'styles' => ["../../dashboard.css", "ticket.css"],
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
                <?php if (empty($tickets)): ?>
                    <div class="container">
                        <h1>Issue Traffic Fine</h1>
                        <h2>No tickets found.</h2>
                    </div>
                <?php else: ?>
                    <?php foreach ($tickets as $ticket): ?>
                        <div class="ticket">
                            <h3>Ticket ID : <?= $ticket['id']; ?> <br>
                                Description: <?= $ticket['description']; ?></h3>
                            <p>Driver ID : <?= $ticket['driver_id']; ?></p>
                            <p>Full Name : <?= $ticket['full_name']; ?></p>
                            <p>Issued Officer : <?= $ticket['officer_id']; ?></p>
                            <p>Date and Time : <?= $ticket['issued_on']; ?></p>
                            <p>Place : <?= $ticket['issued_place']; ?></p>
                            <!--<p>Violation Category : <?= $ticket['category_name']; ?></p>-->
                            <p>Violation : <?= $ticket['offence_description_english']; ?></p>
                            <p>Status : <?= $ticket['payment_status']; ?></p>
                            <p class="fine-amount">Fine : Rs.<?= $ticket['fine_amount']; ?></p>
                            <button class="pay-btn">Pay Now</button>
                            <button class="pay-btn" onclick="window.location.href='./backend/report-fine.php?fine_id=<?= $ticket['id']; ?>'">Report Fine</button>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>

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

<?php
if (isset($_GET['success'])) {
    $successMessage = htmlspecialchars($_GET['success']); 
    echo "<div class='success-message'>$successMessage</div>";
}
?>
