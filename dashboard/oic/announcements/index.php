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

if ($_SESSION['user']['role'] !== 'oic') {
    die("unauthorized user!");
}

if ($_SESSION['message'] ?? null) {
    if ($_SESSION['message'] === 'success') {
        $message = "Announcement published successfully!";
        unset($_SESSION['message']); // Clear the session message
        include '../../../includes/alerts/success.php';
    } else {
        $message = $_SESSION['message']; // Store the message
        unset($_SESSION['message']); // Clear the session message

        // Include the alert.php file to display the message
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
                <div id="alert-container"></div> <!-- Alert container -->
                <h1>Add Announcement</h1>
                <form action="process.php" method="POST">
                    <div class="field">
                        <label for="">Title</label>
                        <input type="text" name="title" placeholder="Title" required>
                    </div>

                    <div class="field">
                        <label for="">Message</label>
                        <textarea name="message" placeholder="Message" required></textarea>
                    </div>
                    <div class="field">
                        <label for="">Target Role</label>
                        <select name="target_role" required>
                            <option value="all">All Users</option>
                            <option value="admin">Admin</option>
                            <option value="oic">OIC</option>
                            <option value="officer">Officer</option>
                            <option value="driver">Driver</option>
                            <!-- if driver to which area option -->
                        </select>
                    </div>
                    <div class="field">
                        <label for="">Expires At(Optional)</label>
                        <input type="datetime-local" name="expires_at">

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