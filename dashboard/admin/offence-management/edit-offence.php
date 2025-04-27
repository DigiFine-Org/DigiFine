<?php
session_start();
require_once "../../../db/connect.php";

$pageConfig = [
    'title' => 'Edit Offence',
    'styles' => ["../../dashboard.css", "offence.css"],
    'scripts' => ["../../dashboard.js"],
    'authRequired' => true
];

include_once "../../../includes/header.php";

if ($_SESSION['user']['role'] !== 'admin') {
    die("Unauthorized user!");
}

// Check if offence exists
$offence = null;
if (isset($_GET['offence_number']) && is_numeric($_GET['offence_number'])) {
    $offence_number = $_GET['offence_number'];

    $stmt = $conn->prepare("SELECT * FROM offences WHERE offence_number = ?");
    if ($stmt) {
        $stmt->bind_param("i", $offence_number);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result && $result->num_rows > 0) {
            $offence = $result->fetch_assoc();
        }
        $stmt->close();
    }
}

if (!$offence) {
    $_SESSION['error_message'] = "Offence not found!";
    header("Location: /digifine/dashboard/admin/offence-management/index.php");
    exit();
}

// Check for errors passed from process file
$errors = $_SESSION['form_errors'] ?? [];
$values = $_SESSION['form_values'] ?? $offence;
unset($_SESSION['form_errors']);
unset($_SESSION['form_values']);
?>

<main>
    <?php include_once "../../includes/navbar.php" ?>
    <div class="dashboard-layout">
        <?php include_once "../../includes/sidebar.php" ?>
        <div class="content">
            <div class="container">
                <button onclick="history.back()" class="back-btn" style="position: absolute; top: 7px; right: 8px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M15 8a.5.5 0 0 1-.5.5H3.707l3.147 3.146a.5.5 0 0 1-.708.708l-4-4a.5.5 0 0 1 0-.708l4-4a.5.5 0 1 1 .708.708L3.707 7.5H14.5a.5.5 0 0 1 .5.5z"/>
                    </svg>
                </button>
                <h1>Edit Offence</h1>
                
                <?php if (!empty($errors)): ?>
                    <div class="error-messages" >
                        <?php foreach ($errors as $error): ?>
                            <p><?= $error ?></p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                
                <form action="edit-offence-process.php?offence_number=<?= $offence['offence_number'] ?>" method="post">
                    <div class="field">
                        <label>Offence Number</label>
                        <input type="text" class="input" placeholder="001" name="offence_number" 
                               value="<?= htmlspecialchars($values['offence_number']) ?>" readonly required>
                    </div>
                    <div class="field">
                        <label>Offence Description (Sinhala):</label>
                        <input type="text" class="input" placeholder="හඳුනාගැනීමේ තහඩු" name="description_sinhala"
                               value="<?= htmlspecialchars($values['description_sinhala']) ?>" required>
                        <?php if (isset($errors['description_sinhala'])): ?>
                            <small class="error" style="color: red;"><?= $errors['description_sinhala'] ?></small>
                        <?php endif; ?>
                    </div>
                    <div class="field">
                        <label>Offence Description (Tamil):</label>
                        <input type="text" class="input" placeholder="அடையாளங்காணல் தகடுகள்" name="description_tamil"
                               value="<?= htmlspecialchars($values['description_tamil']) ?>" required>
                        <?php if (isset($errors['description_tamil'])): ?>
                            <small class="error" style="color: red;"><?= $errors['description_tamil'] ?></small>
                        <?php endif; ?>
                    </div>
                    <div class="field">
                        <label>Offence Description (English):</label>
                        <input type="text" class="input" placeholder="Identification Plates" name="description_english"
                               value="<?= htmlspecialchars($values['description_english']) ?>" required>
                        <?php if (isset($errors['description_english'])): ?>
                            <small class="error" style="color: red;"><?= $errors['description_english'] ?></small>
                        <?php endif; ?>
                    </div>
                    <div class="field">
                        <label>Points Deducted:</label>
                        <input type="text" class="input" placeholder="4" name="points_deducted"
                               value="<?= htmlspecialchars($values['points_deducted'] ?? '') ?>" required>
                        <?php if (isset($errors['points_deducted'])): ?>
                            <small class="error" style="color: red;"><?= $errors['points_deducted'] ?></small>
                        <?php endif; ?>
                    </div>
                    <div class="field">
                        <label>Fine Amount(RS):</label>
                        <input type="text" class="input" placeholder="1000.00" name="fine_amount"
                               value="<?= htmlspecialchars($values['fine_amount'] ?? '') ?>" required>
                        <?php if (isset($errors['fine_amount'])): ?>
                            <small class="error" style="color: red;"><?= $errors['fine_amount'] ?></small>
                        <?php endif; ?>
                    </div>
                    <button type="submit" class="btn">Update</button>
                </form>
            </div>
        </div>
    </div>
</main>

<?php include_once "../../../includes/footer.php" ?>