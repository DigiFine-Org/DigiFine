<?php
$pageConfig = [
    'title' => 'Submit Duty',
    'styles' => ["../../dashboard.css"],
    'scripts' => ["../../dashboard.js"],
    'authRequired' => true
];

require_once "../../../includes/header.php";
if ($_SESSION['user']['role'] !== 'officer') {
    die("unauthorized user!");
}

$policeId = $_SESSION['user']['id'] ?? '';
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
                        <input type="text" class="input" value="<?php echo htmlspecialchars($policeId) ?>" disabled>
                        <input type="hidden" class="input" name="police_id"
                            value="<?php echo htmlspecialchars($policeId) ?>">
                    </div>
                    <p><b>Duty Information</b></p>
                    <div class="field">
                        <label for="">Patrol Location :</label>
                        <input type="text" class="input" name="patrol_location" required>
                    </div>
                    <div class="field">
                        <label for="">Patrol Time(Start):</label>
                        <input type="time" class="input" name="patrol_time_start" required>
                    </div>
                    <div class="field">
                        <label for="">Patrol Time(End):</label>
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

<?php include_once "../../../includes/footer.php" ?>