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
        <div class="content">
            <div class="home-grid">
                <a href="" class="tile">
                    <span>Link 1</span>
                </a>
                <a href="" class="tile">
                    <span>Link 2</span>
                </a>
                <a href="" class="tile">
                    <span>Link 3</span>
                </a>
                <a href="" class="tile">
                    <span>Link 4</span>
                </a>
            </div>
        </div>
    </div>
</main>

<?php include_once "../../../includes/footer.php" ?>