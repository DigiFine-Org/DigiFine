<?php
$pageConfig = [
    'title' => 'Add Offence',
    'styles' => ["../../dashboard.css"],
    'scripts' => ["../../dashboard.js"],
    'authRequired' => true
];

require_once "../../../db/connect.php";
include_once "../../../includes/header.php";

?>

<main>
    <?php include_once "../../includes/navbar.php" ?>
    <div class="dashboard-layout">
        <?php include_once "../../includes/sidebar.php" ?>
        <div class="content">
            <div class="container">
                <h1>Add Offence</h1>
                <form action="add-offence-process.php" method="post">
                    <div class="field">
                        <label for="">Offence Number</label>
                        <input type="text" class="input" placeholder="001" name="offence_number" required>
                    </div>
                    <div class="field">
                        <label for="">Offence Description (Sinhala):</label>
                        <input type="text" class="input" placeholder="හඳුනාගැනීමේ තහඩු" name="description_sinhala" required>
                    </div>
                    <div class="field">
                        <label for="">Offence Description (Tamil):</label>
                        <input type="text" class="input" placeholder="அடையாளங்காணல் தகடுகள்" name="description_tamil" required>
                    </div>
                    <div class="field">
                        <label for="">Offence Description (English):</label>
                        <input type="text" class="input" placeholder="Identification Plates" name="description_english" required>
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
