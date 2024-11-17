<?php
$pageConfig = [
    'title' => 'Officer Check Vehicle Details',
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
                <h1>Check Vehicle Details</h1>
                <?php if (!$result): ?>
                    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                        <input name="query" required type="search" class="input"
                            placeholder="Enter Licence Plate Number(ex:- CAD-6264)">
                        <button class="btn margintop">Search</button>
                    </form>
                <?php else: ?>
                    <div class="warning">
                        <h3>This vehicle is stolen!</h3>
                        <p>Stolen Date: 2024-09-08</p>
                    </div>
                    <hr>
                    <h3>Vehicle Revenue Licence</h3>
                    <div class="data-line">
                        <span>Licence Id:</span>
                        <p>SP000000308603</p>
                    </div>
                    <div class="data-line">
                        <span>Vehicle Type:</span>
                        <p>Motor Car|Petrol</p>
                    </div>
                    <div class="data-line">
                        <span>License Type:</span>
                        <p>Normal License</p>
                    </div>
                    <div class="data-line">
                        <span>License Plate Number:</span>
                        <p>SP | CAD-6264</p>
                    </div>
                    <div class="data-line">
                        <span>Vehicle Owner’s Name:</span>
                        <p>MR. Imalsha Jathunarachchi</p>
                    </div>
                    <div class="data-line">
                        <span>Vehicle Owner’s Address:</span>
                        <p>Sakuna’s Boarding, Woodland Avenue, Nugegoda</p>
                    </div>
                    <div class="data-line">
                        <span>Validity Period:</span>
                        <p>From 28-02-2024 To 27-02-2025</p>
                    </div>
                    <div class="data-line">
                        <span>Number of Seats:</span>
                        <p>4</p>
                    </div>

                <?php endif ?>
            </div>
        </div>
    </div>
</main>

<?php include_once "../../../includes/footer.php" ?>