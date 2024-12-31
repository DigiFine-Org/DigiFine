<?php

$pageConfig = [
    'title' => 'Check Vehicle Details',
    'styles' => ["./stolen-form.css"],
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

// Get license plate number
$license_plate_number = isset($_GET['license_plate_number']) ? htmlspecialchars($_GET['license_plate_number']) : '';

if (!$license_plate_number) {
    die("No license plate number provided!");
}

// Fetch vehicle details
$sql = "SELECT * FROM dmt_vehicles WHERE license_plate_number = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $license_plate_number);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Vehicle not found!");
}

$vehicle = $result->fetch_assoc();
?>

<main>
    <?php include_once "../../includes/navbar.php"; ?>
    <div class="dashboard-layout">
        <?php include_once "../../includes/sidebar.php"; ?>
        <div class="content">
            <img class="watermark" src="../../../assets/watermark.png" />
            <div class="container">
                <h1>Stolen Vehicle Details</h1>
                <p>License Plate: <?php echo htmlspecialchars($vehicle['license_plate_number']); ?></p>
                <p>Vehicle Type: <?php echo htmlspecialchars($vehicle['vehicle_type']); ?></p>
                <p>Owner: <?php echo htmlspecialchars($vehicle['vehicle_owner_fname'] . ' ' . $vehicle['vehicle_owner_lname']); ?></p>

                <form method="POST" action="/digifine/dashboard/officer/check-vehicle-details/handle-stolen.php">
                    <input type="hidden" name="license_plate_number" value="<?php echo htmlspecialchars($vehicle['license_plate_number']); ?>">
                    <label for="action">Action:</label>
                    <select name="action" id="action">
                        <option value="report">Report to Police</option>
                        <option value="release">Mark as Found</option>
                    </select>
                    <button type="submit">Submit</button>
                </form>
            </div>
        </div>
    </div>
</main>
