<?php
$pageConfig = [
    'title' => 'Police Station Vehicle',
    'styles' => ["../../dashboard.css"],
    'scripts' => ["../../dashboard.js"],
    'authRequired' => true
];

require_once "../../../db/connect.php";
include_once "../../../includes/header.php";

if ($_SESSION['user']['role'] !== 'oic') {
    die("Unauthorized user!");
}

$oic_id = $_SESSION['user']['id'] ?? null;

if (!$oic_id) {
    die("Unauthorized access.");
}

// Retrieve OIC's police station ID
$sql = "SELECT police_station FROM officers WHERE id = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Query preparation failed: " . $conn->error);
}

$stmt->bind_param("i", $oic_id);
$stmt->execute();
$result = $stmt->get_result();
$station = $result->fetch_assoc();

if (!$station) {
    die("OIC's police station not found.");
}

$police_station_id = $station['police_station'];
$stmt->close();

// Retrieve seized vehicles related to OIC's police station
$sql = "SELECT s.license_plate_number, 
               o.id AS officer_id, 
                s.officer_name, 
               s.seizure_date_time, 
               s.seized_location
        FROM seized_vehicle s
        INNER JOIN police_stations ps ON ps.name = s.police_station
        INNER JOIN officers o ON o.police_station = ps.id
        WHERE ps.id = ?
        GROUP BY s.license_plate_number, s.seizure_date_time, s.seized_location"; 

$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Query preparation failed: " . $conn->error);
}

$stmt->bind_param("i", $police_station_id);
$stmt->execute();
$result = $stmt->get_result();

$vehicles = [];
while ($row = $result->fetch_assoc()) {
    $vehicles[] = $row;
}

$stmt->close();
$conn->close();
?>

<main>
    <?php include_once "../../includes/navbar.php"; ?>

    <div class="dashboard-layout">
        <?php include_once "../../includes/sidebar.php"; ?>
        <div class="content">
            <div class="container x-large no-border">
                <div class="field">
                    <h1>Seized Vehicles</h1>
                </div>
            </div>

            
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>License Plate Number</th>
                            <th>Officer ID</th>
                            <th>Officer Name</th>
                            <th>Date and Time</th>
                            <th>Seized Location</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($vehicles)): ?>
                            <?php foreach ($vehicles as $vehicle): ?>
                                <tr>
                                    <td><?= htmlspecialchars($vehicle['license_plate_number']) ?></td>
                                    <td><?= htmlspecialchars($vehicle['officer_id']) ?></td>
                                    <td><?= htmlspecialchars($vehicle['officer_name']) ?></td>
                                    <td><?= htmlspecialchars($vehicle['seizure_date_time']) ?></td>
                                    <td><?= htmlspecialchars($vehicle['seized_location']) ?></td>
                                    <td>
                                        <a href="release-vehicle.php?id=<?= htmlspecialchars($officer['id']) ?>"
                                            class="btn">Release</a>
                                    </td> 
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6">No seized vehicles found for this police station.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<?php include_once "../../../includes/footer.php"; ?>