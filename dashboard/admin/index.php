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

?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />

<main>
    <?php include_once "../includes/navbar.php" ?>

    <div class="dashboard-layout">
        <?php include_once "../includes/sidebar.php" ?>
        <div class="content">
            <h2>Administering for Excellence and Security</h2>
            <div class="insights-bar">
                <div class="inner-tile">
                    <div class="icon" style="background-color: #FFEFB4;">
                        <span class="material-symbols-outlined" style="font-size: 36px;">directions_car</span>
                    </div>
                    <div class="info">
                        <p>Drivers</p>
                        <h3>5484</h3>
                    </div>
                </div>
                <div class="inner-tile">
                    <div class="icon" style="background-color: #CDE4FF;">
                        <span class="material-symbols-outlined" style="font-size: 36px;">badge</span>
                    </div>
                    <div class="info">
                        <p>Police Officers</p>
                        <h3>2489</h3>
                    </div>
                </div>
                <div class="inner-tile">
                    <div class="icon" style="background-color: #F8C8D8;">
                        <span class="material-symbols-outlined" style="font-size: 36px;">report_problem</span>
                    </div>
                    <div class="info">
                        <p>Stolen Vehicles</p>
                        <h3>15</h3>
                    </div>
                </div>
                <div class="inner-tile">
                    <div class="icon" style="background-color: #D5F2EA;">
                        <span class="material-symbols-outlined" style="font-size: 36px;">currency_rupee</span>
                    </div>
                    <div class="info">
                        <p>Total Fines</p>
                        <h3>Rs 164,920</h3>
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