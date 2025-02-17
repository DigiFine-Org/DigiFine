<?php

$pageConfig = [
    'title' => 'Driver Fines',
    'styles' => ["../../dashboard.css", "./my-fines.css"],
    'scripts' => ["../../dashboard.js"],
    'authRequired' => true
];

session_start();
include_once "../../../includes/header.php";
require_once "../../../db/connect.php";

// Authorization check
if ($_SESSION['user']['role'] !== 'driver') {
    die("Unauthorized user!");
}

$driver_id = $_SESSION['user']['id'];

// Fetch fines
$fines = [];
$stmt = $conn->prepare("
    SELECT f.id, f.police_id, f.driver_id, f.license_plate_number, f.issued_date,
           f.issued_time, f.offence_type, f.nature_of_offence, f.offence, f.fine_status
    FROM fines AS f
    INNER JOIN drivers AS d ON f.driver_id = d.id
    WHERE d.id = ? AND is_discarded = 0 AND (fine_status = 'pending' OR fine_status = 'overdue')
    ");

if (!$stmt) {
    die("Error preparing query: " . $conn->error);
}

$stmt->bind_param("s", $driver_id);

if (!$stmt->execute()) {
    die("Error executing query: " . $stmt->error);
}

$result = $stmt->get_result();
$fines = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<main>
    <?php include_once "../../includes/navbar.php"; ?>
    <div class="dashboard-layout">
        <?php include_once "../../includes/sidebar.php"; ?>
        <div class="content">
            <h1 style="margin-bottom: 10px;">Fines to be Paid</h1>
            <div class="home-grid">
                <?php foreach ($fines as $fine): ?>
                    <div class="ticket <?= $fine['fine_status'] === 'overdue' ? 'danger' : '' ?>">
                        <span class="id">Ticket: 3456<?= htmlspecialchars($fine['id']) ?></span>
                        <div class="data-line">
                            <div class="label">Offence Type:</div>
                            <p><?= htmlspecialchars($fine['offence_type']) ?></p>
                        </div>
                        <div class="data-line">
                            <div class="label">Offence:</div>
                            <p><?= htmlspecialchars($fine['offence']) ?></p>
                        </div>
                        <div class="data-line">
                            <div class="label">Date:</div>
                            <p><?= htmlspecialchars($fine['issued_date']) ?></p>
                        </div>
                        <div class="bottom-bar">
                            <div class="actions">
                                <?php if ($fine['offence_type'] !== 'court'): ?>
                                    <a href="view-fine-details.php?fine_id=<?= htmlspecialchars($fine['id']) ?>" class="btn">View</a>
                                    <a href="/digifine/dashboard/driver/my-fines/pay-fine/index.php?fine_id=<?= htmlspecialchars($fine['id']) ?>" class="btn">Pay</a>
                                <?php else: ?>
                                    <a href="view-fine-details.php?fine_id=<?= htmlspecialchars($fine['id']) ?>" class="btn">View</a>
                                <?php endif; ?>
                            </div>
                            <div class="status-list">
                                <span class="status <?= $fine['fine_status'] === 'overdue' ? 'danger' : '' ?>">
                                    <?= htmlspecialchars($fine['fine_status']) ?>
                                </span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</main>

<?php include_once "../../../includes/footer.php"; ?>
