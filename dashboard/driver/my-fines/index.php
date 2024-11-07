<?php
$pageConfig = [
    'title' => 'Driver Fines',
    'styles' => ["../../dashboard.css"],
    'scripts' => ["../../dashboard.js"],
    'authRequired' => true
];

include_once "../../../includes/header.php";
?>

<main>
    <?php include_once "../../includes/navbar.php" ?>
    <div class="dashboard-layout">
        <?php include_once "../../includes/sidebar.php" ?>
        <div class="content">
            <p class="p">
                my fines
            </p>
        </div>
    </div>
</main>

<?php include_once "../../../includes/footer.php" ?>