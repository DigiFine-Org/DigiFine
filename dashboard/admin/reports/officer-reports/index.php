<?php
$pageConfig = [
    'title' => 'Reports Dashboard',
    'styles' => ["../../../dashboard.css", "../reports.css"],
    'scripts' => [
        "../../../dashboard.js",
        "officer-details.js",
        "duty-submissions/duty-chart.js",
        "duty-submissions/analytics.js",
        "issued-reported/issued-reported-chart.js",
        "issued-reported/analytics.js",
        "fine-court/fine-court-chart.js",
        "fine-court/analytics.js",
        "issued-place/issued-place-chart.js",
        "issued-place/issued-place-analytics.js",
        "revenue-charts/revenue-chart.js",
        "revenue-charts/analytics.js",
        "revenue-charts/growth-chart.js",

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
    $message = $_SESSION['message'];
    unset($_SESSION['message']);
    include '../../../../includes/alerts/failed.php';
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
            <div class="content" style="max-width: none;">
                <button onclick="history.back()" class="back-btn" style="position: absolute; top: 7px; right: 8px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                        <path fill-rule="evenodd"
                            d="M15 8a.5.5 0 0 1-.5.5H3.707l3.147 3.146a.5.5 0 0 1-.708.708l-4-4a.5.5 0 0 1 0-.708l4-4a.5.5 0 1 1 .708.708L3.707 7.5H14.5a.5.5 0 0 1 .5.5z" />
                    </svg>
                </button>
                <h1>Status of All Fines Issued</h1>
                <p class="description">View and analyze status of fines over different time periods.</p>
                <form action="./get-officer-details.php" method="get" class="filter-form-grid">
                    <div class="filter-field">
                        <label for="officerId">Officer ID:</label>
                        <input type="text" id="officerId" name="officerId" placeholder="Enter Officer ID" required>
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

                <div class="officer-info-container" id="officerInfoContainer" style="display: none; margin-bottom: 20px;">
                    <h1>Officer Information</h1>
                    <div id="officerDetails" class="officer-details">
                    </div>
                </div>

                <div class="chart-content" id="fineStatusContent" style="display: none;">

                    <h1>Duty Submisions</h1>
                    <p class="description">View and analyze fines over different time periods.</p>

                    <div class="table-container">
                        <div class="chart-section">
                            <canvas id="dutyChart" width="800" height="400"></canvas>
                        </div>

                        <form action="duty-submissions/full-duty-table.php" method="get">
                            <input type="hidden" name="time_period" class="hiddenTimePeriod">
                            <input type="hidden" name="officer_id" id="hiddenOfficerId">
                            <button type="submit" class="btn full-report">Full Report</button>
                        </form>
                    </div>

                    <div class="fine-summary mt-4" id="DutySummary"></div>

                    <h1>Issued-Reported Fines</h1>
                    <p class="description">View and analyze fines over different time periods.</p>

                    <div class="table-container">
                        <div class="chart-section">
                            <canvas id="ReportedfineChart" width="800" height="400"></canvas>
                        </div>

                        <form action="issued-reported/full-reported-all-table.php" method="get">
                            <input type="hidden" name="time_period" class="hiddenTimePeriod">
                            <input type="hidden" name="officer_id" id="hiddenOfficerId">
                            <button type="submit" class="btn full-report">Full Report</button>
                        </form>
                    </div>

                    <div class="fine-summary mt-4" id="fineSummary"></div>

                    <h1>Fine Offence - Court Offence</h1>
                    <p class="description">View and analyze fines over different time periods.</p>

                    <div class="table-container">
                        <div class="chart-section">
                            <canvas id="fineCourtChart" width="800" height="400"></canvas>
                        </div>

                        <form action="fine-court/full-fine-court-table.php" method="get">
                            <input type="hidden" name="time_period" class="hiddenTimePeriod">
                            <input type="hidden" name="officer_id" id="hiddenOfficerId">
                            <button type="submit" class="btn full-report">Full Report</button>
                        </form>
                    </div>

                    <div class="fine-summary mt-4" id="CourtfineSummary"></div>

                    <h1>Fine Issued Place</h1>
                    <p class="description">View and analyze fines over different time periods.</p>

                    <div class="table-container">
                        <div class="chart-section">
                            <canvas id="issuedPlaceChart" width="800" height="400"></canvas>
                        </div>

                        <form action="issued-place/full-issued-place-table.php" method="get">
                            <input type="hidden" name="time_period" class="hiddenTimePeriod">
                            <input type="hidden" name="officer_id" id="hiddenOfficerId">
                            <button type="submit" class="btn full-report">Full Report</button>
                        </form>
                    </div>

                    <div class="fine-summary mt-4" id="issuedPlaceSummary"></div>

                    <h1>Revenue of fines</h1>
                    <p class="description">View and analyze fines over different time periods.</p>

                    <div class="table-container">
                        <div class="table-container">
                            <div class="chart-section">
                                <canvas id="fineGrowthChart" width="800" height="400"></canvas>
                            </div>
                        </div>
                        <div class="chart-section">
                            <canvas id="revenueChart" width="800" height="400"></canvas>
                        </div>

                        <form action="revenue-charts/get-full-revenue-report.php" method="get">
                            <input type="hidden" name="time_period" class="hiddenTimePeriod">
                            <input type="hidden" name="officer_id" id="hiddenOfficerId">
                            <button type="submit" class="btn full-report">Full Report</button>
                        </form>
                    </div>

                    <div class="fine-summary mt-4" id="RevenueSummary"></div>
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
        const officerIdInput = document.getElementById("officerId");
        const hiddenTimePeriods = document.querySelectorAll("input[name='time_period']");
        const hiddenOfficerIds = document.querySelectorAll("input[name='officer_id']");
        const fullReportButtons = document.querySelectorAll(".btn.full-report");

        // Update hidden inputs when the Generate Report button is clicked
        generateBtn.addEventListener("click", function(e) {
            e.preventDefault(); // Prevent reload

            const timePeriod = timePeriodSelect.value;
            const officerId = officerIdInput.value;

            // Update hidden inputs for time period and officer ID
            hiddenTimePeriods.forEach(input => {
                input.value = timePeriod;
            });
            hiddenOfficerIds.forEach(input => {
                input.value = officerId;
            });

            // Fetch data for the report
            fetchOfficerInfo();
            fetchDutyData();
            fetchIssuedReportedData();
            fetchFineCourtData();
            fetchIssuedPlaceData();
            fetchRevenueData();
        });
    });
</script>