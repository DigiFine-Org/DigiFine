<?php
$pageConfig = [
    'title' => 'Traffic-Signs',
    'styles' => ["../../dashboard.css", "./driver-dashboard.css"],
    'scripts' => ["../../dashboard.js"],
    'authRequired' => true
];

include_once "../../../includes/header.php";
require_once "../../../db/connect.php";


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
            <div style="text-align: center;">
                <iframe src="/digifine/assets/traffic-signs.pdf" width="100%" height="600px"
                    style="border: none;"></iframe>
            </div>


        </div>
</main>

<?php include_once "../../../includes/footer.php" ?>