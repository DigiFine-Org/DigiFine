<?php
$pageConfig = [
    'title' => 'Generate E-ticket',
    'styles' => ["../../dashboard.css"], // Includes alert styles
    'scripts' => ["../../dashboard.js"],   // Includes alert scripts
    'authRequired' => true
];

session_start();
require_once "../../../db/connect.php";

// Fetch offences from offences table
$sql = "SELECT offence_number, description_english, fine_amount FROM offences";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    $offences = $result->fetch_all(MYSQLI_ASSOC);
} else {
    $offences = [];
}

// Retrieve the current officer's police station id from the session

// Fetch duty locations for this police station from the duty_locations table

// Fetch current user
$policeId = $_SESSION['user']['id'] ?? '';

$policeStationId = null;

if ($policeId) {
    $sqlStation = "SELECT police_station FROM officers WHERE id = '$policeId'";
    $resultStation = $conn->query($sqlStation);
    if ($resultStation && $resultStation->num_rows > 0) {
        $dataStation = $resultStation->fetch_assoc();
        $policeStationId = $dataStation['police_station'];
    }
}

echo "Police staion ID: " . htmlspecialchars($policeStationId);

$locations = [];
if ($policeStationId) {
    $sqlLocations = "SELECT location_name FROM duty_locations WHERE police_station_id = '$policeStationId' ORDER BY location_name ASC";
    $resultLocations = $conn->query($sqlLocations);
    if ($resultLocations && $resultLocations->num_rows > 0) {
        $locations = $resultLocations->fetch_all(MYSQLI_ASSOC);
    }
}

$lastLocation = ""; // Default empty

// Ensure the officer is identified
if ($policeId) {
    $sqlLastLocation = "SELECT location FROM fines WHERE police_id = '$policeId' ORDER BY issued_date DESC, issued_time DESC LIMIT 1";
    $resultLastLocation = $conn->query($sqlLastLocation);

    if ($resultLastLocation && $resultLastLocation->num_rows > 0) {
        $dataLastLocation = $resultLastLocation->fetch_assoc();
        $lastLocation = $dataLastLocation['location'];
    }
}

echo "Locations: " . htmlspecialchars(print_r($locations, true));

// Include header
include_once "../../../includes/header.php";

// Check if the user is authorized as an officer
if ($_SESSION['user']['role'] !== 'officer') {
    echo "<script>showAlert('Unauthorized user!', 'error');</script>";
    echo "<script>setTimeout(() => window.location.href = '/digifine/dashboard/officer/login.php', 3000);</script>";
    exit();
}

$driverId = ""; // Default empty

if (isset($_GET['nic'])) {
    $nic = $conn->real_escape_string($_GET['nic']);

    // Fetch Driver ID from NIC
    $query = "SELECT id FROM drivers WHERE nic = '$nic'";
    $result = $conn->query($query);

    if (!$result) {
        die("SQL Error: " . $conn->error);
    }

    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc();
        $driverId = $data['id'];
    } else {
        $_SESSION['message'] = 'NIC Mismatch or Driver not found for the provided NIC in this System.';
        header("Location: /digifine/dashboard/officer/generate-e-ticket/index.php");
        exit();
    }
}

$conn->close();

if ($_SESSION['message'] ?? null) {
    if ($_SESSION['message'] === 'success') {
        $message = "E-Ticket generated successfully!";
        unset($_SESSION['message']); // Clear the session message
        include '../../../includes/alerts/success.php';
    } else {
        $message = $_SESSION['message']; // Store the message
        unset($_SESSION['message']); // Clear the session message
        include '../../../includes/alerts/failed.php';
    }
}
?>

