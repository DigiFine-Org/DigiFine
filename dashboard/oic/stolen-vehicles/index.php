<?php
$pageConfig = [
    'title' => 'Report Stolen Vehicle',
    'styles' => ["../../dashboard.css"],
    'scripts' => ["../../dashboard.js"],
    'authRequired' => true
];

require_once "../../../includes/header.php";
require_once "../../../db/connect.php";

if ($_SESSION['user']['role'] !== 'oic') {
    die("Unauthorized user!");
}
?>

<main>
    <?php include_once "../../includes/navbar.php" ?>
    <div class="dashboard-layout">
        <?php include_once "../../includes/sidebar.php" ?>
        <div class="content">
            <div class="container">
                <h1>Report Stolen Vehicle</h1>
                <form action="submit-stolen-vehicle-process.php" method="post" enctype="multipart/form-data">
                    <p><b>Vehicle Details</b></p>
                    <div class="field">
                        <label for="vehicle_number">Vehicle Number:</label>
                        <input type="text" class="input" name="vehicle_number" id="vehicle_number" required>
                    </div>
                    <div class="field">
                        <label for="absolute_owner">Absolute Owner:</label>
                        <input type="text" class="input" name="absolute_owner" id="absolute_owner" required>
                    </div>
                    <div class="field">
                        <label for="engine_no">Engine Number:</label>
                        <input type="text" class="input" name="engine_no" id="engine_no" required>
                    </div>
                    <div class="field">
                        <label for="make">Make:</label>
                        <input type="text" class="input" name="make" id="make" required>
                    </div>
                    <div class="field">
                        <label for="model">Model:</label>
                        <input type="text" class="input" name="model" id="model" required>
                    </div>
                    <div class="field">
                        <label for="colour">Colour:</label>
                        <input type="text" class="input" name="colour" id="colour" required>
                    </div>
                    <div class="field">
                        <label for="date_of_registration">Date of Registration:</label>
                        <input type="date" class="input" name="date_of_registration" id="date_of_registration" required>
                    </div>
                    <div class="field">
                        <label for="status">Status:</label>
                        <input type="text" class="input" name="status" id="status" required>
                    </div>
                    <div class="field">
                        <label for="date_reported_stolen">Date Reported Stolen:</label>
                        <input type="date" class="input" name="date_reported_stolen" id="date_reported_stolen" required>
                    </div>
                    <p><b>Additional Details</b></p>
                    <div class="field">
                        <label for="location_last_seen">Location Last Seen:</label>
                        <input type="text" class="input" name="location_last_seen" id="location_last_seen" required>
                    </div>
                     <div class="field">
                        <label for="last_seen_date">Date Last Seen:</label>
                        <input 
                            type="date" 
                            class="input" 
                            name="last_seen_date" 
                            id="last_seen_date" p;
                            lko
                            required
                        >
                        <small id="dateError" style="color: red; display: none;">Date cannot be in the future!</small>
                    </div>

                    
                    <script>
                        const lastSeenDateInput = document.getElementById('last_seen_date');
                        const dateError = document.getElementById('dateError');

                        // Set the max date to today for the last_seen_date field
                        const today = new Date().toISOString().split('T')[0];
                        lastSeenDateInput.max = today;

                        // Add an event listener to validate the date on input change
                        lastSeenDateInput.addEventListener('input', () => {
                            if (new Date(lastSeenDateInput.value) > new Date(today)) {
                                dateError.style.display = 'block'; // Show the error message
                            } else {
                                dateError.style.display = 'none'; // Hide the error message
                            }
                        });
                    </script>

                    <!-- <div class="field">
                        <label for="vehicle_photo">Upload Vehicle Photo (JPG only):</label>
                        <input type="file" class="input" name="vehicle_photo" accept=".jpg" required>
                    </div> -->
                    <button class="btn">Submit Report</button>
                </form>
            </div>
        </div>
    </div>
</main>

<?php include_once "../../../includes/footer.php" ?>
