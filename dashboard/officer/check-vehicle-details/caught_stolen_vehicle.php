<?php

$pageConfig = [
    'title' => 'Check Vehicle Details',
    'styles' => ["../../dashboard.css"],
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

$officerID = isset($_SESSION['user']['id']) ? $_SESSION['user']['id'] : null;

if (!$license_plate_number) {
    die("No license plate number provided!");
}

$sqlOfficer= "SELECT id , CONCAT(fname, ' ', lname) AS full_name FROM officers WHERE id=?";
$stmt=$conn->prepare($sqlOfficer);
$stmt->bind_param("i",$officerID);
$stmt->execute();
$result=$stmt->get_result();

$officer =$result->fetch_assoc();

$stmt->close();



$sql = "SELECT sv.* ,CONCAT(dv.vehicle_owner_fname, ' ', dv.vehicle_owner_lname) AS full_name FROM stolen_vehicles sv JOIN dmt_vehicles dv ON dv.license_plate_number= sv.license_plate_number WHERE sv.license_plate_number=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $license_plate_number);
$stmt->execute();
$result = $stmt->get_result();

$vehicle = $result->fetch_assoc();
?>

<main>
<?php include_once "../../includes/navbar.php"; ?>
    <div class="dashboard-layout">
        <?php include_once "../../includes/sidebar.php"; ?>
        <div class="content">
                <div class="container">
                <h1>Seizing the Vehicle <?php echo htmlspecialchars($vehicle['license_plate_number']); ?></h1>

                
                
                <form method="POST" action="process_seizure.php">
                    <div class="field">
                        <label for="license_plate_number">License Plate Number:</label>
                        <input type="text" name="license_plate_number" value="<?= htmlspecialchars($vehicle['license_plate_number']); ?>" readonly>
                    </div>
                    <div class="field">
                        <label for="seizure-date-time">Seizure Date & Time:</label>
                        <input type="datetime-local" id="seizure-date-time" name="seizure_date_time" required readonly>
                    </div>
                    <div class="field">
                        <label for="seized-location">Seizure Location:</label>
                        <input type="text" id="seized-location" name="seized-location" placeholder="Street name, City" required>
                    </div>
                    <div class="field">
                        <label for="officer_id">Officer ID:</label>
                        <input type="text" id="officer_id" name="officer_id" value="<?= htmlspecialchars($officer['id']); ?>" readonly>
                    </div>
                    <div class="field">
                        <label for="officer_name">Officer Name:</label>
                        <input type="text" id="officer_name" name="officer_name" value="<?= htmlspecialchars($officer['full_name']); ?>" readonly>
                    </div>
                    <div class="field">
                        <label for="police_station">Police Station:</label>
                        <input type="text" id="police_station" name="police_station" placeholder="Police station the vehicle going to be in" required>
                    </div>
                    <div class="field">
                        <label for="driver_NIC">Driver NIC:</label>
                        <input type="text" id="driver_NIC" name="driver_NIC" placeholder="xxxxxxxxxxxx" required>
                    </div>
                    <div class="field">
                        <label for="owner_name">Owner Name:</label>
                        <input type="text" id="owner_name" name="owner_name" value="<?=htmlspecialchars($vehicle['full_name']);?>" readonly>
                    </div>
                    <div class="field">
                        <button class="btn margintop" type="submit">Seize</button>
                    </div>
                </form>

                <script>
                    // Set default seizure date and time to current date and time
                    document.getElementById('seizure-date-time').value = new Date().toISOString().slice(0, 16);
                </script>
                </div>
        </div>
    </div>  
</main>