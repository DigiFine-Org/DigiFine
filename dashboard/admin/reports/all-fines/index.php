<?php
$pageConfig = [
    'title' => 'Reports Dashboard',
    'styles' => ["../../../dashboard.css", "../reports.css"],
    'scripts' => [
        "../../../dashboard.js",
        "payment-status/fine-status-chart.js",
        "payment-status/fine-status-analytics.js",
        "reported-all/issued-fines-chart.js",
        "reported-all/issued-fines-analytics.js",
        "issued-place/issued-place-chart.js",
        "issued-place/issued-place-analytics.js",
        "issued-police-station/police-station-chart.js",
        "issued-police-station/police-station-analytics.js",

    ],
    'authRequired' => true
];

session_start();
include_once "../../../../includes/header.php";
require_once "../../../../db/connect.php";

if ($_SESSION['user']['role'] !== 'admin') {
    die("Unauthorized user!");
}

if ($_SESSION['message'] ?? null) {
    if ($_SESSION['message'] === 'success') {
        $message = "Chart generated successfully!";
        unset($_SESSION['message']);
        include '../../../../includes/alerts/success.php';
    } else {
        $message = $_SESSION['message'];
        unset($_SESSION['message']);
        include '../../../../includes/alerts/failed.php';
    }
}
?>

<body>
    <main>
        <!-- Navbar -->
        <?php include_once "../../../includes/navbar.php"; ?>

        <div class="dashboard-layout">
            <!-- Sidebar -->
            <?php include_once "../../../includes/sidebar.php"; ?>

            <!-- Main Content -->
            <div class="content">
                <h1>Status of All Fines Issued</h1>
                <p class="description">View and analyze status of fines over different time periods.</p>
                <form action="issued-place/full-issued-place-table.php" method="get" class="filter-form-grid">
                    <div class="table-container">
                        <!-- Input Section -->
                        <!-- <div class="filter-form-grid"> -->
                        <div class="filter-field">
                            <label for="timePeriod">Time Period:</label>
                            <select id="timePeriod" name="time_period">
                                <option value="24h">Last 24 Hours</option>
                                <option value="72h">Last 72 Hours</option>
                                <option value="7days">Last 7 Days</option>
                                <option value="14days">Last 14 Days</option>
                                <option value="30days">Last 30 Days</option>
                                <option value="90days">Last 90 Days</option>
                                <option value="365days">Last 365 Days</option>
                                <option value="lifetime">Lifetime</option>
                            </select>
                        </div>
                        <!-- </div> -->

                        <div class="buttons">
                            <button type="submit" class="btn" id="generateReportBtn">
                                Generate Report
                            </button>
                        </div>
                    </div>
                </form>

                <div class="chart-content" id="fineStatusContent">
                    <div class="table-container">
                        <!-- Chart Section -->
                        <div class="chart-section">
                            <canvas id="fineChart" width="800" height="400"></canvas>
                        </div>
                        <div class="full-report-buttons" id="fullReportButtons">
                            <button class="btn full-report-btn" id="fineChartReportBtn">
                                Full Report
                            </button>
                        </div>
                    </div>

                    <div class="fine-summary mt-4" id="fineSummary"></div>

                    <h1>All Fines Issued</h1>
                    <p class="description">View and analyze fines over different time periods.</p>

                    <div class="table-container">
                        <!-- Chart Section -->
                        <div class="chart-section">
                            <canvas id="issuedFineChart" width="800" height="400"></canvas>
                        </div>

                        <div class="full-report-buttons" id="fullReportButtons">
                            <button class="btn full-report-btn" id="issuedFineChartReportBtn">
                                Full Report
                            </button>
                        </div>
                    </div>

                    <div class="fine-summary mt-4" id="IssuedFineSummary"></div>

                    <h1>Analize Fines by Issued police station</h1>
                    <p class="description">View and analyze fines by issued location over different time periods.</p>

                    <div class="table-container">
                        <!-- Chart Section -->
                        <div class="chart-section">
                            <canvas id="policeStationChart" width="800" height="400"></canvas>
                        </div>

                        <div class="full-report-buttons" id="fullReportButtons">
                            <button class="btn full-report-btn" id="policeStationChartReportBtn">
                                Full Report
                            </button>
                        </div>
                    </div>

                    <div class="fine-summary mt-4" id="policeStationSummary"></div>


                    <h1>Analize Fines by Issued Place</h1>
                    <p class="description">View and analyze fines by issued location over different time periods.</p>

                    <div class="table-container">
                        <!-- Chart Section -->
                        <div class="chart-section">
                            <canvas id="issuedPlaceChart" width="800" height="400"></canvas>
                        </div>
                    </div>
                    <div class="full-report-buttons" id="fullReportButtons">
                        <a href="issued-place/full-issued-place-table.php">
                            <button class="btn" id="issuedPlaceChartReportBtn">Full Report</button>
                        </a>
                    </div>

                    <div class="fine-summary mt-4" id="issuedPlaceSummary"></div>
                </div>
            </div>
        </div>



    </main>
    <?php include_once "../../../../includes/footer.php"; ?>


    <!-- Include Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <?php foreach ($pageConfig['scripts'] as $script): ?>
        <script src="<?php echo $script; ?>"></script>
    <?php endforeach; ?>
</body>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Attach one event listener to the button
        const generateBtn = document.getElementById("generateReportBtn");

        generateBtn.addEventListener("click", function(e) {
            e.preventDefault(); // prevent form submission or reload
            fetchFineStatusData();
            fetchIssuedFineData();
            fetchIssuedPlaceData();
            fetchPoliceStationData();

        });
    }); // Close the DOMContentLoaded event listener
</script>