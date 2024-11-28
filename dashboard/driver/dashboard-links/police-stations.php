<?php

$pageConfig = [
    'title' => 'Police Stations',
    'styles' => ["../../dashboard.css", "../driver-dashboard.css"],
    'scripts' => ["../dashboard.js"],
    'authRequired' => true
];

require_once "../../../db/connect.php";

try {
    // Query to fetch police station data
    $sql = "SELECT id, name, province, telephone FROM police_stations";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Query preparation failed: " . $conn->error);
    }
    $stmt->execute();
    $stmt->bind_result($id, $name, $province, $telephone);
    $police_stations = [];

    while ($stmt->fetch()) {
        $police_stations[] = [
            'id' => $id,
            'name' => $name,
            'province' => $province,
            'telephone' => $telephone
        ];
    }

    $stmt->close();
    $conn->close();

} catch (mysqli_sql_exception $e) {
    die("Error: " . $e->getMessage());
}

include_once "../../../includes/header.php";


if ($_SESSION['user']['role'] !== 'driver') {
    die("unauthorized user!");
}

$driverId = $_SESSION['user']['id'] ?? null;

if (!$driverId) {
    die("Unauthorized access.");
}
?>

<main>
    <?php include_once "../../includes/navbar.php" ?>
    <div class="dashboard-layout">
        <?php include_once "../../includes/sidebar.php" ?>
        <div class="content">
            <h1>Police Stations</h1>
            <div class="province-grid" style="">
                <a href="">
                    <div class="province-button">
                        <span>Western Province Police Stations</span>
                    </div>
                </a>
                <a href="">
                    <div class="province-button">
                        <span>Western Province Police Stations</span>
                    </div>
                </a>
                <a href="">
                    <div class="province-button">
                        <span>Central Province Police Stations</span>
                    </div>
                </a>
                <a href="">
                    <div class="province-button">
                        <span>Uva Province Police Stations</span>
                    </div>
                </a>
                <a href="">
                    <div class="province-button">
                        <span>Sabaragamuwa Province Police Stations</span>
                    </div>
                </a>
                <a href="">
                    <div class="province-button">
                        <span>North Western Province Police Stations</span>
                    </div>
                </a>
                <a href="">
                    <div class="province-button">
                        <span>North Central Province Police Stations</span>
                    </div>
                </a>
                <a href="">
                    <div class="province-button">
                        <span>Northern Province Police Stations</span>
                    </div>
                </a>
                <a href="">
                    <div class="province-button">
                        <span>Eastern Province Police Stations</span>
                    </div>
                </a>
            </div>
        </div>
    </div>
</main>

<?php include_once "../../../includes/footer.php" ?>