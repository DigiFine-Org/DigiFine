<!-- <?php
$pageConfig = [
    'title' => 'Officer Dashboard',
    'styles' => ["./dashboard.css"],
    'scripts' => ["./dashboard.js"],
    'authRequired' => true
];

include_once "../includes/header.php";

$dashboard_user = $_SESSION['user'];

?>

<main>
    <?php include_once "./includes/navbar.php" ?>
    <div class="dashboard-layout">
        <?php include_once "./includes/sidebar.php" ?>
        <div class="content">
            <div class="home-grid">
                <?php if ($dashboard_user['role'] === 'oic'): ?>
                    <a href="" class="tile">
                        <span>oic link 1</span>
                    </a>
                    <a href="" class="tile">
                        <span>oic link 2</span>
                    </a>
                <?php elseif ($dashboard_user['role'] === 'officer'): ?>
                    <a href="" class="tile">
                        <span>officer link 1</span>
                    </a>
                    <a href="" class="tile">
                        <span>officer link 2</span>
                    </a>
                <?php elseif ($dashboard_user['role'] === 'admin'): ?>
                    <a href="" class="tile">
                        <span>admin link 1</span>
                    </a>
                    <a href="" class="tile">
                        <span>admin link 2</span>
                    </a>
                <?php elseif ($dashboard_user['role'] === 'driver'): ?>
                    <a href="" class="tile">
                        <span>driver link 1</span>
                    </a>
                    <a href="" class="tile">
                        <span>driver link 2</span>
                    </a>
                <?php else: ?>
                    <p>Invalid user!</p>
                <?php endif ?>

            </div>
        </div>
</main>

<?php include_once "../includes/footer.php" ?> -->