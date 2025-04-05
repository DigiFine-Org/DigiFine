<?php

$pageConfig = [
    'title' => 'View Update Request Details',
    'styles' => ["../../dashboard.css"],
    'scripts' => ["../../dashboard.js"],
    'authRequired' => true
];

require_once "../../../db/connect.php";
include_once "../../../includes/header.php";


if ($_SESSION['user']['role'] !== 'admin') {
    die("unauthorized user!");
}

$type = $_GET['type'] ?? null;
$id = $_GET['id'] ?? null;

if (!$type || !$id) {
    die("Invalid request");
}

// Fetch data from `dmt_drivers` (current data)
$current_data = [];
$sql_current = "SELECT * FROM dmt_drivers WHERE id = ?";
$stmt_current = $conn->prepare($sql_current);
$stmt_current->bind_param("s", $id);
$stmt_current->execute();
$result_current = $stmt_current->get_result();
if ($result_current->num_rows > 0) {
    $current_data = $result_current->fetch_assoc();
}
$stmt_current->close();

// Fetch data from `update_driver_profile_requests` (requested updates)
$requested_data = [];
$sql_requested = "SELECT * FROM update_driver_profile_requests WHERE id = ?";
$stmt_requested = $conn->prepare($sql_requested);
$stmt_requested->bind_param("s", $id);
$stmt_requested->execute();
$result_requested = $stmt_requested->get_result();
if ($result_requested->num_rows > 0) {
    $requested_data = $result_requested->fetch_assoc();
}
$stmt_requested->close();

$conn->close();
?>

<main>
    <?php include_once "../../includes/navbar.php" ?>

    <div class="dashboard-layout">
        <?php include_once "../../includes/sidebar.php" ?>
        <div class="content">
            <div class="container large">
                <!-- <h1>Compare Driver Details</h1>

                <h2>Current Data</h2>
                <table>
                    <tr>
                        <th>First Name</th>
                        <td><?= htmlspecialchars($current_data['fname']) ?></td>
                    </tr>
                    <tr>
                        <th>Last Name</th>
                        <td><?= htmlspecialchars($current_data['lname']) ?></td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td><?= htmlspecialchars($current_data['email']) ?></td>
                    </tr>
                    <tr>
                        <th>Phone Number</th>
                        <td><?= htmlspecialchars($current_data['phone_no']) ?></td>
                    </tr>
                    <tr>
                        <th>NIC</th>
                        <td><?= htmlspecialchars($current_data['nic']) ?></td>
                    </tr>
                </table> -->

                <h2>Requested Updates</h2>
                <table>
                    <tr>
                        <th>First Name</th>
                        <td><?= htmlspecialchars($requested_data['fname'] ?? 'N/A') ?></td>
                    </tr>
                    <tr>
                        <th>Last Name</th>
                        <td><?= htmlspecialchars($requested_data['lname'] ?? 'N/A') ?></td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td><?= htmlspecialchars($requested_data['email'] ?? 'N/A') ?></td>
                    </tr>
                    <tr>
                        <th>Phone Number</th>
                        <td><?= htmlspecialchars($requested_data['phone_no'] ?? 'N/A') ?></td>
                    </tr>
                    <tr>
                        <th>NIC</th>
                        <td><?= htmlspecialchars($requested_data['nic'] ?? 'N/A') ?></td>
                    </tr>
                </table>

                <form action="process-update-request.php" method="post" style="margin-top: 20px;">
                    <input type="hidden" name="type" value="driver">
                    <input type="hidden" name="id" value="<?= htmlspecialchars($id) ?>">
                    <div class="wrapper">
                        <button type="submit" name="action" value="approve" class="btn"
                            style="margin-right: 10px">Approve</button>
                        <button type="submit" name="action" value="reject" class="deletebtn">Reject</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>

<?php include_once "../../../includes/footer.php" ?>