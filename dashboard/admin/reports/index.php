<?php
$pageConfig = [
    'title' => 'Reports Dashboard',
    'styles' => ["../../dashboard.css", "./reports.css"],
    'scripts' => ["../reports.js"],
    'authRequired' => true
];

session_start();
include_once "../../../includes/header.php";
require_once "../../../db/connect.php";

if ($_SESSION['user']['role'] !== 'admin') {
    die("Unauthorized user!");
}
?>

<body>
    <main>
        <!-- Navbar -->
        <?php include_once "../../includes/navbar.php"; ?>

        <div class="dashboard-layout">
            <!-- Sidebar -->
            <?php include_once "../../includes/sidebar.php"; ?>

            <!-- Main Content -->
            <div class="content">
                <h1>Statistics</h1>
                <p class="description">View and analyze statistics for different time periods.</p>


                <div class="filter-field">
                    <a href="all-fines/issued-fines/index.php" class="btn">analyze all fines</a>
                </div>
                <div class="filter-field">
                    <a href="location-reports\issued-place\index.php" class="btn">analyze by location</a>
                </div>
                <div class="filter-field">
                    <a href="location-reports\issued-police-station\index.php" class="btn">issued police station</a>
                </div>
                <br>
                <div class="filter-field">
                    <a href="officer-reports/issued-reported/index.php" class="btn">analyze by officer</a>
                </div>
                <div class="filter-field">
                    <a href="all-fines\fine-status\index.php" class="btn">analyze by fine status</a>
                </div>
                <div class="filter-field">
                    <a href="police-station-reports/index.php" class="btn">analyze by police station</a>
                </div>
                <div class="filter-field">
                    <a href="all-fines\revenue\index.php" class="btn">analyze Revenue</a>
                </div>

                <div class="filter-field">
                    <a href="all-fines\offence\index.php" class="btn">analyze by Offence</a>
                </div>
</body>