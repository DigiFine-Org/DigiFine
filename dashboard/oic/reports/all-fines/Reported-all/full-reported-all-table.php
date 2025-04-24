<?php
$pageConfig = [
    'title' => 'Reports Dashboard',
    'styles' => ["../../../../dashboard.css", "../../reports.css"],
    'scripts' => ["../../../../dashboard.js"],
    'authRequired' => true
];

session_start();
include_once "../../../../../includes/header.php";

if ($_SESSION['user']['role'] !== 'admin') {
    die("Unauthorized user!");
}
$timePeriod = $_GET['time_period'] ?? '';
if (empty($timePeriod)) {
    echo "No time period selected.";
    exit;
}

// Fetch data from the same source used by your chart
$url = "http://localhost/digifine/dashboard/admin/reports/all-fines/Reported-all/get-fines.php?time_period=" . urlencode($timePeriod);
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

            <!-- Fines Table -->
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>All Fines</th>
                            <th>Reported Fines</th>
                            <th>Unreported Fines</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $dates = [];
                        foreach (['all', 'reported'] as $status) {
                            foreach ($data[$status] as $row) {
                                $dates[$row['label']][$status] = $row['count'];
                            }
                        }

                        foreach ($dates as $date => $counts) {
                            $all = $counts['all'] ?? 0;
                            $reported = $counts['reported'] ?? 0;
                            $unreported = $all - $reported;
                        ?>
                            <tr>
                                <td><?= htmlspecialchars($date) ?></td>
                                <td><?= $all ?></td>
                                <td><?= $reported ?></td>
                                <td><?= $unreported ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                    <?php if (empty($dates)) : ?>
                        <tr>
                            <td colspan="4">No fines found for the selected filters.</td>
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