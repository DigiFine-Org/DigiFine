<?php
$pageConfig = [
    'title' => 'Check Vehicle Details',
    'styles' => ["../../dashboard.css","stolen-form.css"],
    'scripts' => ["../../dashboard.js"],
    'authRequired' => true
];

session_start();
require_once "../../../db/connect.php";
include_once "../../../includes/header.php";

// Check user authentication and role
if ($_SESSION['user']['role'] !== 'officer') {
    die("Unauthorized user!");
}




$license_plate_number = $_GET['license_plate_number'] ?? null;
$officerID = $_SESSION['user']['id'] ?? null;

if (!$license_plate_number) {
    die("No license plate number provided!");
}

// Fetch officer details
$sqlOfficer = "SELECT id , CONCAT(fname, ' ', lname) AS full_name FROM officers WHERE id=?";
$stmt = $conn->prepare($sqlOfficer);
$stmt->bind_param("i", $officerID);
$stmt->execute();
$result = $stmt->get_result();
$officer = $result->fetch_assoc();
$stmt->close();

// Fetch vehicle info
$sql = "SELECT sv.*, CONCAT(dv.vehicle_owner_fname, ' ', dv.vehicle_owner_lname) AS full_name 
        FROM stolen_vehicles sv 
        JOIN dmt_vehicles dv ON dv.license_plate_number = sv.license_plate_number 
        WHERE sv.license_plate_number=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $license_plate_number);
$stmt->execute();
$result = $stmt->get_result();
$vehicle = $result->fetch_assoc();
$stmt->close();

$error = "";
$popupMessage = "";
$popupSuccess = true;

// Form submit logic
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $license_plate_number = $_POST['license_plate_number'] ?? '';
    $seizure_date_time = $_POST['seizure_date_time'] ?? '';
    $seized_location = $_POST['seized_location'] ?? '';
    $officer_id = $_POST['officer_id'] ?? '';
    $officer_name = $_POST['officer_name'] ?? '';
    $police_station = $_POST['police_station'] ?? '';
    $driver_NIC = $_POST['driver_NIC'] ?? '';
    $owner_name = $_POST['owner_name'] ?? '';

    // Validate required fields
    if (
        empty($license_plate_number) || empty($seizure_date_time) || empty($seized_location) ||
        empty($officer_id) || empty($officer_name) || empty($police_station) || empty($driver_NIC) || empty($owner_name)
    ) {
        $popupMessage = "All fields are required!";
        $popupSuccess = false;
    } else {
        $sql = "INSERT INTO seized_vehicle 
                (license_plate_number, seizure_date_time, seized_location, officer_id, officer_name, police_station, driver_NIC, owner_name) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("ssssssss", 
                $license_plate_number,
                $seizure_date_time,
                $seized_location,
                $officer_id,
                $officer_name,
                $police_station,
                $driver_NIC,
                $owner_name
            );

            if ($stmt->execute()) {
                // Update dmt_vehicles
                $updateSql = "UPDATE dmt_vehicles SET is_stolen = 0 WHERE license_plate_number = ?";
                $updateStmt = $conn->prepare($updateSql);

                if ($updateStmt) {
                    $updateStmt->bind_param("s", $license_plate_number);
                    if ($updateStmt->execute()) {
                        // Delete from stolen_vehicles
                        $deleteSql = "DELETE FROM stolen_vehicles WHERE license_plate_number = ?";
                        $deleteStmt = $conn->prepare($deleteSql);
                        if ($deleteStmt) {
                            $deleteStmt->bind_param("s", $license_plate_number);
                            $deleteStmt->execute();
                            $deleteStmt->close();
                        }
                        $popupMessage = "Vehicle is now Seized";
                        $popupSuccess = true;

                        // // Redirect with success message
                        // header("Location: index.php?plate=$license_plate_number");
                        // exit();
                    } else {
                        $popupMessage = "Error updating vehicle status: " . $updateStmt->error;
                        $popupSuccess = false;
                    }
                    $updateStmt->close();
                } else {
                    $popupMessage = "Error preparing update statement: " . $conn->error;
                    $popupSuccess = false;
                }
            } else {
                $popupMessage = "Error executing insert: " . $stmt->error;
                $popupSuccess = false;
            }
            $stmt->close();
        } else {
            $popupMessage = "Error preparing insert statement: " . $conn->error;
            $popupSuccess = false;
        }
    }
}
?>

