<?php
$pageConfig = [
    'title' => 'Assign Duties',
    'styles' => ["../../dashboard.css"],
    'scripts' => ["../../dashboard.js"],
    'authRequired' => true
];

include_once "../../../includes/header.php";
include_once "../../../db/connect.php";

if ($_SESSION['user']['role'] !== 'oic') {
    die("Unauthorized user!");
}

$result = "";
if (isset($_GET)) {
    $result = $_GET['query'] ?? "";
}

// Fetch OIC's station
$oicId = $_SESSION['user']['id'];
$stationQuery = "SELECT police_station FROM officers WHERE id = ? AND is_oic = 1";
$stationStmt = $conn->prepare($stationQuery);
$stationStmt->bind_param("i", $oicId);
$stationStmt->execute();
$stationResult = $stationStmt->get_result();
$stationData = $stationResult->fetch_assoc();
$stationId = $stationData['police_station'];

// Fetch all officers under this station who are not OICs
$officersQuery = "SELECT id, fname, lname FROM officers WHERE police_station = ? AND is_oic = 0 ORDER BY lname, fname";
$officersStmt = $conn->prepare($officersQuery);
$officersStmt->bind_param("i", $stationId);
$officersStmt->execute();
$officersResult = $officersStmt->get_result();
?>

<main>
    <?php include_once "../../includes/navbar.php"; ?>
    <div class="dashboard-layout">
        <?php include_once "../../includes/sidebar.php"; ?>
        <div class="content">
            <div class="container">
                <h1>Assign Duty</h1>
                <form action="assign-duty-handler.php" method="POST">
                    <div class="field">
                        <label for="policeId">Select Officer:</label>
                        <select name="policeId" class="input" required>
                            <option value="">Select Officer</option>
                            <?php while ($officer = $officersResult->fetch_assoc()): ?>
                                <option value="<?php echo $officer['id']; ?>">
                                    <?php echo $officer['fname'] . ' ' . $officer['lname'] . ' - ' . $officer['id']; ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="field">
                        <label for="duty">Duty:</label>
                        <input type="text" name="duty" class="input" required>
                    </div>
                    <div class="field">
                        <label for="dutyDate">Duty Date:</label>
                        <input type="date" name="dutyDate" class="input" id="dutyDate" min="<?= date('Y-m-d') ?>" required>
                    </div>
                    <div class="field">
                        <label for="">Duty Time (Start):</label>
                        <input type="time" class="input" name="duty_time_start" required>
                    </div>
                    <div class="field">
                        <label for="">Duty Time (End):</label>
                        <input type="time" class="input" name="duty_time_end" required>
                    </div>
                    <div class="field">
                        <label for="notes">Additional Notes:</label>
                        <textarea name="notes" id="notes"></textarea>
                    </div>
                    <button class="btn">Assign Duty</button>
                </form>
                
                <?php if (isset($_SESSION['success'])): ?>
                    <div class="success-message"><?php echo $_SESSION['success']; ?></div>
                    <?php unset($_SESSION['success']); ?>
                <?php endif; ?>

                <?php if (isset($_SESSION['error'])): ?>
                    <div class="error-message"><?php echo $_SESSION['error']; ?></div>
                    <?php unset($_SESSION['error']); ?>
                <?php endif; ?>

            </div>
        </div>
    </div>
</main>

<?php 
// Close statements and connection
if (isset($stationStmt)) $stationStmt->close();
if (isset($officersStmt)) $officersStmt->close();

include_once "../../../includes/footer.php"; 
?>