<?php
$pageConfig = [
    'title' => 'Reports Dashboard',
    'styles' => ["../../dashboard.css", "../reports.css"],
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
                <button onclick="history.back()" class="back-btn" style="position: absolute; top: 7px; right: 8px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        viewBox="0 0 16 16">
                        <path fill-rule="evenodd"
                            d="M15 8a.5.5 0 0 1-.5.5H3.707l3.147 3.146a.5.5 0 0 1-.708.708l-4-4a.5.5 0 0 1 0-.708l4-4a.5.5 0 1 1 .708.708L3.707 7.5H14.5a.5.5 0 0 1 .5.5z" />
                    </svg>
                </button>
                <h1>Analytics</h1>
                <p class="description">View and analyze statistics for different time periods.</p>


                <div class="filter-field">
                    <a href="all-fines/index.php" class="btn">analyze all fines</a>
                </div>
                <!-- <div class="filter-field">
                    <a href="location-reports\issued-place\index.php" class="btn">analyze by location</a>
                </div> -->
                <!-- <div class="filter-field">
                    <a href="location-reports\issued-police-station\index.php" class="btn">issued police station</a>
                </div> -->
                <br>
                <div class="filter-field">
                    <a href="officer-reports/issued-reported/index.php" class="btn">analyze by officer</a>
                </div>
                <!-- <div class="filter-field">
                    <a href="all-fines\fine-status\index.php" class="btn">analyze by fine status</a>
                </div> -->
                <div class="filter-field">
                    <a href="police-station-reports/index.php" class="btn">analyze by police station</a>
                </div>
                <div class="filter-field">
                    <a href="revenue\index.php" class="btn">analyze Revenue</a>
                </div>

                <div class="filter-field">
                    <a href="offence\index.php" class="btn">analyze by Offence</a>
                </div>
</body>