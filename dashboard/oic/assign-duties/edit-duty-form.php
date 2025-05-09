<?php
$pageConfig = [
    'title' => 'Edit Duty',
    'styles' => ["../../dashboard.css", "./edit-duty-form.css"],
    'scripts' => ["../../dashboard.js"],
    'authRequired' => true
];

include_once "../../../includes/header.php";
include_once "../../../db/connect.php";

if ($_SESSION['user']['role'] !== 'oic') {
    die("Unauthorized user!");
}

$dutyId = $_GET['id'] ?? null;
if (!$dutyId) {
    die("Invalid duty ID.");
}

// Fetch duty details
$dutyQuery = "SELECT * FROM assigned_duties WHERE id = ?";
$dutyStmt = $conn->prepare($dutyQuery);
$dutyStmt->bind_param("i", $dutyId);
$dutyStmt->execute();
$dutyResult = $dutyStmt->get_result();
$duty = $dutyResult->fetch_assoc();

if (!$duty) {
    die("Duty not found.");
}
?>

<main>
    <?php include_once "../../includes/navbar.php"; ?>
    <div class="dashboard-layout">
        <?php include_once "../../includes/sidebar.php"; ?>
        <div class="content">
            <div class="container">
                <h1>Edit Duty</h1>
                <form action="update-duty-handler.php" method="POST">
                    <input type="hidden" name="dutyId" value="<?php echo $duty['id']; ?>">
                    <div class="field">
                        <label for="duty">Duty:</label>
                        <input type="text" name="duty" class="input" value="<?php echo $duty['duty']; ?>" required>
                    </div>
                    <div class="field">
                        <label for="dutyDate">Duty Date:</label>
                        <input type="date" name="dutyDate" class="input" value="<?php echo $duty['duty_date']; ?>" required>
                    </div>
                    <div class="field">
                        <label for="dutyTimeStart">Start Time:</label>
                        <input type="time" name="dutyTimeStart" class="input" value="<?php echo $duty['duty_time_start']; ?>" required>
                    </div>
                    <div class="field">
                        <label for="dutyTimeEnd">End Time:</label>
                        <input type="time" name="dutyTimeEnd" class="input" value="<?php echo $duty['duty_time_end']; ?>" required>
                    </div>
                    <div class="field">
                        <label for="notes">Notes:</label>
                        <textarea name="notes" class="input"><?php echo $duty['notes']; ?></textarea>
                    </div>
                    <button type="submit" class="btn">Update Duty</button>
                </form>
            </div>
        </div>
    </div>
</main>

<?php
// Close statements and connection
if (isset($dutyStmt))
    $dutyStmt->close();

include_once "../../../includes/footer.php";
?>