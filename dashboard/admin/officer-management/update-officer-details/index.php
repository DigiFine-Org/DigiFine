<?php
$pageConfig = [
    'title' => 'Police Officer Details',
    'styles' => ["../../../dashboard.css"],
    'scripts' => ["../../../dashboard.js"],
    'authRequired' => true
];

include_once "../../../../includes/header.php";
require_once "../../../../db/connect.php";

if ($_SESSION['user']['role'] !== 'admin') {
    die("unauthorized user!");
}

$officers = [];
$stmt = $conn->prepare("SELECT po.id, po.fname, po.lname, po.phone_no, ps.id as police_station_id, ps.name as police_station_name FROM officers as po INNER JOIN police_stations as ps ON po.police_station=ps.id
  WHERE po.is_oic=0");
if (!$stmt->execute()) {
    die("Query error!!!");
}

$result = $stmt->get_result();
$officers = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();



?>

<main>
    <?php include_once "../../../includes/navbar.php" ?>

    <div class="dashboard-layout">
        <?php include_once "../../../includes/sidebar.php" ?>
        <div class="content">
            <div class="">
                <h2>Police Officer Details</h2>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Name</th>
                                <th>Phone no</th>
                                <th>Police Station</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($officers as $officer): ?>
                                <tr>
                                    <td><?= $officer['id'] ?></td>
                                    <td><?= $officer['fname'] . " " . $officer['lname'] ?></td>
                                    <td><?= $officer['phone_no'] ?></td>
                                    <td><?= $officer['police_station_name'] . "(" . $officer['police_station_id'] . ")" ?>
                                    </td>
                                    <td>
                                        <a
                                            href="/digifine/dashboard/admin/officer-management/update-officer-details/view/index.php?id=<?= $officer['id'] ?>"><button
                                                type=" button" name="officer_id" style="width:100%"
                                                class="btn">View</button></a>
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

<?php include_once "../../../../includes/footer.php"; ?>