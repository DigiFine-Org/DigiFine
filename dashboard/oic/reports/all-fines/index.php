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
    ],
    'authRequired' => true
];

session_start();
include_once "../../../../includes/header.php";
require_once "../../../../db/connect.php";

if ($_SESSION['user']['role'] !== 'oic') {
    die("Unauthorized user!");
}

$policeStationId = $_SESSION['police_station_id'] ?? null;
// echo "Police Station ID: " . htmlspecialchars($policeStationId);
if (!$policeStationId) {
    die("Police station ID not found in session.");
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
            <div class="content" style="max-width: 100%;">
                <button onclick="history.back()" class="back-btn" style="position: absolute; top: 7px; right: 8px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                        <path fill-rule="evenodd"
                            d="M15 8a.5.5 0 0 1-.5.5H3.707l3.147 3.146a.5.5 0 0 1-.708.708l-4-4a.5.5 0 0 1 0-.708l4-4a.5.5 0 1 1 .708.708L3.707 7.5H14.5a.5.5 0 0 1 .5.5z" />
                    </svg>
                </button>
                <h1>Status of All Fines Issued</h1>
                <p class="description">View and analyze status of fines over different time periods.</p>
                <form method="get" class="filter-form-grid">
                    <div class="filter-field">
                        <label for="stationId">Police Station ID:</label>
                        <input type="text" id="stationId" name="stationId" value="<?php echo htmlspecialchars($policeStationId); ?>" readonly>
                    </div>
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
                    <h1>Analize by Payment Status</h1>
                    <p class="description">View and analyze fines over different time periods.</p>
                    <div class="table-container">
                        <!-- Chart Section -->
                        <div class="chart-section">
                            <canvas id="fineChart" width="800" height="400"></canvas>
                        </div>
                        <form action="payment-status/full-payment-status-table.php" method="get">
                            <input type="hidden" name="time_period" id="hiddenTimePeriod">
                            <input type="hidden" name="station_id" id="hiddenStationId">
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
                            <input type="hidden" name="station_id" id="hiddenStationId">
                            <button type="submit" class="btn full-report">Full Report</button>
                        </form>
                    </div>

                    <div class="fine-summary mt-4" id="IssuedFineSummary"></div>

                    <h1>Analyze Fines by Issued Place</h1>
                    <p class="description">View and analyze fines by issued location over different time periods.</p>
                    <p class="description">*location_name = Location entered by officers.</p>

                    <div class="table-container">
                        <!-- Chart Section -->
                        <div class="chart-section">
                            <canvas id="issuedPlaceChart" width="800" height="400"></canvas>
                        </div>
                    </div>

                    <form action="issued-place/full-issued-place-table.php" method="get">
                        <input type="hidden" name="time_period" id="hiddenTimePeriod">
                        <input type="hidden" name="station_id" id="hiddenStationId">
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
        const stationIdInput = document.getElementById("stationId");
        const hiddenTimePeriods = document.querySelectorAll("input[name='time_period']");
        const hiddenStationIds = document.querySelectorAll("input[name='station_id']");
        const fullReportButtons = document.querySelectorAll(".btn.full-report");

        // Update hidden inputs when the Generate Report button is clicked
        generateBtn.addEventListener("click", function(e) {
            e.preventDefault(); // Prevent reload
            const timePeriod = timePeriodSelect.value;
            const stationId = stationIdInput.value;

            // Update hidden inputs for time period and station ID
            hiddenTimePeriods.forEach(input => {
                input.value = timePeriod;
            });
            hiddenStationIds.forEach(input => {
                input.value = stationId;
            });

            // Fetch data for the report
            fetchFineStatusData();
            fetchIssuedFineData();
            fetchIssuedPlaceData();
        });
    });
</script>