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
                    <a href="officer-reports/issued-fines/index.php" class="btn">analyze by officer</a>
                </div>

</body>