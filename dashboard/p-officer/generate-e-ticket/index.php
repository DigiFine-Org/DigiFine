<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$pageConfig = [
    'title' => 'Generate E-ticket',
    'styles' => ["../../dashboard.css"],
    'scripts' => ["../../dashboard.js"],
    'authRequired' => true
];

include_once "../../../includes/header.php";
<<<<<<< HEAD

=======
>>>>>>> db80d349711448c20f9c0b5a59666efc9d2987af
?>

<main>
    <?php include_once "../../includes/navbar.php" ?>
    <div class="dashboard-layout">
        <?php include_once "../../includes/sidebar.php" ?>
        <div class="content">
            <div class="container">
                <h1>Issue Traffic Fine</h1>
                <form method="POST" action="backend/store-fine.php">

                    <div class="field">
                        <label for="id">Driver License ID</label>
                        <input type="text" id="id" name="id" class="input" placeholder="CAD-6264" required>
                    </div>
                    <div class="field">
                        <button class="btn" type="button" onclick="fetchDriverData()">Fetch Driver Details</button>
                        <script src="backend/fetchDriverData.js"></script>
                    </div>
                    <div class="field">
                        <label for="full_name">Full Name</label>
                        <input type="text" id="full_name" name="full_name" class="input" placeholder="CAD-6264" required>
                    </div>

                    <div class="field">
                        <label for="d_address">Address</label>
                        <input type="text" id="d_address" name="d_address" class="input" placeholder="CAD-6264" required>
                    </div>

                    <div class="field">
                        <label for="license_valid_from">License Valid From</label>
                        <input type="date" id="license_valid_from" name="license_valid_from" class="input" placeholder="CAD-6264" readonly>
                    </div>

                    <div class="field">
                        <label for="license_valid_to">License Valid To</label>
                        <input type="date" id="license_valid_to" name="license_valid_to" class="input" placeholder="CAD-6264" readonly>
                    </div>
                    <div class="field">
                        <label for="competent_categories">Competent Drive Categories</label>
                        <div class="field" id="competent_categories"></div>
                    </div>
                    <div class="field">
<<<<<<< HEAD
                        <label for="vehicle_number">Vehicle License Number</label>
                        <input type="text" id="vehicle_number" name="vehicle_number" class="input" placeholder="CAD-6264" required>
=======
                        <label for="">Offense Type:</label>
                        <select type="time" class="input" required>
                            <option value="">Fine</option>
                            <option value="">Court</option>
                        </select>
>>>>>>> db80d349711448c20f9c0b5a59666efc9d2987af
                    </div>

                    <div class="field">
                        <label for="issued_place">Issued Place</label>
                        <input type="text" id="issued_place" name="issued_place" class="input" placeholder="CAD-6264" required>
                    </div>
                    <div class="field">
                        <!-- <label for="category">Nature of Offence (Category)</label> -->
                        <label for="categoryDropdown">Nature of Offence (Category)</label>
                        <input type="text" id="category" name="category" class="input" placeholder="CAD-6264" required>
                    </div>
                    <div class="field">
                        <!-- New section for violation -->
                        <label for="violation_id">Violation ID</label>
                        <input type="text" id="violation_id" name="violation_id" class="input" placeholder="CAD-6264" required>
                    </div>
                    <div class="field">
                        <button class="btn" type="button" onclick="fetchViolationData()">Fetch Violation Details</button>
                        <script src="backend/fetchViolationData.js"></script>
                    </div>
                    <div class="field">
                        <label for="violation_name">Violation Name</label>
                        <input type="text" id="violation_name" name="violation_name" class="input" placeholder="CAD-6264" required>
                    </div>
                    <div class="field">
                        <label for="description">Description</label>
                        <textarea id="description" name="description" class="input" placeholder="CAD-6264" required></textarea>
                    </div>
                    <div class="field">
                        <label for="price">Price</label>
                        <input type="text" id="price" name="price" class="input" placeholder="CAD-6264" readonly>
                    </div>


                    <!-- <div class="field">
                            <label for="">Law Type:</label>
                            <select type="time" class="input" required>
                                <option value="">Fine</option>
                                <option value="">Court</option>
                            </select>
                        </div> -->

                    <!-- <div class="field">
                            <label for="">Evidence:</label>
                            <input type="image" class="input" required>
                        </div> -->
                    <button class="btn" type="submit">Generate</button>
                </form>
            </div>
        </div>
    </div>
</main>

<<<<<<< HEAD
<?php include_once "../../../includes/footer.php" ?>

<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
=======
<?php include_once "../../../includes/footer.php" ?>
>>>>>>> db80d349711448c20f9c0b5a59666efc9d2987af
