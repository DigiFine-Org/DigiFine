<?php
$pageConfig = [
    'title' => 'Add Offence',
    'styles' => ["../../dashboard.css"],
    'scripts' => ["../../dashboard.js"],
    'authRequired' => true
];

require_once "../../../db/connect.php";
include_once "../../../includes/header.php";

if ($_SESSION['user']['role'] !== 'admin') {
    die("unauthorized user!");
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
                <h1>Add Offence</h1>
                <form action="add-offence-process.php" method="post">
                    <div class="field">
                        <label for="">Offence Number</label>
                        <input type="text" class="input" placeholder="001" name="offence_number" required>
                    </div>
                    <div class="field">
                        <label for="">Offence Description (Sinhala):</label>
                        <input type="text" class="input" placeholder="හඳුනාගැනීමේ තහඩු" name="description_sinhala"
                            required>
                    </div>
                    <div class="field">
                        <label for="">Offence Description (Tamil):</label>
                        <input type="text" class="input" placeholder="அடையாளங்காணல் தகடுகள்" name="description_tamil"
                            required>
                    </div>
                    <div class="field">
                        <label for="">Offence Description (English):</label>
                        <input type="text" class="input" placeholder="Identification Plates" name="description_english"
                            required>
                    </div>
                    <div class="field">
                        <label for="">Points Deducted:</label>
                        <input type="text" class="input" placeholder="4" name="points_deducted" required>
                    </div>
                    <div class="field">
                        <label for="">Fine Amount(RS):</label>
                        <input type="text" class="input" placeholder="1000.00" name="fine" required>
                    </div>
                    <button class="btn">Add</button>
                </form>
            </div>
        </div>
    </div>
</main>

<?php include_once "../../../includes/footer.php" ?>