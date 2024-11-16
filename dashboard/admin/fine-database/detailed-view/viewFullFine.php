<?php
require_once "../../../../db/connect.php";

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['fine_id'])) {
    // Fetch violation details based on the fine_id passed via GET
    $fine_id = (int) $_GET['fine_id'];

    // Prepare the SELECT query to fetch fine data
    $query = "SELECT 
        f.fine_id,
        f.officer_id, 
        f.driver_id, 
        d.full_name,
        d.d_address,
        d.license_valid_from,
        d.license_valid_to,
        d.competent_categories,
        f.vehicle_number,
        v.category_id,
        c.category_name,
        f.violation_id, 
        v.violation_name,
        f.description,
        v.price,
        f.issued_on, 
        f.issued_place,
        f.expire_date,  
        f.payment_status
    FROM fines f
    JOIN violations v ON f.violation_id = v.violation_id
    JOIN drivers d ON f.driver_id = d.id
    join violation_categories c on v.category_id = c.category_id
    WHERE f.fine_id = ?";

    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'i', $fine_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    // Check if tickets are found
    if (mysqli_num_rows($result) > 0) {
        $tickets = mysqli_fetch_all($result, MYSQLI_ASSOC);
        // For testing, let's output the result to check
        // echo "<pre>";
        // print_r($tickets);
        // echo "</pre>";
    } else {
        echo "No tickets found for fine ID: " . $fine_id;
    }

    // Close the statement and the connection
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}
?>

<!-- HTML form to display and update the violation -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Fine</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="../../../driver/my-fines/ticket.css">
</head>

<body>
    <h1>Traffic Fine - Detailed View</h1>
    <div class="tickets-container">
        <?php if (!empty($tickets)): ?>
            <?php $ticket = $tickets[0]; ?>
            <div class="ticket">
                <h3><?= htmlspecialchars($ticket['category_name']); ?> <br>
                    Ticket ID : <?= htmlspecialchars($ticket['fine_id']); ?>
                </h3>

                <p>Issued Officer ID : <?= htmlspecialchars($ticket['officer_id']); ?></p>

                <h4>Driver details</h4>
                <p>Driver ID : <?= htmlspecialchars($ticket['driver_id']); ?></p>
                <p>Full Name : <?= htmlspecialchars($ticket['full_name']); ?></p>
                <p>Address : <?= htmlspecialchars($ticket['d_address']); ?></p>

                <h4>License details</h4>
                <p>License Valid From : <?= htmlspecialchars($ticket['license_valid_from']); ?></p>
                <p>License Valid To : <?= htmlspecialchars($ticket['license_valid_to']); ?></p>
                <p>Competent Drive Categories : <?= htmlspecialchars($ticket['competent_categories']); ?></p>

                <h4>Vehicle details</h4>
                <p>Vehicle Number : <?= htmlspecialchars($ticket['vehicle_number']); ?></p>

                <h4>Violation details</h4>
                <p>Category ID : <?= htmlspecialchars($ticket['category_id']); ?></p>
                <p>Violation Category : <?= htmlspecialchars($ticket['category_name']); ?></p>
                <p>Violation ID : <?= htmlspecialchars($ticket['violation_id']); ?></p>
                <p>Violation Name : <?= htmlspecialchars($ticket['violation_name']); ?></p>
                <p>Description : <?= htmlspecialchars($ticket['description']); ?></p>

                <h4>Fine details</h4>
                <p>Price : Rs.<?= htmlspecialchars($ticket['price']); ?></p>
                <p>Issued On : <?= htmlspecialchars($ticket['issued_on']); ?></p>
                <p>Issued Place : <?= htmlspecialchars($ticket['issued_place']); ?></p>
                <p>Expire Date : <?= htmlspecialchars($ticket['expire_date']); ?></p>
                <p>Payment Status : <?= htmlspecialchars($ticket['payment_status']); ?></p>
            </div>
        <?php else: ?>
            <p>No tickets found for fine ID: <?= htmlspecialchars($fine_id); ?></p>
        <?php endif; ?>
    </div>


    <!-- <div>
        <h1>Edit Traffic Fine</h1>
        <form method="POST" action="store-fine.php">

            <label for="officer_id">Officer ID</label>
            <input type="text" id="officer_id" name="officer_id" id="officer_id" value="<?php echo $officer_id; ?>" readonly>


            <label for="driver_id">Driving License Number</label>
            <input type="text" name="driver_id" id="driver_id" value="<?php echo $driver_id; ?>" readonly>

            <br>
            <label for="full_name">Full Name</label>
            <input type="text" id="full_name" name="full_name" readonly>

            <label for="d_address">Address</label>
            <input type="text" id="d_address" name="d_address" readonly>

            <label for="license_valid_from">License Valid From</label>
            <input type="date" id="license_valid_from" name="license_valid_from" readonly>

            <label for="license_valid_to">License Valid To</label>
            <input type="date" id="license_valid_to" name="license_valid_to" readonly>

            <label for="competent_categories">Competent Drive Categories</label>
            <div id="competent_categories"></div>

            <label for="vehicle_number">Vehicle Number</label>
            <input type="text" id="vehicle_number" name="vehicle_number" readonly>
            <br>
     
            <label for="categoryDropdown">Nature of Offence (Category)</label>
            <input type="text" id="category" name="category" readonly>
            <br>


            <label for="violation_id">Violation ID</label>
            <input type="text" name="violation_id" id="violation_id" value="<?php echo $violation_id; ?>" readonly>
            <br>

            <label for="violation_name">Violation Name</label>
            <input type="text" name="violation_name" id="violation_name" value="<?php echo $violation_name; ?>" readonly>
            <br>

            <label for="description">Description</label>
            <input type="text" name="description" id="description" value="<?php echo $description; ?>" readonly>
            <br>
            <label for="price">Price</label>
            <input type="number" step="0.01" name="price" id="price" value="<?php echo $price; ?>" readonly>

            <label for="payment_status">Payment Status</label>
            <input type="text" name="payment_status" id="payment_status" value="<?php echo $payment_status; ?>" readonly>
  
            <button type="submit">Issue Fine</button>
        </form>
    </div>
    </div> -->

</body>

</html>