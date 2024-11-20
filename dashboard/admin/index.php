<?php
$pageConfig = [
    'title' => 'Ad',
    'styles' => ["../dashboard.css"],
    'scripts' => ["../dashboard.js"],
    'authRequired' => true
];

include_once "../../includes/header.php";
require_once "../../db/connect.php";

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