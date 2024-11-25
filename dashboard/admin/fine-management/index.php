<?php
$pageConfig = [
    'title' => 'Fines',
    'styles' => ["../../dashboard.css"],
    'scripts' => ["../../dashboard.js"],
    'authRequired' => true
];

include_once "../../../includes/header.php";
require_once "../../../db/connect.php";

if ($_SESSION['user']['role'] !== 'admin') {
    die("unauthorized user!");
}

$fines = [];
$stmt = $conn->prepare("SELECT id, police_id, driver_id, license_plate_number, issued_date, issued_time, offence_type, nature_of_offence, offence, fine_status FROM fines");
if (!$stmt->execute()) {
    die("Query error!!!");
}

$result = $stmt->get_result();
$fines = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<main>
    <?php include_once "../../includes/navbar.php" ?>

    <div class="dashboard-layout">
        <?php include_once "../../includes/sidebar.php" ?>
        <div class="content">
            <div class="container x-large no-border">
                <h1>All Fines</h1>
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
                                    <td><?= $fine['police_id'] ?></td>
                                    <td><?= $fine['driver_id'] ?></td>
                                    <td><?= $fine['issued_date'] ?></td>
                                    <td><?= $fine['offence_type'] ?></td>
                                    <td><?= $fine['offence'] ?></td>
                                    <td><?= $fine['fine_status'] ?></td>
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

<?php include_once "../../../includes/footer.php";?>
