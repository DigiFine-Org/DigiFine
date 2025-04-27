<?php
$pageConfig = [
    'title' => 'Payment Details',
    'styles' => ["../../dashboard.css"],
    'scripts' => ["../../dashboard.js"],
    'authRequired' => true
];

include_once "../../../includes/header.php";
require_once "../../../db/connect.php";

if ($_SESSION['user']['role'] !== 'driver') {
    die("unathorized author!");
}

$driver_id = $_SESSION['user']['id'];
$fine_id = isset($_GET['fine_id']) ? intval($_GET['fine_id']) : 0;

if (!$fine_id || !$driver_id) {
    die("Invalid request");
}

// fetch fine details
$sql = "SELECT * FROM fines WHERE id = ? AND driver_id = ? AND fine_status = 'paid'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $fine_id, $driver_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("No paid fine found or unathorized access.");
}

$fine = $result->fetch_assoc();

?>


<main>
    <?php include_once "../../includes/navbar.php" ?>
    <div class="dashboard-layout">
        <?php include_once "../../includes/sidebar.php" ?>
        <div class="content">
            <div class="container">
                <button onclick="history.back()" class="back-btn" style="position: absolute; top: 7px; right: 8px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        viewBox="0 0 16 16">
                        <path fill-rule="evenodd"
                            d="M15 8a.5.5 0 0 1-.5.5H3.707l3.147 3.146a.5.5 0 0 1-.708.708l-4-4a.5.5 0 0 1 0-.708l4-4a.5.5 0 1 1 .708.708L3.707 7.5H14.5a.5.5 0 0 1 .5.5z" />
                    </svg>
                </button>
                <h1>Payment Details</h1>
                <div class="data-line">
                    <span>Fine ID:</span>
                    <p><?= $fine['id'] ?></p>
                </div>
                <div class="data-line"><span>License Plate:</span>
                    <p><?= $fine['license_plate_number'] ?></p>
                </div>
                <div class="data-line"><span>Offence:</span>
                    <p><?= $fine['offence'] ?></p>
                </div>
                <div class="data-line"><span>Nature of Offence</span>
                    <p><?= $fine['nature_of_offence'] ?></p>
                </div>
                <div class="data-line"><span>Issued date:</span>
                    <p><?= $fine['issued_date'] ?></p>
                </div>
                <div class="data-line"><span>Issued Time:</span>
                    <p><?= $fine['issued_time'] ?></p>
                </div>
                <div class="data-line"><span>Amount Paid:</span>
                    <p><?= number_format($fine['fine_amount'], 2) ?></p>
                </div>
                <div class="data-line"><span>Paid At:</span>
                    <p><?= $fine['paid_at'] ?></p>
                </div>

                <a href="download-slip.php?fine_id=<?= $fine['id'] ?>" class="btn">Download Payment Slip</a>

            </div>
        </div>
</main>

<?php include_once "../../../includes/footer.php" ?>