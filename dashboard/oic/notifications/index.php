<?php
$pageConfig = [
    'title' => 'Notifications',
    'styles' => ["../../dashboard.css", "./notifications.css"],
    'scripts' => ["../../dashboard.js", "../../../notifications/script.js", "./oic-notification-scripts.js"],
    'authRequired' => true
];

session_start();
include_once "../../../includes/header.php";
require_once "../../../db/connect.php";

if ($_SESSION['user']['role'] !== 'oic') {
    die("unauthorized user!");
}

$oic_id = $_SESSION['user']['id'];
?>

<main>
    <?php include_once "../../includes/navbar.php" ?>
    <div class="dashboard-layout">
        <?php include_once "../../includes/sidebar.php" ?>
        <div class="content">
            <button onclick="history.back()" class="back-btn" style="position: absolute; top: 7px; right: 8px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                    <path fill-rule="evenodd"
                        d="M15 8a.5.5 0 0 1-.5.5H3.707l3.147 3.146a.5.5 0 0 1-.708.708l-4-4a.5.5 0 0 1 0-.708l4-4a.5.5 0 1 1 .708.708L3.707 7.5H14.5a.5.5 0 0 1 .5.5z" />
                </svg>
            </button>
            <h1>Notifications</h1>
            <div class="description-section">
                <div class="english">
                    <p style="margin-bottom: 10px">Stay informed about system updates, fine payments, and important
                        department announcements.</p>
                </div>
            </div>

            <div id="notifications-container">
                <!-- Notifications will be loaded here via JavaScript -->
                <div class="loading">Loading notifications...</div>
            </div>
        </div>
    </div>
</main>



<?php include_once "../../../includes/footer.php"; ?></div>