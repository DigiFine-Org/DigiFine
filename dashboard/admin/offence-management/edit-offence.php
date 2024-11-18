<?php
$pageConfig = [
    'title' => 'Add Offence',
    'styles' => ["../../dashboard.css"],
    'scripts' => ["../../dashboard.js"],
    'authRequired' => true
];

require_once "../../../db/connect.php";
include_once "../../../includes/header.php";


$offence = null;
if (isset($_GET['offence_number']) && is_numeric($_GET['offence_number'])) {
    $offence_number = $_GET['offence_number'];

    $stmt = $conn->prepare("SELECT *FROM offences WHERE offence_number = ?");
    if($stmt) {
        $stmt->bind_param("i", $offence_number);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result && $result->num_rows > 0) {
            $offence = $result->fetch_assoc();
        }
        $stmt->close();
    }
}

if(!$offence) {
    echo "<script>alert('Offence not found!'); window.location.href = '/digifine/dashboard/admin/offence-management/index.php';</script>";
    exit();
}

?>

<main>
    <?php include_once "../../includes/navbar.php" ?>
    <div class="dashboard-layout">
        <?php include_once "../../includes/sidebar.php" ?>
        <div class="content">
            <div class="container">
                <h1>Edit Offence</h1>
                <form action="edit-offence-process.php" method="post">
                ...<div class="field">
                        <label for="">Offence Number</label>
                        <input type="text" class="input" placeholder="001" name="offence_number" value="<?= htmlspecialchars($offence['offence_number']) ?>" readonly required>
                    </div>
                    <div class="field">
                        <label for="">Offence Description (Sinhala):</label>
                        <input type="text" class="input" placeholder="හඳුනාගැනීමේ තහඩු" name="description_sinhala" value="<?= htmlspecialchars($offence['description_sinhala']) ?>"  required>
                    </div>
                    <div class="field">
                        <label for="">Offence Description (Tamil):</label>
                        <input type="text" class="input" placeholder="அடையாளங்காணல் தகடுகள்" name="description_tamil" value="<?= htmlspecialchars($offence['description_tamil']) ?>" required>
                    </div>
                    <div class="field">
                        <label for="">Offence Description (English):</label>
                        <input type="text" class="input" placeholder="Identification Plates" name="description_english" value="<?= htmlspecialchars($offence['description_english']) ?>" required>
                    </div>
                    <div class="field">
                        <label for="">Points Deducted:</label>
                        <input type="text" class="input" placeholder="4" name="points_deducted" value="<?= htmlspecialchars($offence['points_deducted']) ?>" required>
                    </div>
                    <div class="field">
                        <label for="">Fine Amount(RS):</label>
                        <input type="text" class="input" placeholder="1000.00" name="fine" value="<?= htmlspecialchars($offence['fine']) ?>" required>
                    </div>
            </div>
        </div>
    </div>
</main>
