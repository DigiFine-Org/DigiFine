<?php
$pageConfig = [
    'title' => 'Reports Dashboard',
    'styles' => ["../../../dashboard.css", "../reports.css"],
    'scripts' => [
        "./chart.js",
        "analytics.js",
        "growth-chart.js",
        "police-stations/station-chart.js",
        "police-stations/station-analytics.js",
        "police-officers/officer-chart.js",
        "police-officers/officer-analytics.js",
        "offence-type-revenue/offence-revenue-chart.js",
        "offence-type-revenue/offence-revenue-analytics.js",
        "issued-place/location-analytics.js",
        "issued-place/location-chart.js"
    ],
    'authRequired' => true
];

session_start();
include_once "../../../../includes/header.php";
require_once "../../../../db/connect.php";

if ($_SESSION['user']['role'] !== 'admin') {
    die("Unauthorized user!");
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
                <button onclick="history.back()" class="back-btn" style="position: absolute; top: 7px; right: 8px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                        <path fill-rule="evenodd"
                            d="M15 8a.5.5 0 0 1-.5.5H3.707l3.147 3.146a.5.5 0 0 1-.708.708l-4-4a.5.5 0 0 1 0-.708l4-4a.5.5 0 1 1 .708.708L3.707 7.5H14.5a.5.5 0 0 1 .5.5z" />
                    </svg>
                </button>
                <h1>Status of All Fines Issued</h1>
                <p class="description">View and analyze status of fines over different time periods.</p>
                <form method="get" class="filter-form-grid">
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

                    </div>
                </form>
                <div class="table-container">
                    <div class="buttons">
                        <button type="submit" class="btn" id="generateReportBtn">
                            Generate Report
                        </button>
                    </div>
                </div>
                <div class="chart-content" id="fineStatusContent" style="display: none;">
                    <div class="table-container">
                        <!-- Chart Section -->
                        <div class="chart-section">
                            <canvas id="fineChart" width="800" height="400"></canvas>
                        </div>
                        <form action="payment-status/full-payment-status-table.php" method="get">
                            <input type="hidden" name="time_period" id="hiddenTimePeriod">
                            <button type="submit" class="btn full-report">Full Report</button>
                        </form>
                    </div>

                    <div class="fine-summary mt-4" id="fineSummary"></div>

                    <h1>All Fines Issued</h1>
                    <p class="description">View and analyze fines over different time periods.</p>

                    <div class="table-container">
                        <!-- Chart Section -->
                        <div class="chart-section">
                            <canvas id="issuedFineChart" width="800" height="400"></canvas>
                        </div>

                        <form action="reported-all/full-reported-all-table.php" method="get">
                            <input type="hidden" name="time_period" class="hiddenTimePeriod">
                            <button type="submit" class="btn full-report">Full Report</button>
                        </form>
                    </div>

                    <div class="fine-summary mt-4" id="IssuedFineSummary"></div>

                    <h1>Analyze Fines by Issued Police Station</h1>
                    <p class="description">View and analyze fines by issued location over different time periods.</p>

                    <div class="table-container">
                        <!-- Chart Section -->
                        <div class="chart-section">
                            <canvas id="policeStationChart" width="800" height="400"></canvas>
                        </div>
                        <form action="issued-police-station/full-issued-police-station-table.php" method="get">
                            <input type="hidden" name="time_period" id="hiddenTimePeriod">
                            <button type="submit" class="btn full-report">Full Report</button>
                        </form>
                    </div>

                    <div class="fine-summary mt-4" id="policeStationSummary"></div>

                    <h1>Analyze Fines by Issued Place</h1>
                    <p class="description">View and analyze fines by issued location over different time periods.</p>

                    <div class="table-container">
                        <!-- Chart Section -->
                        <div class="chart-section">
                            <canvas id="issuedPlaceChart" width="800" height="400"></canvas>
                        </div>
                    </div>

                    <form action="issued-place/full-issued-place-table.php" method="get">
                        <input type="hidden" name="time_period" id="hiddenTimePeriod">
                        <button type="submit" class="btn full-report">Full Report</button>
                    </form>

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
        const generateBtn = document.getElementById("generateReportBtn");
        const fineStatusContent = document.getElementById("fineStatusContent");

        generateBtn.addEventListener("click", function() {
            fineStatusContent.style.display = "block";
        });
    });

    document.addEventListener("DOMContentLoaded", function() {
        const generateBtn = document.getElementById("generateReportBtn");
        const timePeriodSelect = document.getElementById("timePeriod");
        const hiddenTimePeriods = document.querySelectorAll("input[name='time_period']");

        generateBtn.addEventListener("click", function(e) {
            e.preventDefault(); // Prevent reload
            const timePeriod = timePeriodSelect.value;
            hiddenTimePeriods.forEach(input => {
                input.value = timePeriod;
            });

            fetchFineStatusData();
            fetchIssuedFineData();
            fetchIssuedPlaceData();
            fetchPoliceStationData();
        });

        // Optional: sync timePeriod dropdown to hidden inputs live
        timePeriodSelect.addEventListener("change", function() {
            hiddenTimePeriods.forEach(input => {
                input.value = this.value;
            });
        });
    });
</script>