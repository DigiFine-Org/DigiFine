<?php
$pageConfig = [
    'title' => 'Tell IGP',
    'styles' => ["../../dashboard.css", "./driver-dashboard.css"],
    'scripts' => ["../../dashboard.js"],
    'authRequired' => true
];

include_once "../../../includes/header.php";
require_once "../../../db/connect.php";


if ($_SESSION['user']['role'] !== 'driver') {
    die("unauthorized user!");
}

$driverId = $_SESSION['user']['id'] ?? null;

if (!$driverId) {
    die("Unauthorized access.");
}
?>

<main>
    <?php include_once "../../includes/navbar.php" ?>
    <div class="dashboard-layout">
        <?php include_once "../../includes/sidebar.php" ?>
        <div class="content">
            <div class="container">
                <h1>Tell Inspector General of Police</h1>
                <h3>Complaint Directly to IGP</h3>
                <form action="">
                    <div class="field">
                        <label for="">Your District:</label>
                        <input type="text" class="input">
                    </div>
                    <div class="field">
                        <label for="">Nearest Police Station:</label>
                        <input type="text" class="input">
                    </div>
                    <div class="field">
                        <label for=" ">Complaint:</label>
                        <input type="text" class="input">
                    </div>
                    <div class="field">
                        <label for="">Your Name:</label>
                        <input type="text" class="input">
                    </div>
                    <div class="field">
                        <label for="">Address:</label>
                        <input type="text" class="input">
                    </div>
                    <div class="field">
                        <label for="">NIC Number:</label>
                        <input type="text" class="input">
                    </div>
                    <div class="field">
                        <label for="">Contact Number:</label>
                        <input type="text" class="input">
                    </div>
                    <div class="field">
                        <label for="">Email Address:</label>
                        <input type="text" class="input">
                    </div>
                    <div class="field">
                        <label for="">Complaint Subject:</label>
                        <input type="text" class="input">
                    </div>

                    <button class="btn">Submit</button>
                </form>
            </div>

        </div>
</main>

<?php include_once "../../../includes/footer.php" ?>