<main>
    <?php include_once "../../includes/navbar.php" ?>
    <div class="dashboard-layout">
        <?php include_once "../../includes/sidebar.php" ?>
        <div class="content">
            <div id="alert-container"></div> <!-- Alert container -->
            <div class="container">
                <h1>Generate E-Ticket</h1>
                <form action="generate-e-ticket-process.php" method="POST">
                    <div class="field">
                        <label for="">Your Police ID:</label>
                        <input type="text" class="input" value="<?php echo htmlspecialchars($policeId) ?>" disabled>
                    </div>
                    <div class="field">
                        <label for="issued_date">Date of Offence:</label>
                        <input type="date" class="input" id="issued_date" value="<?php echo date('Y-m-d'); ?>" disabled>
                        <input type="hidden" name="issued_date" value="<?php echo date('Y-m-d'); ?>">
                    </div>
                    <div class="field">
                        <label for="">Time:</label>
                        <input type="text" class="input clock" id="clock" disabled>
                        <input type="hidden" name="issued_time" id="hidden_clock">
                    </div>
                    <div class="field">
                        <label for="driver_id">Driver License ID:</label>
                        <input type="text" class="input" id="driver_id" name="driver_id"
                            value="<?php echo htmlspecialchars($driverId); ?>" placeholder="B5767089" required>
                    </div>
                    <div class="field">
                        <label for="">Vehicle License Number:</label>
                        <?php
                        $licensePlateNumber = $_GET['license_plate_number'] ?? '';
                        ?>
                        <input type="text" class="input" placeholder="CAD-6264" name="license_plate_number" value="<?php echo htmlspecialchars($licensePlateNumber); ?>" required>
                    </div>
                    <div class="field">
                        <label for="">Offence Type:</label>
                        <select id="offence_type" class="input" name="offence_type" required>
                            <option value="">Select Type</option>
                            <option value="fine">Fine</option>
                            <option value="court">Court</option>
                        </select>
                    </div>
                    <div class="field" id="offence_select_field" style="display: none;">
                        <label for="offence">Select Offence:</label>
                        <select name="offence" id="offence" class="input">
                            <option value="">Select Offence</option>
                            <?php foreach ($offences as $offence): ?>
                                <option value="<?php echo htmlspecialchars($offence['offence_number']); ?>"
                                    data-fine="<?= htmlspecialchars($offence['fine_amount'] ?? 0); ?>">
                                    <?php echo htmlspecialchars($offence['description_english']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <!-- Updated Location field with a search bar (datalist) -->
                    <div class="field">
                        <label for="location">Location:</label>
                        <select name="location" class="input" required>
                            <!-- Default selection is the last location, but the officer can change it -->
                            <?php if (!empty($lastLocation)): ?>
                                <option value="<?php echo htmlspecialchars($lastLocation); ?>" selected>
                                    <?php echo htmlspecialchars($lastLocation); ?> (Last Used)
                                </option>
                            <?php else: ?>
                                <option value="" selected disabled>Select a location</option>
                            <?php endif; ?>

                            <?php foreach ($locations as $loc): ?>
                                <!-- Prevent duplicate entry of the last location in the dropdown -->
                                <?php if ($loc['location_name'] !== $lastLocation): ?>
                                    <option value="<?php echo htmlspecialchars($loc['location_name']); ?>">
                                        <?php echo htmlspecialchars($loc['location_name']); ?>
                                    </option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </select>
                    </div>


                    <div class="field">
                        <label for="fine_amount">Fine Amount:</label>
                        <input type="text" class="input" id="fine_amount" name="fine_amount" value="0" readonly>
                    </div>
                    <div class="field">
                        <label for="">Nature of Offence:</label>
                        <textarea class="input" name="nature_of_offence" placeholder="Describe the nature of the offence" required></textarea>
                    </div>
                    <button class="btn">Generate</button>
                </form>
            </div>
        </div>
    </div>
</main>

<script>
    // Clock update
    function updateClock() {
        const now = new Date();
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        const seconds = String(now.getSeconds()).padStart(2, '0');
        document.getElementById("clock").value = `${hours}:${minutes}:${seconds}`;
        document.getElementById("hidden_clock").value = `${hours}:${minutes}:${seconds}`;
    }
    setInterval(updateClock, 1000);
    updateClock();

    // Toggle Offence select field based on Offence Type
    const offenceType = document.getElementById("offence_type");
    const offenceSelectField = document.getElementById("offence_select_field");
    offenceType.addEventListener("change", function() {
        if (this.value === "fine") {
            offenceSelectField.style.display = "flex";
        } else {
            offenceSelectField.style.display = "none";
        }
    });

    // Update fine amount when an offence is selected
    const offenceDropdown = document.getElementById("offence");
    const fineAmountInput = document.getElementById("fine_amount");
    offenceDropdown.addEventListener("change", function() {
        const selectedOption = offenceDropdown.options[offenceDropdown.selectedIndex];
        const fineAmount = selectedOption.getAttribute("data-fine") || 0;
        fineAmountInput.value = fineAmount;
    });
</script>

<?php include_once "../../../includes/footer.php" ?>