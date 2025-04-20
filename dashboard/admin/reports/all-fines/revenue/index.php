<?php
$pageConfig = [
    'title' => 'Reports Dashboard',
    'styles' => ["../../../../dashboard.css", "../../reports.css"],
    'scripts' => ["./chart.js", "analytics.js", "growth-chart.js", "police-stations/station-chart.js", "police-stations/station-analytics.js"],
    'authRequired' => true
];

session_start();
include_once "../../../../../includes/header.php";
require_once "../../../../../db/connect.php";

if ($_SESSION['user']['role'] !== 'admin') {
    die("Unauthorized user!");
}

if ($_SESSION['message'] ?? null) {
    if ($_SESSION['message'] === 'success') {
        $message = "Chart generated successfully!";
        unset($_SESSION['message']);
        include '../../../../../includes/alerts/success.php';
    } else {
        $message = $_SESSION['message'];
        unset($_SESSION['message']);
        include '../../../../../includes/alerts/failed.php';
    }
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
            <div class="content">
                <h1>Revenue of All Fines Issued</h1>
                <p class="description">View and analyze status of fines over different time periods.</p>

                <div class="table-container">
                    <!-- Input Section -->
                    <div class="filter-form-grid">
                        <div class="filter-field">
                            <label for="timePeriod">Time Period:</label>
                            <select id="timePeriod">
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
                    </div>
                </div>

                <div class="filter-field">
                    <button class="btn" id="generateReportBtn">
                        Generate Report
                    </button>
                </div>

                <div class="table-container">
                    <!-- Chart Section -->
                    <div class="chart-section">
                        <canvas id="fineChart" width="800" height="400"></canvas>
                    </div>
                </div>

                <div class="table-container">
                    <!-- Chart Section -->
                    <div class="chart-section">
                        <canvas id="fineGrowthChart" width="400" height="200"></canvas>
                    </div>
                </div>
                <div class="table-container">
                    <div class="fine-summary mt-4" id="fineSummary"></div>
                </div>

                <div class="table-container">
                    <!-- Chart Section -->
                    <div class="chart-section">
                        <canvas id="policeStationFineChart" width="800" height="400"></canvas>
                    </div>
                </div>

                <div class="table-container">
                    <div class="fine-summary mt-4" id="fineSummary2"></div>
                </div>
    </main>

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
            fetchFineData();
            fetchStationFineData();
        });
    }); // Close the DOMContentLoaded event listener
</script>