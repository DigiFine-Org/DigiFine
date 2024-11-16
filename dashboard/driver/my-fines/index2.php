<?php
include('get-ticket-data.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Tickets</title>
    <!-- <link rel="stylesheet" href="../assets/css/styles.css"> -->
    <link rel="stylesheet" href="../assets/css/ticket.css">

</head>


<body>

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

</body>

</html>
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
