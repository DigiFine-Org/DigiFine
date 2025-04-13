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

// Fetch assigned duty details for validation or additional display
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

// Get current date and time in digital format
$current_date = date('Y-m-d');
$current_time = date('H:i:s');
$current_datetime = date('Y-m-d H:i:s');

// Create datetime objects for comparison
$duty_start_datetime = $duty['duty_date'] . ' ' . $duty['duty_time_start'];
$duty_end_datetime = $duty['duty_date'] . ' ' . $duty['duty_time_end'];

// Check if duty is already submitted
$duty_submitted = $duty['submitted'] == 1;

// Determine if the duty can be submitted (after start time and before end time)
$can_submit = !$duty_submitted && ($current_datetime >= $duty_start_datetime) && ($current_datetime <= $duty_end_datetime);

// Determine status message
$status_message = "";
$status_class = "";

if ($duty_submitted) {
    $status_message = "This duty has already been submitted.";
    $status_class = "success-message";
} elseif ($current_datetime < $duty_start_datetime) {
    $status_message = "You can not submit this duty until " . date('Y-m-d H:i:s', strtotime($duty_start_datetime));
    $status_class = "error-message";
} elseif (($current_datetime < $duty_end_datetime)) {
    $status_message = "This duty has expired. The submission period ended on " . date('Y-m-d H:i:s', strtotime($duty_end_datetime));
    $status_class = "error-message";
}
?>
 
<main>
    <?php include_once "../../includes/navbar.php" ?>
    <div class="dashboard-layout">
        <?php include_once "../../includes/sidebar.php" ?>
        <div class="content">
            <div class="container">
                <h1>Submit Duty</h1>
                
                <?php if (!empty($status_message)): ?>
                <div class="<?php echo $status_class; ?>"><?php echo $status_message; ?></div>
                <?php endif; ?>
                
                <div class="duty-details">
                    <h3>Duty Details</h3>
                    <p><strong>Date:</strong> <?php echo date('Y-m-d', strtotime($duty['duty_date'])); ?></p>
                    <p><strong>Time:</strong> <?php echo date('H:i:s', strtotime($duty['duty_time_start'])); ?> - <?php echo date('H:i:s', strtotime($duty['duty_time_end'])); ?></p>
                    <p><strong>Status:</strong> <?php echo $duty['submitted'] ? 'Submitted' : 'Pending Submission'; ?></p>
                </div>
                
                <?php if ($can_submit): ?>
                <form action="submit-duty-process.php" method="post">
                    <p><b>Police Officer Details</b></p>
                    <div class="field">
                        <label for="">Police ID:</label>
                        <input type="text" class="input" value="<?= htmlspecialchars($policeId) ?>" disabled>
                        <input type="hidden" name="police_id" value="<?= htmlspecialchars($policeId) ?>">
                        <input type="hidden" name="assigned_duty_id" value="<?= htmlspecialchars($assignedDutyId) ?>">
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
                    <div class="field">
                        <label for="">Patrol Information:</label>
                        <textarea type="text" class="input" name="patrol_information" required></textarea>
                    </div>
                    <button class="btn">Submit</button>
                </form>
                <?php else: ?>
                <p>The submission form is not available at this time.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>

<?php include_once "../../../includes/footer.php" ?>