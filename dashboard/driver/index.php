<?php
$pageConfig = [
    'title' => 'Payments',
    'styles' => ["../dashboard.css"],
    'scripts' => ["../dashboard.js"],
    'authRequired' => true
];

include_once "../../includes/header.php";

if ($_SESSION['user']['role'] !== 'driver') {
    die("unauthorized user!");
}
?>

<main>
    <?php include_once "../includes/navbar.php" ?>
    <div class="dashboard-layout">
        <?php include_once "../includes/sidebar.php" ?>
        <div class="content">
            <div class="content">
                <div class="home-grid">
                    
                </div>
            </div>
        </div>
</main>

<?php include_once "../../includes/footer.php" ?>