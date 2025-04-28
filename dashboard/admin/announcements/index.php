<?php
$pageConfig = [
    'title' => 'Announcements',
    'styles' => ["../../dashboard.css"],
    'scripts' => ["../../dashboard.js"],
    'authRequired' => true
];
session_start();
include_once "../../../includes/header.php";
require_once "../../../db/connect.php";

if ($_SESSION['user']['role'] !== 'admin') {
    die("unauthorized user!");
}

if ($_SESSION['message'] ?? null) {
    if ($_SESSION['message'] === 'success') {
        $message = "Announcement published successfully!";
        unset($_SESSION['message']);
        include '../../../includes/alerts/success.php';
    } else {
        $message = $_SESSION['message'];
        unset($_SESSION['message']);
        include '../../../includes/alerts/failed.php';
    }
}


?>

<main>
    <?php include_once "../../includes/navbar.php" ?>
    <div class="dashboard-layout">
        <?php include_once "../../includes/sidebar.php" ?>
        <div class="content">
            <div class="container">
                <button onclick="history.back()" class="back-btn" style="position: absolute; top: 7px; right: 8px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        viewBox="0 0 16 16">
                        <path fill-rule="evenodd"
                            d="M15 8a.5.5 0 0 1-.5.5H3.707l3.147 3.146a.5.5 0 0 1-.708.708l-4-4a.5.5 0 0 1 0-.708l4-4a.5.5 0 1 1 .708.708L3.707 7.5H14.5a.5.5 0 0 1 .5.5z" />
                    </svg>
                </button>
                <div id="alert-container"></div>
                <h1>Add Announcement</h1>
                <form action="process.php" method="POST">
                    <div class="field">
                        <label for="">Title</label>
                        <input type="text" class="input" name="title" placeholder="Title" required>
                    </div>

                    <div class="field">
                        <label for="">Message</label>
                        <textarea class="input" name="message" placeholder="Message" style="min-height: 200px; width: 100%;" required></textarea>
                    </div>
                    <div class="field">
                        <label for="">Target Role</label>
                        <select name="target_role" class="input" required>
                            <option value="all">All Users</option>
                            <option value="admin">Admin</option>
                            <option value="oic">OIC</option>
                            <option value="officer">Officer</option>
                            <option value="driver">Driver</option>
                        </select>
                    </div>
                    <div class="field">
                        <label for="">Expires At(Optional)</label>
                        <input type="datetime-local" class="input" name="expires_at">

                    </div>

                    <div class="field">
                        <button class="btn">Add Announcement</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</main>

<?php include_once "../../../includes/footer.php"; ?>