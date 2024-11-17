<?php
$pageConfig = [
    'title' => 'Government Fine List',
    'styles' => ["../dashboard.css"],
    'scripts' => ["../dashboard.js"],
    'authRequired' => true
];

include "../../db/connect.php";

include_once "../../includes/header.php";
?>

<main>
    <?php include_once "../includes/navbar.php" ?>
    <div class="dashboard-layout">
        <?php include_once "../includes/sidebar.php" ?>
        <div class="content">
            <div class="container x-large no-border">
                
            </div>
        </div>
    </div>
</main>

<?php include_once "../../includes/footer.php" ?>