<?php
$pageConfig = [
    'title' => 'DAssign Duties',
    'styles' => ["../../dashboard.css"],
    'scripts' => ["../../dashboard.js"],
    'authRequired' => true
];

include_once "../../../includes/header.php";
if ($_SESSION['user']['role'] !== 'oic') {
    die("unauthorized user!");
}

$result = "";

if (isset($_GET)) {
    $result = $_GET['query'] ?? "";
}
?>

<main>
    <?php include_once "../../includes/navbar.php" ?>
    <div class="dashboard-layout">
        <?php include_once "../../includes/sidebar.php" ?>
        <div class="content">
            <div class="container x-large no-border">
                <h1>Assign Duty</h1>
                <form action="generate-e-ticket-process.php" method="POST">
                    <div class="field">
                        <label for="">Police ID:</label>
                        <input type="text" class="input" value="">
                    </div>
                    <div class="field">
                        <label for="">Duty:</label>
                        <input type="text" class="input" value="">
                    </div>
                    <div class="field">
                        <label for="">Additional Notes:</label>
                        <textarea name="" id=""></textarea>
                    </div>
                    <button class="btn">Generate</button>
                </form>

            </div>

        </div>
    </div>
</main>

<?php include_once "../../../includes/footer.php" ?>