<?php
$pageConfig = [
    'title' => 'Officer Dashboard',
    'styles' => ["../dashboard.css", "./officer-dashboard.css"],
    'scripts' => ["../dashboard.js"],
    'authRequired' => true
];

include_once "../../includes/header.php";
require_once "../../db/connect.php";



if ($_SESSION['user']['role'] !== 'officer') {
    die("unauthorized user!");
}

$policeId = $_SESSION['user']['id'] ?? null;

if (!$policeId) {
    die("Unauthorized access.");
}

// fetch officer's nameS
$sql = "SELECT o.fname, o.lname, ps.name AS police_station_name 
    FROM officers o 
    INNER JOIN police_stations ps ON o.police_station = ps.id 
    WHERE o.id = ? ";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $policeId);

if (!$stmt->execute()) {
    die("Error fetching officer details " . $stmt->error);
}

$result = $stmt->get_result();
if ($result->num_rows === 0) {
    die("Officer not found.");
}

$officer = $result->fetch_assoc();
$stmt->close();
$conn->close();

?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<main>
    <?php include_once "../includes/navbar.php" ?>
    <div class="dashboard-layout">
        <?php include_once "../includes/sidebar.php" ?>
        <div class="content">
            <h2>Welcome Officer <?= htmlspecialchars($officer['fname'] . ' ' . $officer['lname']) ?>!</h2>
            <p>Police Station: <?= htmlspecialchars($officer['police_station_name']) ?></p>
            <div class="insights-bar">
                <div class="inner-tile">
                    <div class="icon" style="background-color: #FFEFB4;">
                        <!-- <img src="driver-icon.svg" alt="Driver Icon"> -->
                    </div>
                    <div class="info">
                        <p>Duty Submissions</p>
                        <h3>248</h3>
                    </div>
                </div>
                <div class="inner-tile">
                    <div class="icon" style="background-color: #CDE4FF;">
                        <!-- <img src="officer-icon.svg" alt="Officer Icon"> -->
                    </div>
                    <div class="info">
                        <p>Fines Issued</p>
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
                        <p>Duty Assignments</p>
                        <h3>20</h3>
                    </div>
                </div>
            </div>
            <div class="navigation-tile-grid">
                <div class="tile offence-list">
                    <span>Offence List</span>
                </div>
                <div class="tile resources">
                    <span>Resources</span>
                </div>
                <div class="tile training">
                    <span>Training & Notices</span>
                </div>
                <div class="tile stolen-vehicles">
                    <span>Stolen Vehicles</span>
                </div>
                <div class="tile dummy">
                    <span>Dummy</span>
                </div>
                <div class="tile dummy">
                    <span>Dummy</span>
                </div>
            </div>
        </div>
    </div>
</main>




<?php include_once "../../includes/footer.php"; ?>