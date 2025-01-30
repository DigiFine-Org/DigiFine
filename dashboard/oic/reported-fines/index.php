<?php
$pageConfig = [
    'title' => 'Police Station Fines',
    'styles' => ["../../dashboard.css"],
    'scripts' => ["../../dashboard.js"],
    'authRequired' => true
];

require_once "../../../db/connect.php";
include_once "../../../includes/header.php";

if ($_SESSION['user']['role'] !== 'oic') {
    die("unauthorized user!");
}

$oic_id = $_SESSION['user']['id'] ?? null;

if (!$oic_id) {
    die("Unauthorized access.");
}

// Retrieve OIC's police station ID
$sql = "SELECT * FROM officers WHERE is_oic = '1' AND id = ? LIMIT 1";
$oic_stmt = $conn->prepare($sql);
$oic_stmt->bind_param("i", $oic_id);
$oic_stmt->execute();
$result = $oic_stmt->get_result();
if ($result->num_rows === 0) {
    die("OIC not found or police station not assigned.");
}
$oic_data = $result->fetch_assoc();
$police_station_id = $oic_data['police_station'];

// Fetch fines related to the OIC's police station
$query = "
    SELECT f.id, f.police_id, f.driver_id, f.license_plate_number, f.issued_date, f.issued_time, 
           f.offence_type, f.nature_of_offence, f.offence, f.fine_status, f.is_reported
    FROM fines f
    INNER JOIN officers o ON f.police_id = o.id
    WHERE o.police_station = ? AND f.is_reported = 1
";

$fines = [];
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $police_station_id);
$stmt->execute();
$result = $stmt->get_result();
$fines = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Fetch offence types and offences for filters
$stmt = $conn->prepare("SELECT DISTINCT offence_type FROM fines");
$stmt->execute();
$result = $stmt->get_result();
$offenceTypes = $result->fetch_all(MYSQLI_ASSOC);

$stmt = $conn->prepare("SELECT DISTINCT offence FROM fines");
$stmt->execute();
$result = $stmt->get_result();
$offences = $result->fetch_all(MYSQLI_ASSOC);

$stmt->close();
?>

<main>
    <?php include_once "../../includes/navbar.php" ?>

    <div class="dashboard-layout">
        <?php include_once "../../includes/sidebar.php" ?>
        <div class="content">
            <div class="container x-large no-border">
                <div class="field">
                    <h1>Reported Fines</h1>

                </div>
            </div>


            <!-- Fines Table -->
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>FINE ID</th>
                            <th>POLICE ID</th>
                            <th>DRIVER ID</th>
                            <th>ISSUED DATE</th>
                            <th>OFFENCE TYPE</th>
                            <th>OFFENCE</th>
                            <th>Is Reported</th>
                            <th>FINE STATUS</th>
                            <th>ACTION</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($fines)): ?>
                            <?php foreach ($fines as $fine): ?>
                                <tr>
                                    <td><?= htmlspecialchars($fine['id']) ?></td>
                                    <td><?= htmlspecialchars($fine['police_id']) ?></td>
                                    <td><?= htmlspecialchars($fine['driver_id']) ?></td>
                                    <td><?= htmlspecialchars($fine['issued_date']) ?></td>
                                    <td><?= htmlspecialchars($fine['offence_type']) ?></td>
                                    <td><?= htmlspecialchars($fine['offence']) ?></td>
                                    <td><?= $fine['is_reported'] ? 'Yes' : 'No' ?></td>
                                    <td><?= htmlspecialchars($fine['fine_status']) ?></td>
                                    <td>
                                        <a href="view-fine-details.php?id=<?= htmlspecialchars($fine['id']) ?>"
                                            class="btn">Solve Now</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="9">No reported fines found for the selected filters.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
</main>

<?php include_once "../../../includes/footer.php"; ?>