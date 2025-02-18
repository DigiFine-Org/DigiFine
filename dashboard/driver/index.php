<?php
$pageConfig = [
    'title' => 'Dashboard',
    'styles' => ["../dashboard.css", "./driver-dashboard.css"],
    'scripts' => ["../dashboard.js"],
    'authRequired' => true
];

include_once "../../includes/header.php";
require_once "../../db/connect.php";

if ($_SESSION['user']['role'] !== 'driver') {
    die("unauthorized user!");
}

$driverId = $_SESSION['user']['id'] ?? null;

if (!$driverId) {
    die("Unauthorized access.");
}

// Fetch driver's details (Name and Points)
$sql = "SELECT fname, lname, points FROM drivers WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $driverId);

if (!$stmt->execute()) {
    die("Error fetching driver details: " . $stmt->error);
}

$result = $stmt->get_result();
if ($result->num_rows === 0) {
    die("Driver not found.");
}

$driver = $result->fetch_assoc();
$stmt->close();

// Fetch Active Fines Count
$sql = "SELECT COUNT(*) AS active_fines FROM fines WHERE driver_id = ? AND fine_status = 'pending' AND is_reported = 0 AND is_discarded = 0";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $driverId);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$active_fines = $row['active_fines'] ?? 0; 

$stmt->close();

$sql = "SELECT COUNT(*) AS reported_fines FROM fines WHERE driver_id = ?  AND is_reported = 1 ";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $driverId);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$reported_fines = $row['reported_fines'] ?? 0; 

$stmt->close();

$sql = "SELECT COUNT(*) AS cleared_fines FROM fines WHERE driver_id = ?  AND fine_status = 'paid'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $driverId);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$cleared_fines = $row['cleared_fines'] ?? 0; 

$stmt->close();
$conn->close();
?>

<main>
    <?php include_once "../includes/navbar.php" ?>
    <div class="dashboard-layout">
        <?php include_once "../includes/sidebar.php" ?>
        <div class="content">
            <h2>Welcome Driver <?= htmlspecialchars($driver['fname'] . ' ' . $driver['lname']) ?>!</h2>
            <p>A responsible driver is a true road hero.</p>
            <div class="insights-bar" style="margin-bottom:20px">
                <div class="inner-tile">
                    <div class="icon" style="background-color: #FFEFB4;">
                    </div>
                    <div class="info">
                        <p>Driving Points</p>
                        <h3><?= htmlspecialchars($driver['points']) ?></h3> 
                    </div>
                </div>
                <div class="inner-tile">
                    <div class="icon" style="background-color: #CDE4FF;">
                    </div>
                    <div class="info">
                        <p>Active Fines</p>
                        <h3><?= $active_fines; ?></h3> <!-- Dynamically display count -->
                    </div>
                </div>
                <div class="inner-tile">
                    <div class="icon" style="background-color: #F8C8D8;">
                    </div>
                    <div class="info">
                        <p>Reported Fines</p>
                        <h3><?= $reported_fines; ?></h3>
                    </div>
                </div>
                <div class="inner-tile">
                    <div class="icon" style="background-color: #D5F2EA;">
                    </div>
                    <div class="info">
                        <p>Cleared Fines</p>
                        <h3><?= $cleared_fines; ?></h3>
                    </div>
                </div>
            </div>
            
            <div class="navigation-tile-grid" style="margin-top: 40px;">
                <a href="/digifine/dashboard/driver/dashboard-links/emergency-services.php">
                    <div class="tile emergency-services">
                        <span>Emergency Services</span>
                    </div>
                </a>
                <a href="/digifine/dashboard/driver/dashboard-links/tips-for-drivers.php">
                    <div class="tile tips-drivers">
                        <span>Tips for Drivers</span>
                    </div>
                </a>
                <a href="/digifine/dashboard/driver/dashboard-links/traffic-signs.php">
                    <div class="tile traffic-signs">
                        <span>Traffic Signs</span>
                    </div>
                </a>
                <a href="/digifine/dashboard/driver/dashboard-links/remaining-points.php">
                    <div class="tile remaining-points">
                        <span>Remaining Points</span>
                    </div>
                </a>
                <a href="/digifine/dashboard/driver/dashboard-links/tell-igp.php">
                    <div class="tile tell-igp">
                        <span>Tell IGP</span>
                    </div>
                </a>
                <a href="/digifine/dashboard/driver/dashboard-links/police-stations.php">
                    <div class="tile police-stations">
                        <span>Police Stations</span>
                    </div>
                </a>
                <a href="/digifine/dashboard/driver/dashboard-links/stolen-vehicles.php">
                    <div class="tile police-stations">
                        <span>Vehicle Stolen?</span>
                    </div>
                </a>
            </div>
        </div>
    </div>
</main>

<?php include_once "../../includes/footer.php" ?>
