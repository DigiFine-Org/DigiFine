<?php
$pageConfig = [
    'title' => 'Driver Points Status',
    'styles' => ["../../dashboard.css", "./points-scheme.css"],
    'scripts' => ["../../dashboard.js"],
    'authRequired' => true
];

include_once "../../../includes/header.php";
require_once "../../../db/connect.php";

// Check if user is logged in as a driver
if ($_SESSION['user']['role'] !== 'driver') {
    die("unauthorized user!");
}

// Get the driver ID from the session
$driverId = $_SESSION['user']['id'] ?? null;

if (!$driverId) {
    die("Unauthorized access.");
}

// Fetch driver's details (Name, Points, and Created Date)
$sql = "SELECT fname, lname, points, created_at FROM drivers WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $driverId);

if (!$stmt->execute()) {
    die("Error fetching driver details: " . $stmt->error);
}

$result = $stmt->get_result();
if ($result->num_rows === 0) {
    die("Driver not found.");
}

$driver = $result->fetch_assoc();
$stmt->close();

// Check if points need to be reset (every 3 months since account creation)
function checkAndResetPoints($conn, $driverId, $createdAt)
{
    $createDate = new DateTime($createdAt);
    $currentDate = new DateTime();
    $interval = $createDate->diff($currentDate);

    // Convert the difference to months
    $monthsDiff = ($interval->y * 12) + $interval->m;

    // Check if it's time for a points reset (every 3 months)
    if ($monthsDiff > 0 && $monthsDiff % 3 == 0 && $interval->d == 0) {
        // Reset points to 20
        $resetSql = "UPDATE drivers SET points = 20 WHERE id = ?";
        $resetStmt = $conn->prepare($resetSql);
        $resetStmt->bind_param("s", $driverId);
        $resetResult = $resetStmt->execute();
        $resetStmt->close();

        // If reset was successful, return true
        if ($resetResult) {
            return true;
        }
    }

    return false;
}

// Check for points reset 
$pointsReset = checkAndResetPoints($conn, $driverId, $driver['created_at']);
if ($pointsReset) {
    // Refresh driver data after points reset
    $sql = "SELECT points FROM drivers WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $driverId);
    $stmt->execute();
    $result = $stmt->get_result();
    $refreshedData = $result->fetch_assoc();
    $driver['points'] = $refreshedData['points'];
    $stmt->close();
}

// Check for 3 fines on the same day
function checkForMultipleFinesToday($conn, $driverId)
{
    $today = date('Y-m-d');
    $sql = "SELECT COUNT(*) as fine_count FROM fines 
            WHERE driver_id = ? AND DATE(issued_date) = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $driverId, $today);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();
    $stmt->close();

    return $data['fine_count'] >= 3;
}

$multipleFines = checkForMultipleFinesToday($conn, $driverId);

// Function to update license suspension status
function updateLicenseSuspension($conn, $driverId, $suspended)
{
    $sql = "UPDATE drivers SET license_suspended = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $suspended, $driverId);
    $stmt->execute();
    $stmt->close();
}

// Determine license status
$licenseStatus = "Active";
$suspensionReason = "";

// Check for license suspension conditions
if ($driver['points'] < 8) {
    $licenseStatus = "Suspended";
    $suspensionReason = "Points below minimum threshold (8 points)";
    updateLicenseSuspension($conn, $driverId, 1);
} elseif ($multipleFines) {
    $licenseStatus = "Suspended";
    $suspensionReason = "Multiple violations (3+ fines) on the same day";
    updateLicenseSuspension($conn, $driverId, 1);
} else {
    // Ensure license is marked as active if conditions are resolved
    updateLicenseSuspension($conn, $driverId, 0);
}

// Function to get points deduction history
function getPointsHistory($conn, $driverId)
{
    $sql = "SELECT f.issued_date, f.offence, o.description_english, o.points_deducted 
            FROM fines f
            JOIN offences o ON f.offence = o.offence_number
            WHERE f.driver_id = ? AND o.points_deducted > 0
            ORDER BY f.issued_date DESC";

    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        return ['error' => "Error preparing statement: " . $conn->error];
    }

    $stmt->bind_param("s", $driverId);

    if (!$stmt->execute()) {
        return ['error' => "Error executing query: " . $stmt->error];
    }

    $result = $stmt->get_result();
    $history = [];

    while ($row = $result->fetch_assoc()) {
        $history[] = $row;
    }

    $stmt->close();
    return $history;
}

