<?php
$pageConfig = [
    'title' => 'Fine Details',
    'styles' => ["../../dashboard.css"],
    'scripts' => ["../../dashboard.js"],
    'authRequired' => true
];

include_once "../../../includes/header.php";
require_once "../../../db/connect.php";

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid or missing fine ID.");
}

$id = intval($_GET['id']);
$sql = "SELECT id, police_id, driver_id, license_plate_number, issued_date, issued_time,expire_date, offence_type, nature_of_offence, offence, fine_status, fine_amount FROM fines WHERE id = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Query preparation failed: " . $conn->error);
}

$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("No fine found with the given ID.");
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
                    <span>Expiry Date:</span>
                    <p><?= htmlspecialchars($fine['expire_date']) ?></p>
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
                <div class="data-line">
                    <span>Fine Amount:</span>
                    <p><?= htmlspecialchars($fine['fine_amount']) ?></p>
                </div>
                <a href="index.php" class="btn" style="margin-top: 20px;">Back to Fines</a>
            </div>
        </div>
    </div>
</main>

<?php include_once "../../../includes/footer.php" ?>