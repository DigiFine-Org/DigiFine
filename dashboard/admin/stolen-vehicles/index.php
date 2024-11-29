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


?>

<main>
    <?php include_once "../../includes/navbar.php" ?>

    <div class="dashboard-layout">
        <?php include_once "../../includes/sidebar.php" ?>
        <div class="content">
            
        </div>
    </div>
</main>

<?php include_once "../../includes/footer.php" ?>