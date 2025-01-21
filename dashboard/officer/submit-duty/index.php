<?php

$pageConfig = [
    'title' => 'Submit Duty',
    'styles' => ["../../dashboard.css", "./my-duties.css"],
    'scripts' => ["../../dashboard.js"],
    'authRequired' => true
];

session_start();
include_once "../../../includes/header.php";
require_once "../../../db/connect.php";

if ($_SESSION['user']['role'] !== 'officer') {
    die("Unauthorized user!");
}

$officer_id = $_SESSION['user']['id'];

$duties = [];
$stmt = $conn->prepare("
    SELECT ad.id, ad.duty, ad.notes, ad.assigned_by, ad.assigned_at,ad.duty_date
    FROM assigned_duties AS ad
    WHERE ad.police_id = ? AND ad.submitted = 0
");

if (!$stmt) {
    die("Error preparing query!" . $conn->error);
}

$stmt->bind_param("i", $officer_id);

if (!$stmt->execute()) {
    die("Error executing query" . $stmt->error);
}

$result = $stmt->get_result();
$duties = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<main>
    <?php include_once "../../includes/navbar.php"; ?>
    <div class="dashboard-layout">
        <?php include_once "../../includes/sidebar.php"; ?>
        <div class="content">
            <div class="home-grid">
                <?php foreach ($duties as $duty): ?>
                    <div class="ticket">
                        <span class="id">Duty ID: <?= htmlspecialchars($duty['id']) ?></span>
                        <div class="data-line">
                            <div class="label">Duty:</div>
                            <p><?= htmlspecialchars($duty['duty']) ?></p>
                        </div>
                        <div class="data-line">
                            <div class="label">Notes:</div>
                            <p><?= htmlspecialchars($duty['notes'] ?? "N/A") ?></p>
                        </div>
                        <div class="data-line">
                            <div class="label">Assigned By:</div>
                            <p><?= htmlspecialchars($duty['assigned_by']) ?></p>
                        </div>
                        <div class="data-line">
                            <div class="label">Assigned At:</div>
                            <p><?= htmlspecialchars($duty['assigned_at']) ?></p>
                        </div>
                        <div class="bottom-bar">
                            <div class="actions">
                                <a href="submit-duty.php?id=<?= htmlspecialchars($duty['id']) ?>" class="btn">Submit Duty</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
                
                <?php if (empty($duties)): ?>
                    <p>No duties assigned to you yet.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>

<?php include_once "../../../includes/footer.php"; ?>
 