<?php
$pageConfig = [
    'title' => 'Admin Dashboard',
    'styles' => ["https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css", "../dashboard.css", "./admin-dashboard.css"],
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
                        <i class="fas fa-car" style="font-size: 1.7rem;"></i>
                    </div>
                    <div class="info">
                        <p>Drivers</p>
                        <h3><?= $totalDrivers ?></h3>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="icon" style="background-color: #CDE4FF;">
                        <i class="fas fa-user-shield" style="font-size: 1.7rem;"></i>
                    </div>
                    <div class="info">
                        <p>Police Officers</p>
                        <h3><?= $totalOfficers ?></h3>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="icon" style="background-color: #F8C8D8;">
                        <i class="fas fa-car-crash" style="font-size: 1.7rem;"></i>
                    </div>
                    <div class="info">
                        <p>Stolen Vehicles</p>
                        <h3><?= $totalStolenVehicles ?></h3>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="icon" style="background-color: #D5F2EA;">
                        <i class="fas fa-rupee-sign" style="font-size: 1.7rem;"></i>
                    </div>
                    <div class="info">
                        <p>Total Fines</p>
                        <h3>Rs <?= number_format($totalFineAmount, 2) ?></h3>
                    </div>
                </div>
            </div>
            <style>
                .chart-container {
                    display: grid;
                    grid-template-columns: repeat(2, 1fr);
                    gap: 20px;
                    margin-top: 30px;
                    padding: 20px;
                }

                .chart-card {
                    background: white;
                    border-radius: 10px;
                    padding: 20px;
                    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
                    max-width: 500px;
                    height: 300px;
                }

                .chart-title {
                    font-size: 1.1rem;
                    color: #333;
                    margin-bottom: 15px;
                    font-weight: 500;
                }

                canvas {
                    max-height: 240px !important;
                }

                .chart-container {
                    display: grid;
                    grid-template-columns: repeat(2, 1fr);
                    gap: 20px;
                    margin-top: 30px;
                    padding: 20px;
                }

                .chart-card {
                    background: white;
                    border-radius: 10px;
                    padding: 20px;
                    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
                }

                .chart-title {
                    font-size: 1.1rem;
                    color: #333;
                    margin-bottom: 15px;
                    font-weight: 500;
                }
            </style>

            <div class="chart-container">
                <div class="chart-card">
                    <h3 class="chart-title">Monthly Fines Distribution</h3>
                    <canvas id="finesChart"></canvas>
                </div>
                <div class="chart-card">
                    <h3 class="chart-title">User Distribution</h3>
                    <canvas id="usersChart"></canvas>
                </div>
                <div class="chart-card">
                    <h3 class="chart-title">Vehicle Status</h3>
                    <canvas id="vehicleChart"></canvas>
                </div>
                <div class="chart-card">
                    <h3 class="chart-title">Fine Collection Trend</h3>
                    <canvas id="trendChart"></canvas>
                </div>

                <div class="chart-card">
                    <h3 class="chart-title">License Status</h3>
                    <canvas id="licenseChart"></canvas>
                </div>
                <div class="chart-card">
                    <h3 class="chart-title">Fine Payment Status</h3>
                    <canvas id="fineStatusChart"></canvas>
                </div>
            </div>
        </div>
    </div>

</main>

<script>
    const finesChart = new Chart(document.getElementById('finesChart'), {
        type: 'bar',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            datasets: [{
                label: 'Fine Amount (Rs)',
                data: [12000, 19000, 15000, 25000, 22000, 30000],
                backgroundColor: '#0284c7'
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                }
            }
        }
    });

    const usersChart = new Chart(document.getElementById('usersChart'), {
        type: 'doughnut',
        data: {
            labels: ['Drivers', 'Officers', 'Admins'],
            datasets: [{
                data: [<?= $totalDrivers ?>, <?= $totalOfficers ?>, 1],
                backgroundColor: ['#FFC107', '#2196F3', '#FF5722']
            }]
        }
    });

    const vehicleChart = new Chart(document.getElementById('vehicleChart'), {
        type: 'pie',
        data: {
            labels: ['Regular Vehicles', 'Stolen Vehicles'],
            datasets: [{
                data: [<?= $totalDrivers - $totalStolenVehicles ?>, <?= $totalStolenVehicles ?>],
                backgroundColor: ['#2196F3', '#FF5722']
            }]
        }
    });

    const trendChart = new Chart(document.getElementById('trendChart'), {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            datasets: [{
                label: 'Collection Trend',
                data: [5000, 15000, 20000, 18000, 25000, <?= $totalFineAmount ?>],
                borderColor: '#2196F3',
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                }
            }
        }
    });

    // License Status Chart
    const licenseChart = new Chart(document.getElementById('licenseChart'), {
        type: 'doughnut',
        data: {
            labels: ['Active License', 'Suspended License'],
            datasets: [{
                data: [80, 20], // Replace with actual data
                backgroundColor: ['#4CAF50', '#FF5722']
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                }
            }
        }
    });

    // Fine Payment Status Chart
    const fineStatusChart = new Chart(document.getElementById('fineStatusChart'), {
        type: 'pie',
        data: {
            labels: ['Paid Fines', 'Overdue Fines'],
            datasets: [{
                data: [65, 35], // Replace with actual data
                backgroundColor: ['#2196F3', '#f44336']
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                }
            }
        }
    });
</script>
<?php include_once "../../includes/footer.php"; ?>