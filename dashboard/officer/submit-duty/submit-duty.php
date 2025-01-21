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

// Fetch assigned duty details for validation or additional display (optional)
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
?>

<main>
    <?php include_once "../../includes/navbar.php" ?>
    <div class="dashboard-layout">
        <?php include_once "../../includes/sidebar.php" ?>
        <div class="content">
            <div class="container">
                <h1>Submit Duty</h1>
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
                        <input type="time" class="input" name="patrol_time_start" required>
                    </div>
                    <div class="field">
                        <label for="">Patrol Time (End):</label>
                        <input type="time" class="input" name="patrol_time_end" required>
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

<<<<<<< HEAD
<<<<<<< HEAD
<?php include_once "../../../includes/footer.php" ?>
=======
<?php include_once "../../../includes/footer.php" ?>
>>>>>>> 35ae4724cab9c47f0e68afda1ff3af0e30f15e6d
=======
<?php include_once "../../../includes/footer.php" ?>
>>>>>>> 4035893fb72d6cee2accb82ad812e8d242fd64c3
