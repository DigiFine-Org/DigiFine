<?php
$pageConfig = [
    'title' => 'Assign OIC',
    'styles' => ["../../dashboard.css"],
    'scripts' => ["../../dashboard.js"],
    'authRequired' => true
];

include_once "../../../includes/header.php";
require_once "../../../db/connect.php";

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
    <?php include_once "../../includes/navbar.php" ?>

    <div class="dashboard-layout">
        <?php include_once "../../includes/sidebar.php" ?>
        <div class="content">
            <div class="container x-large no-border">
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
                                        <form action="./assign-process.php" method="post">
                                            <input type="hidden" name="police_station_id"
                                                value="<?= $officer['police_station_id'] ?>">
                                            <button type=" submit" name="officer_id" value="<?= $officer['id'] ?>"
                                                style="width:100%" class="btn small">Assign</button>
                                        </form>
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