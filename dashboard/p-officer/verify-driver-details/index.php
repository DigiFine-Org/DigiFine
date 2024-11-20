<?php
$pageConfig = [
    'title' => 'Verify Driver Details',
    'styles' => ["../../dashboard.css"],
    'scripts' => ["../../dashboard.js"],
    'authRequired' => true
];

include_once "../../../includes/header.php";

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
            <div class="container">
<<<<<<< HEAD
                <h1>Issue Traffic Fine</h1>
                <form method="POST" action="store-fine.php">

                    <div class="field">
                        <label for="driver_id">Driver License ID</label>
                        <input type="text" id="id" name="id" class="input" placeholder="CAD-6264" required>
=======
                <h1>Verify Driver Details</h1>
                <?php if (!$result): ?>
                    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                        <input name="query" required type="search" class="input" placeholder="Enter Driver License ID">
                        <button class="btn margintop">Search</button>
                    </form>
                <?php else: ?>
                    <h3>Driver License</h3>
                    <div class="data-line">
                        <span>FULL NAME:</span>
                        <p>IMALSHA AKALANKA JATHUN ARACHCHI</p>
>>>>>>> db80d349711448c20f9c0b5a59666efc9d2987af
                    </div>
                    <div class="field">
                        <button class="btn" type="button" onclick="fetchDriverData()">Fetch Driver Details</button>
                        <script src="backend/fetchDriverData.js"></script>
                    </div>
                    <div class="field">
                        <label for="full_name">Full Name</label>
                        <input type="text" id="full_name" name="full_name" class="input" placeholder="CAD-6264" required>
                    </div>

                    <div class="field">
                        <label for="d_address">Address</label>
                        <input type="text" id="d_address" name="d_address" class="input" placeholder="CAD-6264" required>
                    </div>

                    <div class="field">
                        <label for="license_valid_from">License Valid From</label>
                        <input type="date" id="license_valid_from" name="license_valid_from" class="input" placeholder="CAD-6264" readonly>
                    </div>

                    <div class="field">
                        <label for="license_valid_to">License Valid To</label>
                        <input type="date" id="license_valid_to" name="license_valid_to" class="input" placeholder="CAD-6264" readonly>
                    </div>
                    <div class="field">
                        <label for="competent_categories">Competent Drive Categories</label>
                        <div class="field" id="competent_categories"></div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>

<?php include_once "../../../includes/footer.php" ?>