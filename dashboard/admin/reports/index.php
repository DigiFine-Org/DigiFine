<?php
$pageConfig = [
    'title' => 'Register Police Officer',
    'styles' => ["../../dashboard.css", "../officer-management/styles.css"],
    'scripts' => ["../../dashboard.js"],
    'authRequired' => true
];

session_start();
include_once "../../../includes/header.php";
require_once "../../../db/connect.php";

?>

<main>
    <?php include_once "../../includes/navbar.php" ?>

    <div class="dashboard-layout">
        <?php include_once "../../includes/sidebar.php" ?>
        <div class="content">
            <div class="">
                <button onclick="history.back()" class="back-btn" style="position: absolute; top: 7px; right: 8px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        viewBox="0 0 16 16">
                        <path fill-rule="evenodd"
                            d="M15 8a.5.5 0 0 1-.5.5H3.707l3.147 3.146a.5.5 0 0 1-.708.708l-4-4a.5.5 0 0 1 0-.708l4-4a.5.5 0 1 1 .708.708L3.707 7.5H14.5a.5.5 0 0 1 .5.5z" />
                    </svg>
                </button>
                <div style="margin-bottom: 50px;">
                    <h1>Reports Dashboard</h1>
                    <p class="description">View and analyze statistics for different time periods.</p>
                </div>
                <div class="feature-tiles">
                    <a href="all-fines/index.php"
                        class="feature-tile">
                        <div class="tile-full">
                            <div class="tile-content">
                                <h3>Analyze All Fines</h3>
                                <p>View and analyze statistics for different time periods</p>
                            </div>
                        </div>
                    </a>
                    <a href="officer-reports/index.php" class="feature-tile">
                        <div class="tile-full">
                            <div class="tile-content">
                                <h3>Analyze by Police Officer</h3>
                                <p>View and analyze statistics for different time periods</p>
                            </div>
                        </div>
                    </a>
                    <a href="police-station-reports/index.php" class="feature-tile">
                        <div class="tile-full">
                            <div class="tile-content">
                                <h3>Analyze by Police Station</h3>
                                <p>View and analyze statistics for different time periods</p>
                            </div>
                        </div>
                    </a>
                    <a href="revenue\index.php" class="feature-tile">
                        <div class="tile-full">
                            <div class="tile-content">
                                <h3>analyze Revenue</h3>
                                <p>View and analyze statistics for different time periods</p>
                            </div>
                        </div>
                    </a>
                    <a href="offence\index.php" class="feature-tile">
                        <div class="tile-full">
                            <div class="tile-content">
                                <h3>Analyze by Offence</h3>
                                <p>View and analyze statistics for different time periods</p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>

        </div>
    </div>
    </div>
</main>


<?php include_once "../../../includes/footer.php"; ?>