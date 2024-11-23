<?php
$pageConfig = [
    'title' => 'Submit Duty',
    'styles' => ["../../dashboard.css"],
    'scripts' => ["../../dashboard.js"],
    'authRequired' => true
];

require_once "../../../includes/header.php";

$policeId = $_SESSION['user']['id'] ?? '';
?>

<main>
    <?php include_once "../../includes/navbar.php" ?>
    <div class="dashboard-layout">
        <?php include_once "../../includes/sidebar.php" ?>
        <div class="content">
            <div class="container">
                <h1>Submit Duty</h1>
                <form action="submit-duty-process.php" method="pos">
                    <p><b>Police Officer Details</b></p>
                    <div class="field">
                        <label for="">Police ID:</label>
                        <input type="text" class="input" name="police_id"
                            value="<?php echo htmlspecialchars($policeId) ?>" disabled>
                    </div>
                    <p><b>Duty Information</b></p>
                    <div class="field">
                        <label for="">Patrol Location :</label>
                        <input type="text" class="input" name="patrol_location" required>
                    </div>
                    <div class="field">
                        <label for="">Patrol Time(Start):</label>
                        <input type="date" class="input" name="patrol_time_start" required>
                    </div>
                    <div class="field">
                        <label for="">Patrol Time(End):</label>
                        <input type="date" class="input" name="patrol_time_end" required>
                    </div>
                    <div class="field" required>
                        <label for="">Patrol Information:</label>
                        <textarea type="text" class="input" name="patrol_information"></textarea>
                    </div>
                    <div class="field" required hidden>
                        <textarea type="time" class="input" name="created_at" hidden></textarea>
                    </div>
                    <button class="btn">Submit</button>
                </form>
            </div>
        </div>
    </div>
</main>

<?php include_once "../../../includes/footer.php" ?>