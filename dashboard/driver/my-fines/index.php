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
           f.issued_time, f.offence_type, f.nature_of_offence, f.offence, f.fine_status,f.fine_amount, o.description_english  AS offence_description
    FROM fines AS f
    INNER JOIN drivers AS d ON f.driver_id = d.id
    LEFT JOIN offences AS o ON f.offence = o.offence_number
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
            <button onclick="history.back()" class="back-btn" style="position: absolute; top: 7px; right: 8px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                    <path fill-rule="evenodd"
                        d="M15 8a.5.5 0 0 1-.5.5H3.707l3.147 3.146a.5.5 0 0 1-.708.708l-4-4a.5.5 0 0 1 0-.708l4-4a.5.5 0 1 1 .708.708L3.707 7.5H14.5a.5.5 0 0 1 .5.5z" />
                </svg>
            </button>
            <h1 style="margin-bottom: 10px;">Fines to be Paid</h1>
            <div class="home-grid">
                <?php if (count($fines) > 0): ?>
                    <?php foreach ($fines as $fine): ?>
                        <div class="ticket <?= $fine['fine_status'] === 'overdue' ? 'danger' : '' ?>">
                            <span class="id">Ticket: 3456<?= htmlspecialchars($fine['id']) ?></span>
                            <div class="data-line">
                                <div class="label">Offence Type:</div>
                                <p><?= htmlspecialchars($fine['offence_type']) ?></p>
                            </div>
                            <div class="data-line">
                                <div class="label">Date:</div>
                                <p><?= htmlspecialchars($fine['issued_date']) ?></p>
                            </div>
                            <?php if ($fine['offence_type'] !== 'court'): ?>
                                <div class="data-line">
                                    <div class="label">Offence:</div>
                                    <p><?= htmlspecialchars($fine['offence_description']) ?></p>
                                </div>
                                <div class="data-line">
                                    <div class="label">Fine Amount:</div>
                                    <p><?= htmlspecialchars($fine['fine_amount']) ?></p>
                                </div>
                            <?php else: ?>
                                <div class="data-line">
                                    <div class="label">Nature of Offence:</div>
                                    <p><?= htmlspecialchars($fine['nature_of_offence']) ?></p>
                                </div>

                            <?php endif; ?>


                            <div class="bottom-bar">
                                <div class="actions">
                                    <?php if ($fine['offence_type'] !== 'court'): ?>
                                        <a href="view-fine-details.php?fine_id=<?= htmlspecialchars($fine['id']) ?>"
                                            class="btn">View</a>
                                        <!-- <a href="/digifine/dashboard/driver/my-fines/pay-fine/index.php?fine_id=<?= htmlspecialchars($fine['id']) ?>"
                                            class="btn">Pay</a> -->

                                    <?php else: ?>
                                        <a href="view-fine-details.php?fine_id=<?= htmlspecialchars($fine['id']) ?>"
                                            class="btn">View</a>
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
                <?php else: ?>
                    <div class="no-fines-container">
                        <img src="../../../assets/no-fines.png" alt="No fines" class="no-fines-image">
                        <h2>No Fines Found</h2>
                        <p>You don't have any outstanding fines to pay. Keep up the good driving!</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>

<?php include_once "../../../includes/footer.php"; ?>