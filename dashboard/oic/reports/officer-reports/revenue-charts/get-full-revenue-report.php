<?php
$pageConfig = [
    'title' => 'Reports Dashboard',
    'styles' => ["../../../../dashboard.css", "../../reports.css"],
    'scripts' => ["../../../../dashboard.js"],
    'authRequired' => true
];

session_start();
include_once "../../../../../includes/header.php";

if ($_SESSION['user']['role'] !== 'oic') {
    die("Unauthorized user!");
}
$timePeriod = $_GET['time_period'] ?? '';
$officerId = $_GET['officer_id'];

// Fetch data from the same source used by your chart
$url = "http://localhost/digifine/dashboard/oic/reports/officer-reports/revenue-charts/get-fines.php?police_id=" . urlencode($officerId) . "&time_period=" . urlencode($timePeriod);

$response = file_get_contents($url);

if ($response === false) {
    echo "Failed to fetch data.";
    exit;
}

$data = json_decode($response, true);

if (isset($data['error'])) {
    echo "Error: " . htmlspecialchars($data['error']);
    exit;
}
?>

<main>
    <?php include_once "../../../../includes/navbar.php" ?>

    <div class="dashboard-layout">
        <?php include_once "../../../../includes/sidebar.php" ?>
        <div class="content">
            <button onclick="history.back()" class="back-btn" style="position: absolute; top: 7px; right: 8px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                    <path fill-rule="evenodd"
                        d="M15 8a.5.5 0 0 1-.5.5H3.707l3.147 3.146a.5.5 0 0 1-.708.708l-4-4a.5.5 0 0 1 0-.708l4-4a.5.5 0 1 1 .708.708L3.707 7.5H14.5a.5.5 0 0 1 .5.5z" />
                </svg>
            </button>
            <h2>Full Report of Fines by Reported Status - <?= htmlspecialchars($timePeriod) ?></h2>
            <div class="table-container">
                <div class="buttons" style="margin-bottom: 20px;">
                    <a href="download-report.php?time_period=<?= urlencode($timePeriod) ?>&officer_id=<?= urlencode($officerId) ?>&download=true" class="btn">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" style="margin-right: 5px;">
                            <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5z" />
                            <path d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3z" />
                        </svg>
                        Download Full Report
                    </a>
                </div>
            </div>
            <!-- Fines Table -->
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Paid Fines</th>
                            <th>Pending Fines</th>
                            <th>Overdue Fines</th>
                            <th>All Fines</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $dates = [];
                        foreach (['paid', 'pending', 'overdue'] as $status) {
                            foreach ($data[$status] as $row) {
                                $date = $row['label'];
                                $amount = floatval($row['total_amount']);
                                if (!isset($dates[$date])) {
                                    $dates[$date] = ['paid' => 0, 'pending' => 0, 'overdue' => 0];
                                }
                                $dates[$date][$status] = $amount;
                            }
                        }

                        foreach ($dates as $date => $counts) {
                            $paid = $counts['paid'] ?? 0;
                            $pending = $counts['pending'] ?? 0;
                            $overdue = $counts['overdue'] ?? 0;
                            $all = $pending + $overdue + $paid;
                        ?>
                            <tr>
                                <td><?= htmlspecialchars($date) ?></td>
                                <td><?= $paid  ?></td>
                                <td><?= $pending ?></td>
                                <td><?= $overdue ?></td>
                                <td><?= $all ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                    <?php if (empty($dates)) : ?>
                        <tr>
                            <td colspan="5">No fines found for the selected filters.</td>
                        </tr>
                    <?php endif; ?>
                </table>
            </div>
        </div>
    </div>



    </div>
    </div>


</main>

<?php include_once "../../../../../includes/footer.php"; ?>