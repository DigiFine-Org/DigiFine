<?php
$pageConfig = [
    'title' => 'Check Vehicle Details',
    'styles' => ["../../dashboard.css"],
    'scripts' => ["../../dashboard.js"],
    'authRequired' => true
];

include_once "../../../includes/header.php";
require_once "../../../db/connect.php";

$result = null;
$id = $_GET['query'] ?? null;
if ($id) {
    $sql = "SELECT * FROM dmt_vehicles WHERE license_plate_number=?";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("Database error: " . $conn->error);
    }

    $stmt->bind_param("s", $id);

    if (!$stmt->execute()) {
        die("Query execution error: " . $stmt->error);
    }

    $result = $stmt->get_result();
    if ($result->num_rows === 0) {
        header("Location: verify-vehicle-details?error=Vehicle not found");
    }

    $result = $result->fetch_assoc();
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
                            placeholder="Enter Licence Plate Number (SP|BBY-1683)">
                        <button class="btn margintop">Search</button>
                    </form>
                <?php else: ?>
                    <!-- <div class="warning">
                        <h3>This vehicle is stolen!</h3>
                        <p>Stolen Date: 2024-09-08</p>
                    </div> -->
                    <hr>
                    <h3>Vehicle Revenue Licence</h3>
                    <div class="data-line">
                        <span>Licence Id:</span>
                        <p><?= $result['license_id'] ?></p>
                    </div>
                    <div class="data-line">
                        <span>Vehicle Type:</span>
                        <p><?= $result['vehicle_type'] ?></p>
                    </div>
                    <div class="data-line">
                        <span>License Type:</span>
                        <p><?= $result['license_type'] ?></p>
                    </div>
                    <div class="data-line">
                        <span>License Plate Number:</span>
                        <p><?= $result['license_plate_number'] ?></p>
                    </div>
                    <div class="data-line">
                        <span>Vehicle Owner’s Name:</span>
                        <p><?= $result['vehicle_owner_fname'] . " " . $result['vehicle_owner_lname'] ?></p>
                    </div>
                    <div class="data-line">
                        <span>Vehicle Owner’s Address:</span>
                        <p><?= $result['address'] ?></p>
                    </div>
                    <div class="data-line">
                        <span>Validity Period:</span>
                        <p>From <b><?= $result['license_issue_date'] ?></b> To <b><?= $result['license_expiry_date'] ?></b>
                        </p>
                    </div>
                    <div class="data-line">
                        <span>Number of Seats:</span>
                        <p><?= $result['no_of_seats'] ?></p>
                    </div>
                    <br>
                    <a href="../generate-e-ticket/index.php?id=<?= $id ?>" class="btn margintop">Issue Fine</a>

                <?php endif ?>
            </div>
        </div>
    </div>
</main>

<?php include_once "../../../includes/footer.php" ?>