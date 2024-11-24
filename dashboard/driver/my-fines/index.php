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

if ($_SESSION['user']['role'] !== 'driver') {
    die("unauthorized user!");
}

$driver_id = $_SESSION['user']['id'];

$fines = [];
$stmt = $conn->prepare("
    SELECT f.id, f.police_id, f.driver_id, f.license_plate_number, f.issued_date,
    f.issued_time, f.offence_type, f.nature_of_offence, f.offence, f.fine_status
    FROM fines AS f
    INNER JOIN drivers AS d ON f.driver_id = d.id
    WHERE d.id = ?
    ");

if (!$stmt) {
    die("Error preparing query!" . $conn->error);
}

$stmt->bind_param("s", $driver_id);

if (!$stmt->execute()) {
    die("Query Error" . $stmt->error);
}


$result = $stmt->get_result();
$fines = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>


<main>
    <?php include_once "../../includes/navbar.php" ?>
    <div class="dashboard-layout">
        <?php include_once "../../includes/sidebar.php" ?>
        <div class="content">
            <div class="content">
                <div class="home-grid">
                    <?php foreach ($fines as $fine): ?>
                        <a href="" class="ticket <?= $fine['fine_status'] === 'overdue' ? 'danger' : '' ?>">
                            <span class="id">Ticket: <?= htmlspecialchars($fine['id']) ?></span>
                            <div class="data-line">
                                <div class="label">Offence Type:</div>
                                <p><?= htmlspecialchars($fine['offence_type']) ?></p>
                            </div>
                            <div class="data-line">
                                <div class="label">Offence:</div>
                                <p><?= htmlspecialchars($fine['offence']) ?></p>
                            </div>
                            <div class="data-line">
                                <div class="label">Date & Time:</div>
                                <p><?= htmlspecialchars($fine['issued_date'] . " " . $fine['issued_time']) ?></p>
                            </div>
                            <div class="bottom-bar">
                                <div class="actions">
                                    <button class="btn">View</button>
                                    <button class="btn">Pay</button>
                                </div>
                                <div class="status-list">
                                    <span class="status <?= $fine['fine_status'] === 'overdue' ? 'danger' : '' ?>">
                                        <?= htmlspecialchars($fine['fine_status']) ?>
                                    </span>
                                </div>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include_once "../../../includes/footer.php"; ?>