<main>
    <?php include_once "../../includes/navbar.php"; ?>
    <div class="dashboard-layout">
        <?php include_once "../../includes/sidebar.php"; ?>
        <div class="content">
            <div class="container">
                <button onclick="history.back()" class="back-btn" style="position: absolute; top: 7px; right: 8px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        viewBox="0 0 16 16">
                        <path fill-rule="evenodd"
                            d="M15 8a.5.5 0 0 1-.5.5H3.707l3.147 3.146a.5.5 0 0 1-.708.708l-4-4a.5.5 0 0 1 0-.708l4-4a.5.5 0 1 1 .708.708L3.707 7.5H14.5a.5.5 0 0 1 .5.5z" />
                    </svg>
                </button>
                <h1>Seizing the Vehicle <?= htmlspecialchars($vehicle['license_plate_number']) ?></h1>

            <form method="POST" action="" class="seizure-form">
    <div class="form-row">
        <div class="form-group">
            <label class="form-label">License Plate Number</label>
            <input type="text" class="form-control readonly" name="license_plate_number" 
                   value="<?= htmlspecialchars($vehicle['license_plate_number']) ?>" readonly>
        </div>
        
        <div class="form-group">
            <label class="form-label">Owner Name</label>
            <input type="text" class="form-control readonly" name="owner_name" 
                   value="<?= htmlspecialchars($vehicle['full_name']) ?>" readonly>
        </div>
    </div>

    <div class="form-row">
        <div class="form-group">
            <label class="form-label">Seizure Date & Time <span class="required">*</span></label>
            <input type="datetime-local" class="form-control" id="seizure-date-time" 
                   name="seizure_date_time" required>
        </div>
        
        <div class="form-group">
            <label class="form-label">Seizure Location <span class="required">*</span></label>
            <input type="text" class="form-control" name="seized_location" 
                   placeholder="Street name, City" required>
        </div>
    </div>

    <div class="form-row">
        <div class="form-group">
            <label class="form-label">Officer ID</label>
            <input type="text" class="form-control readonly" name="officer_id" 
                   value="<?= htmlspecialchars($officer['id']) ?>" readonly>
        </div>
        
        <div class="form-group">
            <label class="form-label">Officer Name</label>
            <input type="text" class="form-control readonly" name="officer_name" 
                   value="<?= htmlspecialchars($officer['full_name']) ?>" readonly>
        </div>
    </div>

    <div class="form-row">
    <div class="form-group">
    <label class="form-label">Police Station <span class="required">*</span></label>
    <select id="police_station" name="police_station" class="form-control select2-dropdown" required>
        <option value="">Select Police Station</option>
        <?php
        $stationQuery = "SELECT id, name FROM police_stations ORDER BY name";
        $stationStmt = $conn->prepare($stationQuery);
        $stationStmt->execute();
        $stationResult = $stationStmt->get_result();
        
        while($station = $stationResult->fetch_assoc()): ?>
            <option value="<?= htmlspecialchars($station['id']) ?>"
                <?php if(isset($officer['police_station']) && $officer['police_station'] == $station['id']) echo 'selected'; ?>>
                <?= htmlspecialchars($station['name']) ?>
            </option>
        <?php endwhile; ?>
    </select>
</div>
    </div>
        
        <div class="form-group">
            <label class="form-label">Driver NIC <span class="required">*</span></label>
            <input type="text" class="form-control" name="driver_NIC" 
                   placeholder="NIC Number" required>
        </div>

        <div class="form-actions">
        <button type="submit" class="btn btn-primary">
            Seize Vehicle
        </button>
        <a href="index.php" class="btn btn-secondary">
           Cancel
        </a>
    </div>
    </div>

    
</form>

            <script>
                document.getElementById('seizure-date-time').value = new Date().toISOString().slice(0, 16);
            </script>
        </div>
    </div>
</div>      
</main>

<div id="popupNew" class="popupNew">
    <div class="popup-contentNew">
        <span id="popup-closeNew" class="popup-closeNew">&times;</span>
        <div id="popupIconNew" class="popup-icon"></div>
        <h2 id="popupTitleNew"></h2>
        <p id="popupTextNew"></p>
        <button id="popupButtonNew" class="popup-button">OK</button>
    </div>
</div>

<script>

    

    function showPopup(message, isSuccess = true) {
        const popup = document.getElementById('popupNew');
        // const icon = document.getElementById('popupIconNew');
        const titleEl = document.getElementById('popupTitleNew');
        const textEl = document.getElementById('popupTextNew');
        const button = document.getElementById('popupButtonNew');

    // icon.innerHTML = isSuccess ? '✅' : '❌';
    // icon.style.color = isSuccess ? '#28a745' : '#dc3545';
    titleEl.textContent = isSuccess ? 'Success!' : 'Error!';
    button.style.backgroundColor = '#003366';
    textEl.textContent = message;
    popup.style.display = 'flex';

    button.onclick = null;
    
    // Set up new click handler
    button.onclick = function() {
        closePopup();
        if (isSuccess) {
            window.location.href = 'index.php';
        }
    };
}



    function closePopup() {
        document.getElementById('popupNew').style.display = 'none';
    }

    document.getElementById('popup-closeNew').addEventListener('click', closePopup);
    document.getElementById('popupButtonNew').addEventListener('click', closePopup);
    window.addEventListener('click', function (event) {
        if (event.target === document.getElementById('popupNew')) {
            closePopup();
        }
    });

    <?php if (!empty($popupMessage)): ?>
        document.addEventListener('DOMContentLoaded', function () {
            showPopup("<?= htmlspecialchars($popupMessage, ENT_QUOTES) ?>", <?= $popupSuccess ? 'true' : 'false' ?>);
        });
    <?php endif; ?>

    $(document).ready(function() {
    // Initialize Select2 with search functionality
    $('.select2-dropdown').select2({
        placeholder: "Type to search police stations...",
        allowClear: true,
        width: '100%',
        minimumInputLength: 1, // Start searching after 1 character
        matcher: function(params, data) {
            // If there are no search terms, return all of the data
            if ($.trim(params.term) === '') {
                return data;
            }
            
            // Do a case-insensitive search
            if (data.text.toLowerCase().indexOf(params.term.toLowerCase()) > -1) {
                return data;
            }
            
            // Return null if the term doesn't match
            return null;
        }
    });
    
    // Set default police station if available
    <?php if(isset($officer['police_station'])): ?>
        $('#police_station').val('<?= $officer['police_station'] ?>').trigger('change');
    <?php endif; ?>
});
</script>


<style>

    
    /* Main Form Container */

/* Add this to your header for icons */
/* <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"> */
</style>