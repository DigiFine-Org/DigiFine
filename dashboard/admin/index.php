<?php
$pageConfig = [
    'title' => 'Admin Dashboard',
    'styles' => ["../dashboard.css", "./admin-dashboard.css"],
    'scripts' => ["../dashboard.js"],
    'authRequired' => true
];

include_once "../../includes/header.php";
require_once "../../db/connect.php";

if ($_SESSION['user']['role'] !== 'admin') {
    die("unauthorized user!");
}

// SELECT TOTAL DRIVERS
$sql_total_drivers = "SELECT COUNT(*) as total_drivers FROM drivers ";
$stmt = $conn->prepare($sql_total_drivers);
$stmt->execute();
$result_drivers = $stmt->get_result();

$totalDrivers = 0;
if ($row = $result_drivers->fetch_assoc()) {
    $totalDrivers = $row['total_drivers'];
}

$stmt->close();

// SELECT TOTAL POLICE OFFICERS
$sql_total_officers = "SELECT COUNT(*) as total_officers FROM officers ";
$stmt = $conn->prepare($sql_total_officers);
$stmt->execute();
$result_officers = $stmt->get_result();

$totalOfficers = 0;
if ($row = $result_officers->fetch_assoc()) {
    $totalOfficers = $row['total_officers'];
}

$stmt->close();

// SELECT TOTAL STOLEN VEHICLES
$sql_total_stolen_vehicles = "SELECT COUNT(*) as total_stolen_vehicles FROM stolen_vehicles ";
$stmt = $conn->prepare($sql_total_stolen_vehicles);
$stmt->execute();
$result_stolen_vehicles = $stmt->get_result();

$totalStolenVehicles = 0;
if ($row = $result_stolen_vehicles->fetch_assoc()) {
    $totalStolenVehicles = $row['total_stolen_vehicles'];
}

$stmt->close();


// SELECT TOTAL FINES

$sql_total_fine_amount = "SELECT SUM(fine_amount) as total_fine_amount FROM fines WHERE is_discarded = 0";
$stmt = $conn->prepare($sql_total_fine_amount);
$stmt->execute();
$result_fine_amount = $stmt->get_result();

$totalFineAmount = 0;
if ($row = $result_fine_amount->fetch_assoc()) {
    $totalFineAmount = $row['total_fine_amount'] ?? 0;
}

$stmt->close();


?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />

<main>
    <?php include_once "../includes/navbar.php" ?>

    <div class="dashboard-layout">
        <?php include_once "../includes/sidebar.php" ?>
        <div class="content">
            <button onclick="history.back()" class="back-btn" style="position: absolute; top: 7px; right: 8px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                    <path fill-rule="evenodd"
                        d="M15 8a.5.5 0 0 1-.5.5H3.707l3.147 3.146a.5.5 0 0 1-.708.708l-4-4a.5.5 0 0 1 0-.708l4-4a.5.5 0 1 1 .708.708L3.707 7.5H14.5a.5.5 0 0 1 .5.5z" />
                </svg>
            </button>
            <h2>Administering for Excellence and Security</h2>
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="icon" style="background-color: #FFEFB4;">
                        <span class="material-symbols-outlined" style="font-size: 36px;">directions_car</span>
                    </div>
                    <div class="info">
                        <p>Drivers</p>
                        <h3><?= $totalDrivers ?></h3>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="icon" style="background-color: #CDE4FF;">
                        <span class="material-symbols-outlined" style="font-size: 36px;">badge</span>
                    </div>
                    <div class="info">
                        <p>Police Officers</p>
                        <h3><?= $totalOfficers ?></h3>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="icon" style="background-color: #F8C8D8;">
                        <span class="material-symbols-outlined" style="font-size: 36px;">report_problem</span>
                    </div>
                    <div class="info">
                        <p>Stolen Vehicles</p>
                        <h3><?= $totalStolenVehicles ?></h3>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="icon" style="background-color: #D5F2EA;">
                        <span class="material-symbols-outlined" style="font-size: 36px;">currency_rupee</span>
                    </div>
                    <div class="info">
                        <p>Total Fines</p>
                        <h3>Rs <?= number_format($totalFineAmount, 2) ?></h3>
                    </div>
                </div>
            </div>

            <div class="charts-bar" style="margin-top: 75px;">
                <div class="inner-chart">
                    <canvas id="myChart"></canvas>
                </div>
                <div class="inner-chart">
                    <canvas id="myChart-1"></canvas>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
    const ctx = document.getElementById('myChart');

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'April', 'May', 'June', 'July', 'August', 'Sept', 'Oct', 'Nov', 'Dec'],
            datasets: [{
                label: ' Monthly Driver Registrations',
                data: [121, 58, 45, 145, 23, 67, 73, 95, 100, 36, 78, 60],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>

<script>
    const cty = document.getElementById('myChart-1');

    new Chart(cty, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'April', 'May', 'June', 'July', 'August', 'Sept', 'Oct', 'Nov', 'Dec'],
            datasets: [{
                label: ' Monthly Fines Issued',
                data: [1321, 2558, 4645, 4145, 1523, 5667, 8673, 6695, 6100, 4364, 4578, 4560],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>

<?php include_once "../../includes/footer.php"; ?>