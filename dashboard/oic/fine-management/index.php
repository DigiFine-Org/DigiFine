<?php
$pageConfig = [
    'title' => 'Police Station Fines',
    'styles' => ["../../dashboard.css"],
    'scripts' => ["../../dashboard.js"],
    'authRequired' => true
];


require_once "../../../db/connect.php";
include_once "../../../includes/header.php";

$oic_id = $_SESSION['user']['id'] ?? null;

if (!$oic_id) {
    die("Unauthorized access.");
}


// Retrieve OIC's police station ID
$sql = "SELECT * FROM officers WHERE is_oic = '1' AND id = ? LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $oic_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    die("OIC not found or police station not assigned.");
}
$oic_data = $result->fetch_assoc();
$police_station_id = $oic_data['police_station'];

// Fetch fines related to the OIC's police station
$fines_sql = "
    SELECT f.id, f.police_id, f.driver_id, f.license_plate_number, f.issued_date, f.issued_time, 
           f.offence_type, f.nature_of_offence, f.offence, f.fine_status 
    FROM fines f
    INNER JOIN officers o ON f.police_id = o.id
    WHERE o.police_station = ?
";
$fines_stmt = $conn->prepare($fines_sql);
$fines_stmt->bind_param("i", $police_station_id);
$fines_stmt->execute();
$fines_result = $fines_stmt->get_result();
$fines = $fines_result->fetch_all(MYSQLI_ASSOC);

$fines_stmt->close();
$stmt->close();
$conn->close();


?>

<main>
    <?php include_once "../../includes/navbar.php" ?>

    <div class="dashboard-layout">
        <?php include_once "../../includes/sidebar.php" ?>
        <div class="content">
            <div class="container x-large no-border">
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>POLICE ID</th>
                                <th>DRIVER ID</th>
                                <th>ISSUED DATE</th>
                                <th>OFFENCE TYPE</th>
                                <th>OFFENCE</th>
                                <th>FINE STATUS</th>
                                <th>ACTION</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($fines as $fine): ?>
                                <tr>
                                    <td><?= htmlspecialchars($fine['police_id']) ?></td>
                                    <td><?= htmlspecialchars($fine['driver_id']) ?></td>
                                    <td><?= htmlspecialchars($fine['issued_date']) ?></td>
                                    <td><?= htmlspecialchars($fine['offence_type']) ?></td>
                                    <td><?= htmlspecialchars($fine['offence']) ?></td>
                                    <td><?= htmlspecialchars($fine['fine_status']) ?></td>
                                    </td>
                                    <td>
                                        <a href="view-fine-details.php?id=<?= htmlspecialchars($fine['id']) ?>"
                                            class="btn">View</a>
                                    </td>
                                </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include_once "../../../includes/footer.php"; ?>