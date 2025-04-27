<?php
$pageConfig = [
    'title' => 'Police Officers',
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



$sql = "SELECT * FROM officers WHERE is_oic = '1' AND id = ? LIMIT 1";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Error preparing statement: " . $conn->error);
}
$stmt->bind_param("i", $oic_id);
$stmt->execute();
$result = $stmt->get_result();


if ($result->num_rows === 0) {
    die("OIC not found or police station not assigned.");
}

$oic_data = $result->fetch_assoc();
$police_station_id = $oic_data['police_station'];


$officer_sql = "
    SELECT id, fname, lname, email, phone_no, nic 
    FROM officers WHERE police_station = ? AND is_oic = 0;
";

$officer_stmt = $conn->prepare($officer_sql);
if (!$officer_stmt) {
    die("Error preparing officer statement: " . $conn->error);
}
$officer_stmt->bind_param("i", $police_station_id);
$officer_stmt->execute();
$officer_result = $officer_stmt->get_result();
$officers = $officer_result->fetch_all(MYSQLI_ASSOC);

$officer_stmt->close();
$stmt->close();
$conn->close();


?>

<main>
    <?php include_once "../../includes/navbar.php" ?>

    <div class="dashboard-layout">
        <?php include_once "../../includes/sidebar.php" ?>
        <div class="content">
            <div class="container x-large no-border">
                <div class="field">
                <h1>Station Officers</h1>
                </div>
            </div>

                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>POLICE ID</th>
                                <th>FULL NAME</th>
                                <th>EMAIL</th>
                                <th>PHONE NO</th>
                                <th>NIC</th>

                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($officers as $officer): ?>
                                <tr>
                                    <td><?= htmlspecialchars($officer['id']) ?></td>
                                    <td><?= htmlspecialchars($officer['fname']) ." ". $officer['lname']?></td>
                                    <td><?= htmlspecialchars($officer['email']) ?></td>
                                    <td><?= htmlspecialchars($officer['phone_no']) ?></td>
                                    <td><?= htmlspecialchars($officer['nic']) ?></td>

                                </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                </div>
            
        </div>
    </div>
</main>

<?php include_once "../../../includes/footer.php"; ?>