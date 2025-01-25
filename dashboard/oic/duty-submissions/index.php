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

    // Query to fetch duty submissions for officers under the OIC's police station
    $query = "
        SELECT ds.id AS submission_id, ds.police_id, ds.patrol_location, a.duty_time_start, ds.patrol_time_started, a.duty_time_end, ds.patrol_time_ended, ds.patrol_information
        FROM duty_submissions ds
        INNER JOIN officers o ON ds.police_id = o.id
        INNER JOIN assigned_duties a ON ds.assigned_duty_id = a.id
        WHERE  o.police_station = ?";

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
            <div class="container x-large no-border">
                <h1>Duty Submissions</h1>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Police ID</th>
                                <th>Patrol Location</th>
                                <th>Duty assigned to start at</th>
                                <th>Duty started by the officer at</th>
                                <th>Duty assigned to end at</th>
                                <th>Duty ended by the officer at</th>
                                <th>Additional Information</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($duties)): ?>
                                <tr>
                                    <td colspan="5">No submitted duties found.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($duties as $duty): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($duty['police_id']) ?></td>
                                        <td><?= htmlspecialchars($duty['patrol_location']) ?></td>
                                        <td><?= htmlspecialchars($duty['duty_time_start']) ?></td>
                                        <td><?= htmlspecialchars($duty['patrol_time_started']) ?></td>
                                        <td><?= htmlspecialchars($duty['duty_time_end']) ?></td>
                                        <td><?= htmlspecialchars($duty['patrol_time_ended']) ?></td>
                                        <td><?= htmlspecialchars($duty['patrol_information'] ?? 'N/A') ?></td>
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
