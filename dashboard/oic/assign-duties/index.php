<?php
$pageConfig = [
    'title' => 'Assign Duties',
    'styles' => ["../../dashboard.css"],
    'scripts' => ["../../dashboard.js"],
    'authRequired' => true
];

include_once "../../../includes/header.php";
if ($_SESSION['user']['role'] !== 'oic') {
    die("Unauthorized user!");
}

$result = "";

if (isset($_GET)) {
    $result = $_GET['query'] ?? "";
}
?>

<main>
    <?php include_once "../../includes/navbar.php"; ?>
    <div class="dashboard-layout">
        <?php include_once "../../includes/sidebar.php"; ?>
        <div class="content">
            <div class="container">
                <h1>Assign Duty</h1>
                <form action="assign-duty-handler.php" method="POST">
                    <div class="field">
                        <label for="policeId">Police ID:</label>
                        <input type="text" name="policeId" class="input" required>
                    </div>
                    <div class="field">
                        <label for="duty">Duty:</label>
                        <input type="text" name="duty" class="input" required>
                    </div>
                    <div class="field">
                        <label for="dutyDate">Duty Date:</label>
                        <input type="date" name="dutyDate" class="input" id="dutyDate" min="<?= date('Y-m-d') ?>" required>
                    </div>
                    <div class="field">
                        <label for="notes">Additional Notes:</label>
                        <textarea name="notes" id="notes"></textarea>
                    </div>
                    <button class="btn">Assign Duty</button>
                </form>
                
                <?php if (isset($_SESSION['success'])): ?>
                    <div class="success-message"><?php echo $_SESSION['success']; ?></div>
                    <?php unset($_SESSION['success']); ?>
                <?php endif; ?>

                <?php if (isset($_SESSION['error'])): ?>
                    <div class="error-message"><?php echo $_SESSION['error']; ?></div>
                    <?php unset($_SESSION['error']); ?>
                <?php endif; ?>

            </div>
        </div>
    </div>
</main>

<?php include_once "../../../includes/footer.php"; ?>
