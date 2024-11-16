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

?>

<main>
    <!-- <script>
        function fetchDriverData() {
            const id = document.getElementById("id").value;

            if (id) {
                // Send an AJAX request to fetch driver data from the backend
                fetch(`backend/get-driver-data.php?id=${id}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Populate the fields with fetched data
                            document.getElementById("full_name").value = data.full_name;
                            document.getElementById("d_address").value = data.d_address;
                            document.getElementById("license_valid_from").value = data.license_valid_from;
                            document.getElementById("license_valid_to").value = data.license_valid_to;
                            document.getElementById("competent_categories").innerHTML = data.competent_categories;
                        } else {
                            alert("Driver data not found!");
                        }
                    })
                    .catch(error => console.error("Error fetching driver data:", error));
            } else {
                alert("Please enter a license number.");
            }
        }

        function fetchViolationData() {
            const violationId = document.getElementById("violation_id").value;

            if (violationId) {
                // Send an AJAX request to fetch violation data from the backend
                fetch(`backend/get-violation-data.php?violation_id=${violationId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Populate the fields with fetched data
                            document.getElementById("violation_name").value = data.violation_name;
                            document.getElementById("price").value = data.price;
                        } else {
                            alert("Violation data not found!");
                        }
                    })
                    .catch(error => console.error("Error fetching violation data:", error));
            } else {
                alert("Please enter a violation ID.");
            }
        }
    </script> -->


    <?php include_once "../../includes/navbar.php" ?>
    <div class="dashboard-layout">
        <?php include_once "../../includes/sidebar.php" ?>
        <div class="content">
            <div class="container">
                <h1>Issue Traffic Fine</h1>
                <form method="POST" action="backend/store-fine.php">

                    <div class="field">
                        <label for="driver_id">Driver License ID</label>
                        <input type="text" id="driver_id" name="driver_id" class="input" placeholder="CAD-6264" required>
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
                        <label for="vehicle_number">Vehicle License Number</label>
                        <input type="text" id="vehicle_number" name="vehicle_number" class="input" placeholder="CAD-6264" required>
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

<?php include_once "../../../includes/footer.php" ?>

<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
