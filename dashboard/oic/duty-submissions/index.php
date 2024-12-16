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
        SELECT ds.id AS submission_id, ds.police_id, ds.patrol_location, ds.patrol_time_start, ds.patrol_time_end, ds.patrol_information
        FROM duty_submissions ds
        INNER JOIN officers o ON ds.police_id = o.id
        WHERE  o.police_station = ?
        ORDER BY ds.patrol_time_start DESC";

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
<<<<<<< HEAD
                <h1>Submitted Duties</h1>
=======
                <h1>Duty Submissions</h1>
>>>>>>> c2e6a350219c3bb0ba715390fc7af16602c190d5
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
<<<<<<< HEAD
                                <th>Police ID</th>
                                <th>Patrol Location</th>
                                <th>Start Time</th>
                                <th>End Time</th>
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
                                        <td><?= htmlspecialchars($duty['patrol_time_start']) ?></td>
                                        <td><?= htmlspecialchars($duty['patrol_time_end']) ?></td>
                                        <td><?= htmlspecialchars($duty['patrol_information'] ?? 'N/A') ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
=======
                                <th>Submission Number</th>
                                <th>Police ID</th>
                                <th>Officer Name</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>440891</td>
                                <td>15364</td>
                                <td>Pubuditha Walgampaya</td>
                                <td><button class="btn">View</button></td>
                            </tr>
                            <tr>
                                <td>44082</td>
                                <td>12675</td>
                                <td>Wendt Edmund</td>
                                <td><button class="btn">View</button></td>
                            </tr>
                            <tr>
                                <td>44083</td>
                                <td>14567</td>
                                <td>Thihansa Sanjunie</td>
                                <td><button class="btn">View</button></td>
                            </tr>
                            <tr>
                                <td>44084</td>
                                <td>18934</td>
                                <td>John Manuel</td>
                                <td><button class="btn">View</button></td>
                            </tr>
                            <tr>
                                <td>44085</td>
                                <td>17256</td>
                                <td>Nikila Silva</td>
                                <td><button class="btn">View</button></td>
                            </tr>
                            <tr>
                                <td>44086</td>
                                <td>16321</td>
                                <td>Nadun Madusanka</td>
                                <td><button class="btn">View</button></td>
                            </tr>
                            <tr>
                                <td>44087</td>
                                <td>13452</td>
                                <td>Abdhul Basith</td>
                                <td><button class="btn">View</button></td>
                            </tr>
                            <tr>
                                <td>44088</td>
                                <td>14678</td>
                                <td>Nimsara Wickramathanthri</td>
                                <td><button class="btn">View</button></td>
                            </tr>
                            <tr>
                                <td>44089</td>
                                <td>17892</td>
                                <td>Chamath Abeysinghe</td>
                                <td><button class="btn">View</button></td>
                            </tr>
                            <tr>
                                <td>440810</td>
                                <td>12543</td>
                                <td>Heshan Fernando</td>
                                <td><button class="btn">View</button></td>
                            </tr>
                            <tr>
                                <td>440811</td>
                                <td>13987</td>
                                <td>Dinuka Perera</td>
                                <td><button class="btn">View</button></td>
                            </tr>
                            <tr>
                                <td>440812</td>
                                <td>14236</td>
                                <td>Ravindu Wijesinghe</td>
                                <td><button class="btn">View</button></td>
                            </tr>
                            <tr>
                                <td>440813</td>
                                <td>16897</td>
                                <td>Samadhi Peiris</td>
                                <td><button class="btn">View</button></td>
                            </tr>
                            <tr>
                                <td>440814</td>
                                <td>17562</td>
                                <td>Mahesh Samarasinghe</td>
                                <td><button class="btn">View</button></td>
                            </tr>
                            <tr>
                                <td>440815</td>
                                <td>15834</td>
                                <td>Ruwan Lakmal</td>
                                <td><button class="btn">View</button></td>
                            </tr>
>>>>>>> c2e6a350219c3bb0ba715390fc7af16602c190d5
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include_once "../../../includes/footer.php"; ?>
