<?php

$pageConfig = [
    'title' => 'Fine Details',
    'styles' => ["../../dashboard.css"],
    'scripts' => ["../../dashboard.js"],
    'authRequired' => true
];

require_once "../../../db/connect.php";
include_once "../../../includes/header.php";

if ($_SESSION['user']['role'] !== 'driver') {
    die("unauthorized user!");
}

$driver_id = $_SESSION['user']['id'] ?? null;

// Fine ID Numeric Validation
$fine_id = isset($_GET['fine_id']) ? intval($_GET['fine_id']) : 0;
if ($fine_id <= 0) {
    die("Invalid fine ID.");
}


if (!$driver_id) {
    die("Unathorized access.");
}

$sql = "
    SELECT f.id AS fine_id, f.police_id, f.driver_id, f.license_plate_number, f.issued_date, 
    f.issued_time, f.offence_type, f.nature_of_offence, f.offence, f.fine_status FROM fines AS
     f INNER JOIN drivers AS d ON f.driver_id = d.id WHERE f.id = ? AND d.id = ?;
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("is", $fine_id, $driver_id);

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
    <?php include_once "../../includes/navbar.php" ?>
    <div class="dashboard-layout">
        <?php include_once "../../includes/sidebar.php" ?>
        <div class="content">
            <div class="container large">
                <h1>Fine Details</h1>
                <div class="data-line">
                    <span>Police ID:</span>
                    <p><?= htmlspecialchars($fine['police_id']) ?></p>
                </div>
                <div class="data-line">
                    <span>Driver ID:</span>
                    <p><?= htmlspecialchars($fine['driver_id']) ?></p>
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
                    <span>Fine Status:</span>
                    <p><?= htmlspecialchars($fine['fine_status']) ?></p>
                </div>
                <div class="wrapper">
                    <!-- <a href="index.php" class="btn" style="margin-right: 10px;margin-top:20px">Back to Fines</a> -->
                    <button class="btn" style="margin-right: 10px;margin-top:20px" id="reportFineButton">Report</button>
                    <!-- <a href="" class="btn" style="margin-right: 10px;margin-top:20px" id="reportFineButton">Report</a> -->
                    <a href="/digifine/dashboard/driver/my-fines/pay-fine/index.php?fine_id=<?=htmlspecialchars($fine['fine_id']) ?>" class="btn" style="margin-right: 10px;margin-top:20px" id="payFinea">Pay</a>
                </div>
                <!-- Hidden report form -->
                <form action="report-fine-process.php" method="post" id="reportFineForm"
                    style="display: none; margin-top: 20px;">
                    <div class="field">
                        <input type="file" style="margin-bottom: 10px;">
                        <label for="reported_description">Reason for Reporting:</label>
                        <textarea type="text" class="input" name="reported_description" id="reported_description"
                            required></textarea>
                        <button class="btn" style="margin-top: 10px; margin-right: 10px">Submit</button>
                        <input type="hidden" name="fine_id" value="<?= htmlspecialchars($fine['fine_id']) ?>">
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>

<script>
    // Toggle visibility for the report form and hide the Pay button
    document.getElementById('reportFineButton').addEventListener('click', function () {
        document.getElementById('reportFineForm').style.display = 'flex'; // Show the form
        this.style.display = 'none'; // Hide the Report button
        document.getElementById('payFineButton').style.display = 'none'; // Hide the Pay button
    });
</script>

<?php include_once "../../../includes/footer.php" ?>