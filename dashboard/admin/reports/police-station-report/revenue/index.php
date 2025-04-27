<?php
$pageConfig = [
    'title' => 'Reports Dashboard',
    'styles' => ["../../../../dashboard.css", "../../reports.css"],
    'scripts' => [
        "overall/chart.js",
        "overall/analytics.js",
        "overall/growth-chart.js",
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
include_once "../../../../../includes/header.php";
require_once "../../../../../db/connect.php";

if ($_SESSION['user']['role'] !== 'admin') {
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
        <?php include_once "../../../../includes/navbar.php"; ?>

        <div class="dashboard-layout">
            <!-- Sidebar -->
            <?php include_once "../../../../includes/sidebar.php"; ?>

            <!-- Main Content -->
            <div class="content" style="max-width: none;">
                <button onclick="history.back()" class="back-btn" style="position: absolute; top: 7px; right: 8px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                        <path fill-rule="evenodd"
                            d="M15 8a.5.5 0 0 1-.5.5H3.707l3.147 3.146a.5.5 0 0 1-.708.708l-4-4a.5.5 0 0 1 0-.708l4-4a.5.5 0 1 1 .708.708L3.707 7.5H14.5a.5.5 0 0 1 .5.5z" />
                    </svg>
                </button>
                <h1>Analize Revenue of All Fines Issued by Police Station <?php echo htmlspecialchars($policeStationId); ?></h1>
                <p class="description">View and analyze status of fines over different time periods.</p>
                <form method="get" class="filter-form-grid">
                    <!-- <div class="filter-field">
                        <label for="stationId">Police Station ID:</label> -->
                    <input type="hidden" id="stationId" name="stationId" value="<?php echo htmlspecialchars($policeStationId); ?>" readonly>
                    <!-- </div> -->
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
                            <canvas id="fineGrowthChart" width="800" height="400"></canvas>
                        </div>
                        <div class="chart-section">
                            <canvas id="fineChart" width="800" height="400"></canvas>
                        </div>
                        <form action="overall/get-full-revenue-report.php" method="get">
                            <input type="hidden" name="time_period" id="hiddenTimePeriod">
                            <input type="hidden" name="station_id" id="hiddenStationId">
                            <button type="submit" class="btn full-report">Full Report</button>
                        </form>
                    </div>

                    <div class="fine-summary mt-4" id="fineSummary"></div>

                    <h1>Issued fines by Issued Police Officers</h1>
                    <p class="description">View and analyze fines by issued location over different time periods.</p>

                    <div class="table-container">
                        <!-- Chart Section -->
                        <div class="chart-section">
                            <canvas id="officerFineChart" width="800" height="400"></canvas>
                        </div>
                        <form action="police-officers/full-officer-table.php" method="get">
                            <input type="hidden" name="time_period" id="hiddenTimePeriod">
                            <input type="hidden" name="station_id" id="hiddenStationId">
                            <button type="submit" class="btn full-report">Full Report</button>
                        </form>
                    </div>

                    <div class="fine-summary mt-4" id="officerSummary"></div>

                    <h1> Issued Fines - Offences</h1>
                    <p class="description">View and analyze fines by issued location over different time periods.</p>

                    <div class="table-container">
                        <!-- Chart Section -->
                        <div class="chart-section">
                            <canvas id="OffenesRevenueChart" width="800" height="400"></canvas>
                        </div>
                    </div>

                    <form action="offence-type-revenue/get-full-offence-table.php" method="get">
                        <input type="hidden" name="time_period" id="hiddenTimePeriod">
                        <input type="hidden" name="station_id" id="hiddenStationId">
                        <button type="submit" class="btn full-report">Full Report</button>
                    </form>

                    <div class="fine-summary mt-4" id="offencesRevenueSummary"></div>


                    <h1>Issued Fines - location</h1>
                    <p class="description">View and analyze fines by issued location over different time periods.</p>

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
    <?php include_once "../../../../../includes/footer.php"; ?>


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


        generateBtn.addEventListener("click", function(e) {
            e.preventDefault(); // Prevent reload
            const timePeriod = timePeriodSelect.value;
            const stationId = stationIdInput.value;

            hiddenTimePeriods.forEach(input => {
                input.value = timePeriod;
            });

            hiddenStationIds.forEach(input => {
                input.value = stationId;
            });

            fetchFineData();
            fetchOfficerFineData();
            fetchOffencesRevenueFineData();
            fetchIssuedPlaceFineData();
        });

    });
</script>