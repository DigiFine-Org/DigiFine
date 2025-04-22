<?php
$pageConfig = [
    'title' => 'Submitted Duties',
    'styles' => ["../../dashboard.css"],
    'scripts' => ["../../dashboard.js"],
    'authRequired' => true
];

require_once "../../../db/connect.php";
include_once "../../../includes/header.php";

if ($_SESSION['user']['role'] !== 'oic') {
    die("Unauthorized user!");
}

try {
    $oic_id = $_SESSION['user']['id'];

    $sql = "SELECT police_station FROM officers WHERE is_oic = 1 AND id = ? LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $oic_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        die("OIC not found or police station not assigned.");
    }

    $oic_data = $result->fetch_assoc();
    $police_station_id = $oic_data['police_station'];

    // Modified query to include all necessary fields
    $query = "
        SELECT 
            ds.id AS submission_id, 
            ds.police_id, 
            CONCAT(o.fname, ' ', o.lname) AS officer_name,
            a.duty_date,
            ds.patrol_location, 
            a.duty_time_start, 
            ds.patrol_time_started, 
            a.duty_time_end, 
            ds.patrol_time_ended, 
            ds.is_late_submission
        FROM duty_submissions ds
        INNER JOIN officers o ON ds.police_id = o.id
        INNER JOIN assigned_duties a ON ds.assigned_duty_id = a.id
        WHERE o.police_station = ?";

    $stmt = $conn->prepare($query);
    if (!$stmt) {
        throw new Exception("Failed to prepare statement: " . $conn->error);
    }

    $stmt->bind_param('s', $police_station_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $duties = [];
    while ($row = $result->fetch_assoc()) {
        $duties[] = $row;
    }
} catch (Exception $e) {
    die("Error fetching submitted duties: " . $e->getMessage());
}
?>

<main>
    <?php include_once "../../includes/navbar.php"; ?>

    <div class="dashboard-layout">
        <?php include_once "../../includes/sidebar.php"; ?>
        <div class="content">
            <button onclick="history.back()" class="back-btn" style="position: absolute; top: 7px; right: 8px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                    <path fill-rule="evenodd"
                        d="M15 8a.5.5 0 0 1-.5.5H3.707l3.147 3.146a.5.5 0 0 1-.708.708l-4-4a.5.5 0 0 1 0-.708l4-4a.5.5 0 1 1 .708.708L3.707 7.5H14.5a.5.5 0 0 1 .5.5z" />
                </svg>
            </button>
            <div class="">
                <h1>Duty Submissions</h1>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Police ID</th>
                                <th>Officer Name</th>
                                <th>Duty Date</th>
                                <th>Patrol Location</th>
                                <th>Assigned Start</th>
                                <th>Actual Start</th>
                                <th>Assigned End</th>
                                <th>Actual End</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($duties)): ?>
                                <tr>
                                    <td colspan="11">No submitted duties found.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($duties as $duty): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($duty['police_id']) ?></td>
                                        <td><?= htmlspecialchars($duty['officer_name']) ?></td>
                                        <td><?= htmlspecialchars($duty['duty_date']) ?></td>
                                        <td><?= htmlspecialchars($duty['patrol_location']) ?></td>
                                        <td><?= htmlspecialchars($duty['duty_time_start']) ?></td>
                                        <td><?= htmlspecialchars($duty['patrol_time_started']) ?></td>
                                        <td><?= htmlspecialchars($duty['duty_time_end']) ?></td>
                                        <td><?= htmlspecialchars($duty['patrol_time_ended']) ?></td>
                                        <td><?= $duty['is_late_submission'] ? 'Late' : 'On Time' ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include_once "../../../includes/footer.php"; ?>