// Calculate next points reset date
function getNextResetDate($createdAt)
{
    $createDate = new DateTime($createdAt);
    $currentDate = new DateTime();

    // Find the next 3-month interval from creation date
    $monthsSinceCreation = ($currentDate->format('Y') - $createDate->format('Y')) * 12 +
        ($currentDate->format('m') - $createDate->format('m'));

    $nextResetMonths = ceil(($monthsSinceCreation + 1) / 3) * 3;

    $nextResetDate = clone $createDate;
    $nextResetDate->modify("+{$nextResetMonths} months");

    return $nextResetDate->format('F j, Y');
}

// Get next reset date
$nextResetDate = getNextResetDate($driver['created_at']);

// Get points deduction history
$pointsHistory = getPointsHistory($conn, $driverId);

// Close database connection
$conn->close();
?>

<main>
    <?php include_once "../../includes/navbar.php" ?>
    <div class="dashboard-layout">
        <?php include_once "../../includes/sidebar.php" ?>
        <div class="content">
            <button onclick="history.back()" class="back-btn" style="position: absolute; top: 7px; right: 8px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                    <path fill-rule="evenodd"
                        d="M15 8a.5.5 0 0 1-.5.5H3.707l3.147 3.146a.5.5 0 0 1-.708.708l-4-4a.5.5 0 0 1 0-.708l4-4a.5.5 0 1 1 .708.708L3.707 7.5H14.5a.5.5 0 0 1 .5.5z" />
                </svg>
            </button>
            <h2>Points Status</h2>

            <div class="points-overview">
                <div class="points-card">
                    <div class="points-header">
                        <div>
                            <h3>Current Points Status</h3>
                        </div>
                        <div class="points-value <?= ($driver['points'] < 12) ? 'points-warning' : ''; ?>">
                            <?= $driver['points'] ?>
                        </div>
                    </div>

                    <div class="points-info">
                        <p>Next points reset scheduled for: <strong><?= $nextResetDate ?></strong></p>

                        <?php if ($pointsReset): ?>
                            <div class="points-reset-notification">
                                <i class="fas fa-sync-alt"></i> Your points have been reset to 20 based on your 3-month
                                cycle.
                            </div>
                        <?php endif; ?>

                        <div
                            class="license-status-container <?= $licenseStatus === 'Suspended' ? 'suspended' : 'active' ?>">
                            <h4>License Status: <?= $licenseStatus ?></h4>
                            <?php if ($licenseStatus === 'Suspended'): ?>
                                <p class="suspension-reason"><i class="fas fa-exclamation-triangle"></i>
                                    <?= $suspensionReason ?></p>
                                <p>Please contact traffic authorities immediately to resolve this issue.</p>
                            <?php elseif ($driver['points'] < 12): ?>
                                <p class="points-warning"><i class="fas fa-exclamation-circle"></i> Warning: Your points are
                                    below 12. Drive carefully to avoid license suspension.</p>
                            <?php else: ?>
                                <p><i class="fas fa-check-circle"></i> Your license is in good standing.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="points-history">
                <h3>Points Deduction History</h3>
                <?php if (isset($pointsHistory['error'])): ?>
                    <div class="alert alert-danger"><?= $pointsHistory['error'] ?></div>
                <?php elseif (empty($pointsHistory)): ?>
                    <div class="no-history">No points deduction history found.</div>
                <?php else: ?>
                    <div class="table-container">
                        <table class="points-table">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Offence</th>
                                    <th>Points Deducted</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($pointsHistory as $record): ?>
                                    <tr>
                                        <td><?= date('d-m-Y', strtotime($record['issued_date'])) ?></td>
                                        <td><?= htmlspecialchars($record['description_english']) ?></td>
                                        <td><?= $record['points_deducted'] ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>

<?php include_once "../../../includes/footer.php" ?>