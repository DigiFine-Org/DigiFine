<?php
$pageConfig = [
    'title' => 'Submit Duty',
    'styles' => ["../../dashboard.css"],
    'scripts' => ["../../dashboard.js"],
    'authRequired' => true
];

require_once "../../../includes/header.php";
require_once "../../../db/connect.php";

if ($_SESSION['user']['role'] !== 'officer') {
    die("Unauthorized user!");
}

$policeId = $_SESSION['user']['id'] ?? '';
$assignedDutyId = $_GET['id'] ?? null;

if (!$assignedDutyId) {
    die("No duty ID provided.");
}

$stmt = $conn->prepare("SELECT * FROM assigned_duties WHERE id = ? AND police_id = ?");
if (!$stmt) {
    die("Error preparing statement: " . $conn->error);
}
$stmt->bind_param("ii", $assignedDutyId, $policeId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("No assigned duty found for the given ID.");
}

$duty = $result->fetch_assoc();
$stmt->close();

// Late submission logic
date_default_timezone_set('Asia/Colombo');
$currentDate = date('Y-m-d');
$is_late_submission = ($currentDate > $duty['duty_date']);
$status_message = $is_late_submission ? "Late submission: This duty was due on " . $duty['duty_date'] : "";
$status_class = $is_late_submission ? "error-message" : "";
?>

<main>
    <?php include_once "../../includes/navbar.php" ?>
    <div class="dashboard-layout">
        <?php include_once "../../includes/sidebar.php" ?>
        <div class="content">
            <div class="container">
                <h1>Submit Duty</h1>
                
                <?php if (!empty($status_message)): ?>
                <div class="<?= $status_class ?>"><?= $status_message ?></div>
                <?php endif; ?>
                
                <form action="submit-duty-process.php" method="post">
                    <p><b>Police Officer Details</b></p>
                    <div class="field">
                        <label for="">Police ID:</label>
                        <input type="text" class="input" value="<?= htmlspecialchars($policeId) ?>" disabled>
                        <input type="hidden" name="police_id" value="<?= htmlspecialchars($policeId) ?>">
                        <input type="hidden" name="assigned_duty_id" value="<?= htmlspecialchars($assignedDutyId) ?>">
                        <input type="hidden" name="is_late_submission" value="<?= $is_late_submission ? 1 : 0 ?>">
                    </div>
                    <p><b>Duty Information</b></p>
                    <div class="field">
                        <label for="">Patrol Location:</label>
                        <input type="text" class="input" name="patrol_location" required>
                    </div>
                    <div class="field">
                        <label for="">Patrol Time (Start):</label>
                        <input type="time" class="input" name="patrol_time_started" required>
                    </div>
                    <div class="field">
                        <label for="">Patrol Time (End):</label>
                        <input type="time" class="input" name="patrol_time_ended" required>
                    </div>
                    <div class="field" required>
                        <label for="">Patrol Information:</label>
                        <textarea type="text" class="input" name="patrol_information"></textarea>
                    </div>
                    <button class="btn">Submit</button>
                </form>
            </div>
        </div>
    </div>
</main>

<?php include_once "../../../includes/footer.php" ?>