<?php

$pageConfig = [
    'title' => 'Fine Details',
    'styles' => ["../../dashboard.css", "./my-fines.css"],
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

// Fetch fine details - added is_reported to the query
$sql = "
    SELECT f.id AS fine_id, f.police_id, f.driver_id, f.license_plate_number, f.issued_date, 
    f.issued_time, f.offence_type, f.nature_of_offence, f.offence, f.fine_status, f.is_reported,f.is_solved, f.fine_amount
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
            <div class="container">
                <button onclick="history.back()" class="back-btn" style="position: absolute; top: 7px; right: 8px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        viewBox="0 0 16 16">
                        <path fill-rule="evenodd"
                            d="M15 8a.5.5 0 0 1-.5.5H3.707l3.147 3.146a.5.5 0 0 1-.708.708l-4-4a.5.5 0 0 1 0-.708l4-4a.5.5 0 1 1 .708.708L3.707 7.5H14.5a.5.5 0 0 1 .5.5z" />
                    </svg>
                </button>
                <h1>Fine Details</h1>
                <div class="data-line">
                    <span>Fine ID:</span>
                    <p>3456<?= htmlspecialchars($fine['fine_id']); ?></p>
                </div>
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
                    <span>Fine Amount:</span>
                    <p><?= htmlspecialchars($fine['fine_amount']); ?></p>
                </div>
                <div class="data-line">
                    <span>Fine Status:</span>
                    <p><?= htmlspecialchars($fine['fine_status']); ?></p>
                </div>
                <div class="wrapper">
                    <?php if ($fine['offence_type'] === 'court'): ?>
                        <p class="court-violation">This is a court violation. Reporting or paying is not allowed online.</p>
                    <?php else: ?>
                        <?php if ($fine['fine_status'] === 'overdue'): ?>
                            <p class="reported-message1">This fine is overdue. Reporting or paying is not allowed.</p>
                        <?php elseif ($fine['fine_status'] === 'paid'): ?>
                            <p class="reported-message2">This fine has already been paid. Further actions are not allowed.</p>
                        <?php else: ?>
                            <?php if ($fine['is_reported'] == 0): ?>
                                <button class="btn" id="reportFineButton" style="margin-right: 10px;">Report</button>
                            <?php endif; ?>
                            <?php if ($fine['is_reported'] == 0): ?>
                                <a href="/digifine/dashboard/driver/my-fines/pay-fine/index.php?fine_id=<?= htmlspecialchars($fine['fine_id']); ?>"
                                    class="btn" id="payFineButton">Pay</a>
                            <?php endif; ?>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>

                <?php if ($fine['is_reported'] == 1 && $fine['is_solved'] == 0): ?>
                    <div style="margin-top: 15px;">
                        <p class="reported-message1">This fine has already been reported.</p>
                    </div>
                <?php elseif ($fine['is_solved'] == 1 && $fine['is_reported'] == 1): ?>
                    <div style="margin-top: 15px;">
                        <p class="reported-message1">This fine has already been reported</p>
                        <p class="reported-message2">This fine has been marked as fair.</p>
                    </div>
                    <div class="wrapper">
                        <a href="/digifine/dashboard/driver/my-fines/pay-fine/index.php?fine_id=<?= htmlspecialchars($fine['fine_id']); ?>"
                            class="btn" id="payFineButton">Pay</a>
                    </div>
                <?php endif; ?>

                <?php if ($fine['offence_type'] !== 'court' && $fine['is_reported'] == 0): ?>
                    <form action="report-fine-process.php" method="post" id="reportFineForm" enctype="multipart/form-data"
                        style="display: none; margin-top: 20px; flex-direction: column;">
                        <div class="field">
                            <label for="evidence">Upload Evidence:</label>
                            <input type="file" name="evidence" id="evidence" accept=".jpg,.jpeg,.png,.pdf" required>
                        </div>
                        <div class="field">
                            <label for="reported_description">Reason for Reporting:</label>
                            <textarea name="reported_description" id="reported_description" class="input"
                                required></textarea>
                            <span style="margin-bottom:10px;"></span>
                        </div>
                        <button class="btn" style="margin-top: 12px; margin-right: 10px">Submit</button>
                        <input type="hidden" name="fine_id" value="<?= htmlspecialchars($fine['fine_id']) ?>">
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>

<script>
    // Toggle visibility for the report form and hide other buttons
    const reportButton = document.getElementById('reportFineButton');
    if (reportButton) {
        reportButton.addEventListener('click', function () {
            const reportForm = document.getElementById('reportFineForm');
            const payFineButton = document.getElementById('payFineButton');

            reportForm.style.display = 'flex'; // Show the report form
            this.style.display = 'none'; // Hide the Report button
            if (payFineButton) payFineButton.style.display = 'none'; // Hide the Pay button
        });
    }
</script>

<?php include_once "../../../includes/footer.php"; ?>