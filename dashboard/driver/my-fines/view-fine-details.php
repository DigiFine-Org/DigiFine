<?php

$pageConfig = [
    'title' => 'Fine Details',
    'styles' => ["../../dashboard.css"],
    'scripts' => ["../../dashboard.js"],
    'authRequired' => true
];

require_once "../../../db/connect.php";
include_once "../../../includes/header.php";

// Check if the user is a driver
if ($_SESSION['user']['role'] !== 'driver') {
    die("Unauthorized user!");
}

$driver_id = $_SESSION['user']['id'] ?? null;

// Validate the fine ID
$fine_id = isset($_GET['fine_id']) ? intval($_GET['fine_id']) : 0;
if ($fine_id <= 0) {
    die("Invalid fine ID.");
}

if (!$driver_id) {
    die("Unauthorized access.");
}

if ($fine_id <= 0 || !$driver_id) {
    die("Invalid fine ID or unauthorized access.");
}

// Fetch fine details
$sql = "
    SELECT f.id AS fine_id, f.police_id, f.driver_id, f.license_plate_number, f.issued_date, 
    f.issued_time, f.offence_type, f.nature_of_offence, f.offence, f.fine_status 
    FROM fines AS f 
    INNER JOIN drivers AS d ON f.driver_id = d.id 
    WHERE f.id = ? AND d.id = ?;
";

$stmt = $conn->prepare($sql);
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
    <?php include_once "../../includes/navbar.php"; ?>
    <div class="dashboard-layout">
        <?php include_once "../../includes/sidebar.php"; ?>
        <div class="content">
            <div class="container large">
                <h1>Fine Details</h1>
                <div class="data-line">
                    <span>Police ID:</span>
                    <p><?= htmlspecialchars($fine['police_id']); ?></p>
                </div>
                <div class="data-line">
                    <span>Driver ID:</span>
                    <p><?= htmlspecialchars($fine['driver_id']); ?></p>
                </div>
                <div class="data-line">
                    <span>License Plate Number:</span>
                    <p><?= htmlspecialchars($fine['license_plate_number']); ?></p>
                </div>
                <div class="data-line">
                    <span>Issued Date:</span>
                    <p><?= htmlspecialchars($fine['issued_date']); ?></p>
                </div>
                <div class="data-line">
                    <span>Issued Time:</span>
                    <p><?= htmlspecialchars($fine['issued_time']); ?></p>
                </div>
                <div class="data-line">
                    <span>Offence Type:</span>
                    <p><?= htmlspecialchars($fine['offence_type']); ?></p>
                </div>
                <div class="data-line">
                    <span>Offence:</span>
                    <p><?= htmlspecialchars($fine['offence']); ?></p>
                </div>
                <div class="data-line">
                    <span>Nature of Offence:</span>
                    <p><?= htmlspecialchars($fine['nature_of_offence']); ?></p>
                </div>
                <div class="data-line">
                    <span>Fine Status:</span>
                    <p><?= htmlspecialchars($fine['fine_status']); ?></p>
                </div>
                <div class="wrapper">
                    <?php if ($fine['offence_type'] === 'court'): ?>
                        <p class="court-violation">This is a court violation. Reporting or paying is not allowed online.</p>
                    <?php else: ?>
                        <button class="btn" id="reportFineButton" style="margin-right: 10px;">Report</button>
                        <a href="/digifine/dashboard/driver/my-fines/pay-fine/index.php?fine_id=<?= htmlspecialchars($fine['fine_id']); ?>"
                            class="btn" id="payFineButton">Pay</a>
                    <?php endif; ?>

                </div>
                <?php if ($fine['offence_type'] !== 'court'): ?>
                    <form action="report-fine-process.php" method="post" id="reportFineForm"
                        style="display: none; margin-top: 20px;">
                        <div class="field">
                            <input type="file" style="margin-bottom: 10px;">
                            <label for="reported_description">Reason for Reporting:</label>
                            <textarea name="reported_description" id="reported_description" class="input"
                                required></textarea>
                            <button class="btn" style="margin-top: 10px;">Submit</button>
                            <input type="hidden" name="fine_id" value="<?= htmlspecialchars($fine['fine_id']); ?>">
                        </div>
                    </form>
                <?php endif; ?>
                <!-- Hidden report form
                <form action="report-fine-process.php" method="post" id="reportFineForm" enctype="multipart/form-data"
                    style="display: none; margin-top: 20px; flex-direction: column;">
                    <div class="field">
                        <label for="evidence">Upload Evidence:</label>
                        <input type="file" name="evidence" id="evidence" accept="image/*,application/pdf" required>
                    </div>
                    <div class="field">
                        <label for="reported_description">Reason for Reporting:</label>
                        <textarea name="reported_description" id="reported_description" class="input"
                            required></textarea>
                        <span style="margin-bottom:10px;"></span>
                    </div>
                    <button class="btn" style="margin-top: 12px; margin-right: 10px">Submit</button>
                    <input type="hidden" name="fine_id" value="<?= htmlspecialchars($fine['fine_id']) ?>">
                </form> -->
            </div>
        </div>
    </div>
</main>

<script>
    // Toggle visibility for the report form and hide other buttons
    document.getElementById('reportFineButton').addEventListener('click', function () {
        const reportForm = document.getElementById('reportFineForm');
        const payFineButton = document.getElementById('payFineButton');

        reportForm.style.display = 'flex'; // Show the report form
        this.style.display = 'none'; // Hide the Report button
        if (payFineButton) payFineButton.style.display = 'none'; // Hide the Pay button
    });
</script>

<?php include_once "../../../includes/footer.php"; ?>
