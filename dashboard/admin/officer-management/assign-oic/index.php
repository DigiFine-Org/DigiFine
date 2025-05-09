<?php
$pageConfig = [
    'title' => 'Assign OIC',
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

if ($_SESSION['message'] ?? null) {
    if ($_SESSION['message'] === 'OIC assigned successfully!') {
        $message = $_SESSION['message']; // Store the message
        unset($_SESSION['message']); // Clear the session message
        include '../../../../includes/alerts/success.php';
    } else {
        $message = $_SESSION['message']; // Store the message
        unset($_SESSION['message']); // Clear the session message

        // Include the alert.php file to display the message
        include '../../../../includes/alerts/failed.php';
    }
}

?>

<main>
    <?php include_once "../../../includes/navbar.php" ?>

    <div class="dashboard-layout">
        <?php include_once "../../../includes/sidebar.php" ?>
        <div class="content">
            <button onclick="history.back()" class="back-btn" style="position: absolute; top: 7px; right: 8px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                    <path fill-rule="evenodd"
                        d="M15 8a.5.5 0 0 1-.5.5H3.707l3.147 3.146a.5.5 0 0 1-.708.708l-4-4a.5.5 0 0 1 0-.708l4-4a.5.5 0 1 1 .708.708L3.707 7.5H14.5a.5.5 0 0 1 .5.5z" />
                </svg>
            </button>
            <div class="">
                <h2>Assign OIC for Police Stations</h2>
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
                                                style="width:100%" class="btn">Assign</button>
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

<?php include_once "../../../../includes/footer.php"; ?>