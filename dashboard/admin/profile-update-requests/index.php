<?php
$pageConfig = [
    'title' => 'Profile Update Requests',
    'styles' => ["../../dashboard.css"],
    'scripts' => ["../../dashboard.js"],
    'authRequired' => true
];

require_once "../../../db/connect.php";
include_once "../../../includes/header.php";

if ($_SESSION['user']['role'] !== 'admin') {
    die("unauthorized user!");
}

// Fetch update requests for officers and drivers
$officer_requests = [];
$driver_requests = [];

try {
    // Officer update requests
    $sql_officers = "SELECT * FROM update_officer_profile_requests";
    $result_officers = $conn->query($sql_officers);
    if ($result_officers) {
        $officer_requests = $result_officers->fetch_all(MYSQLI_ASSOC);
    }

    // Driver update requests
    $sql_drivers = "SELECT * FROM update_driver_profile_requests";
    $result_drivers = $conn->query($sql_drivers);
    if ($result_drivers) {
        $driver_requests = $result_drivers->fetch_all(MYSQLI_ASSOC);
    }
} catch (Exception $e) {
    die("Error fetching requests: " . $e->getMessage());
}

?>

<main>
    <?php include_once "../../includes/navbar.php" ?>

    <div class="dashboard-layout">
        <?php include_once "../../includes/sidebar.php" ?>
        <div class="content">
            <div class="container x-large no-border">
                <!-- Driver Update Requests -->
                <h2>Driver Profile Update Requests</h2>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>NIC</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($driver_requests as $request): ?>
                                <tr>
                                    <td><?= htmlspecialchars($request['id']) ?></td>
                                    <td><?= htmlspecialchars($request['fname']) ?></td>
                                    <td><?= htmlspecialchars($request['lname']) ?></td>
                                    <td><?= htmlspecialchars($request['email']) ?></td>
                                    <td><?= htmlspecialchars($request['phone_no']) ?></td>
                                    <td><?= htmlspecialchars($request['nic']) ?></td>
                                    <td>
                                        <form action="view-update-request-details.php" method="get">
                                            <input type="hidden" name="type" value="driver">
                                            <input type="hidden" name="id" value="<?= htmlspecialchars($request['id']) ?>">
                                            <button type="submit" class="btn" style="margin-bottom: 5px;">View
                                                Details</button>
                                        </form>
                                    </td>

                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include_once "../../includes/footer.php" ?>