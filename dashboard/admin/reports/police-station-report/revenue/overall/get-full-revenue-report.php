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
if (!$timePeriod) {
    die("Time period not found!");
}

$policeStationId = $_GET['station_id'] ?? '';
if (!$policeStationId) {
    die("Police station not found!");
}

// Fetch data from the same source used by your chart
$url = "http://localhost/digifine/dashboard/oic/reports/revenue/overall/get-fines.php?police_station=$policeStationId&time_period=$timePeriod";

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
            <h2>Full Report of Revenue - <?= htmlspecialchars($timePeriod) ?></h2>

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