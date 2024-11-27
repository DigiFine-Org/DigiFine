<?php
$pageConfig = [
    'title' => 'Payments',
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

// fetch driver's nameS
$sql = "SELECT fname, lname FROM drivers WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $driverId);

if (!$stmt->execute()) {
    die("Error fetching officer details " . $stmt->error);
}

$result = $stmt->get_result();
if ($result->num_rows === 0) {
    die("Driver not found.");
}

$driver = $result->fetch_assoc();
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
            <div class="navigation-tile-grid" style="margin-top: 40px;">
                <div class="tile emergency-services">
                    <span>Emergency Services</span>
                </div>
                <div class="tile tips-drivers">
                    <span>Tips For Drivers</span>
                </div>
                <div class="tile traffic-signs">
                    <span>Traffic Signs</span>
                </div>
                <div class="tile remaining-points">
                    <span>Remaining Points</span>
                </div>
                <div class="tile tell-igp">
                    <span>Tell IGP</span>
                </div>
                <div class="tile police-stations">
                    <span>Police Stations</span>
                </div>
            </div>
</main>

<?php include_once "../../includes/footer.php" ?>