<?php
$pageConfig = [
    'title' => 'OIC Dashboard',
    'styles' => ["../dashboard.css", "oic-dashboard.css"],
    'scripts' => ["../dashboard.js"],
    'authRequired' => true
];

require_once "../../db/connect.php";
include_once "../../includes/header.php";



if ($_SESSION['user']['role'] !== 'oic') {
    die("unauthorized user!");
}

$driverId = $_SESSION['user']['id'] ?? null;

if (!$driverId) {
    die("Unauthorized access.");
}

// fetch officer's nameS
$sql = "SELECT o.fname, o.lname, ps.name AS police_station_name 
    FROM officers o 
    INNER JOIN police_stations ps ON o.police_station = ps.id 
    WHERE o.id = ? ";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $driverId);

if (!$stmt->execute()) {
    die("Error fetching officer details " . $stmt->error);
}

$result = $stmt->get_result();
if ($result->num_rows === 0) {
    die("Officer not found.");
}

$oic = $result->fetch_assoc();
$stmt->close();
$conn->close();

?>

<main>
    <?php include_once "../includes/navbar.php" ?>

    <div class="dashboard-layout">
        <?php include_once "../includes/sidebar.php" ?>
        <div class="content">
            <h2>Welcome OIC <?= htmlspecialchars($oic['fname'] . ' ' . $oic['lname']) ?>!</h2>
            <p>Police Station: <?= htmlspecialchars($oic['police_station_name']) ?></p>
            <div class="insights-bar">
                <div class="inner-tile">
                    <div class="icon" style="background-color: #FFEFB4;">
                        <!-- <img src="driver-icon.svg" alt="Driver Icon"> -->
                    </div>
                    <div class="info">
                        <p>Station Police Officers</p>
                        <h3>248</h3>
                    </div>
                </div>
                <div class="inner-tile">
                    <div class="icon" style="background-color: #CDE4FF;">
                        <!-- <img src="officer-icon.svg" alt="Officer Icon"> -->
                    </div>
                    <div class="info">
                        <p>Pending Fines</p>
                        <h3>56</h3>
                    </div>
                </div>
                <div class="inner-tile">
                    <div class="icon" style="background-color: #F8C8D8;">
                        <!-- <img src="report-icon.svg" alt="Report Icon"> -->
                    </div>
                    <div class="info">
                        <p>Reported Fines</p>
                        <h3>15</h3>
                    </div>
                </div>
                <div class="inner-tile">
                    <div class="icon" style="background-color: #D5F2EA;">
                        <!-- <img src="fines-icon.svg" alt="Fines Icon"> -->
                    </div>
                    <div class="info">
                        <p>Overdue Fines</p>
                        <h3>20</h3>
                    </div>
                </div>

            </div>

        </div>
    </div>
</main>

<?php include_once "../../includes/footer.php"; ?>