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
                <button onclick="history.back()" class="back-btn" style="position: absolute; top: 7px; right: 8px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        viewBox="0 0 16 16">
                        <path fill-rule="evenodd"
                            d="M15 8a.5.5 0 0 1-.5.5H3.707l3.147 3.146a.5.5 0 0 1-.708.708l-4-4a.5.5 0 0 1 0-.708l4-4a.5.5 0 1 1 .708.708L3.707 7.5H14.5a.5.5 0 0 1 .5.5z" />
                    </svg>
                </button>
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