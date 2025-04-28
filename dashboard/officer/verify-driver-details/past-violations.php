<?php
$pageConfig = [
    'title' => 'Driver Previous Violations',
    'styles' => ["../../dashboard.css"],
    'scripts' => ["../../dashboard.js"],
    'authRequired' => true
];

require_once "../../../db/connect.php";
include_once "../../../includes/header.php";

if ($_SESSION['user']['role'] !== 'officer') {
    die("unauthorized user!");
}

$driverId = $_GET['license_id'] ?? null;

if (!$driverId) {
    $_SESSION['message'] = "Driver ID is missing!";
    header("Location: ../verify-driver-details/");
    exit();
}

$driverSql = "SELECT * FROM dmt_drivers WHERE license_id = ?";
$driverStmt = $conn->prepare($driverSql);
$driverStmt->bind_param("s", $driverId);
$driverStmt->execute();
$driverResult = $driverStmt->get_result();

if ($driverResult->num_rows === 0) {
    $_SESSION['message'] = "NIC Mismatch or Driver not found for the provided NIC in this System.";
    header("Location: ../verify-driver-details/");
    exit();
}

$driver = $driverResult->fetch_assoc();

$sql = "SELECT * FROM fines WHERE driver_id = ? ORDER BY issued_date DESC";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Database error: " . $conn->error);
}

$stmt->bind_param("s", $driverId);
$stmt->execute();
$result = $stmt->get_result();

?>

<?php include_once "../../includes/navbar.php" ?>
<div class="dashboard-layout">
    <?php include_once "../../includes/sidebar.php" ?>
    <div class="content">
        <img class="watermark" src="../../../assets/watermark.png" />
        <div class="container">
            <button onclick="history.back()" class="back-btn" style="position: absolute; top: 7px; right: 8px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                    <path fill-rule="evenodd"
                        d="M15 8a.5.5 0 0 1-.5.5H3.707l3.147 3.146a.5.5 0 0 1-.708.708l-4-4a.5.5 0 0 1 0-.708l4-4a.5.5 0 1 1 .708.708L3.707 7.5H14.5a.5.5 0 0 1 .5.5z" />
                </svg>
            </button>
            <h1>Driver Past Violations</h1>
            <?php if ($result->num_rows > 0): ?>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Issued Date</th>
                                <th>Time</th>
                                <th>Location</th>
                                <th>Offence</th>
                                <th>Nature of Offence</th>
                                <th>Type</th>
                                <th>Status</th>
                                <th>Fine Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?= $row['issued_date'] ?></td>
                                    <td><?= $row['issued_time'] ?></td>
                                    <td><?= $row['location'] ?></td>
                                    <td><?= $row['offence'] ?: 'N/A' ?></td>
                                    <td><?= $row['nature_of_offence'] ?></td>
                                    <td><?= ucfirst($row['offence_type']) ?></td>
                                    <td><?= ucfirst($row['fine_status']) ?></td>
                                    <td><?= number_format($row['fine_amount'], 2) ?> LKR</td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p>No past violations found for this driver.</p>
            <?php endif; ?>
            <br>
            <a href="../generate-e-ticket/index.php?id=<?= $driver['license_id'] ?>&nic=<?= $driver['nic'] ?>"
                class="btn">Issue Fine</a>


        </div>
    </div>
</div>
</main>

<?php include_once "../../../includes/footer.php" ?>