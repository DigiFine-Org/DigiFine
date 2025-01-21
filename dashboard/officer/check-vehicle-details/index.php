<?php

$pageConfig = [
    'title' => 'Check Vehicle Details',
    'styles' => ["../../dashboard.css"],
    'scripts' => ["../../dashboard.js"],
    'authRequired' => true
];

session_start();
require_once "../../../db/connect.php";
include_once "../../../includes/header.php";

// Check user authentication and role
if ($_SESSION['user']['role'] !== 'officer') {
    die("Unauthorized user!");
}

$result = null;
$id = $_GET['query'] ?? null;

if ($id) {
    $sql = "SELECT * FROM dmt_vehicles WHERE license_plate_number = ?";
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
        $_SESSION['message'] = "Vehicle not found!";
        header("Location: /digifine/dashboard/officer/check-vehicle-details/index.php");
        exit();
    }

    $result = $result->fetch_assoc();
}

?>

<main>
    <?php include_once "../../includes/navbar.php"; ?>
    <div class="dashboard-layout">
        <?php include_once "../../includes/sidebar.php"; ?>
        <div class="content">
            <img class="watermark" src="../../../assets/watermark.png" />
            <div class="container">
                <h1>Check Vehicle Details</h1>
                <?php if ($_SESSION['message'] ?? null): ?>
                    <div class="alert alert-danger">
                        <?php 
                            echo htmlspecialchars($_SESSION['message']);
                            unset($_SESSION['message']); 
                        ?>
                    </div>
                <?php endif; ?>

                <?php if (!$result): ?>
                    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="GET">
                        <input name="query" required type="search" class="input"
                            placeholder="Enter Licence Plate Number (e.g., SP|BBY-1683)">
                        <button class="btn margintop">Search</button>
                    </form>
                <?php else: ?>
                    <?php if ($result['is_stolen'] == 1): ?>
                        <div class="alert alert-danger">
                            <h3>Alert: Stolen Vehicle</h3>
                            <p>This vehicle is marked as stolen in the system. Please proceed with caution.</p>
                        </div>

                        <div class="data-line">
                            <span>Licence Id:</span>
                            <p><?= htmlspecialchars($result['license_id']); ?></p>
                        </div>
                        <div class="data-line">
                            <span>Vehicle Type:</span>
                            <p><?= htmlspecialchars($result['vehicle_type']); ?></p>
                        </div>
                        <div class="data-line">
                            <span>License Type:</span>
                            <p><?= htmlspecialchars($result['license_type']); ?></p>
                        </div>
                        <div class="data-line">
                            <span>License Plate Number:</span>
                            <p><?= htmlspecialchars($result['license_plate_number']); ?></p>
                        </div>
                        <div class="data-line">
                            <span>Vehicle Owner’s Name:</span>
                            <p><?= htmlspecialchars($result['vehicle_owner_fname'] . " " . $result['vehicle_owner_lname']); ?></p>
                        </div>
                        <div class="data-line">
                            <span>Vehicle Owner’s Address:</span>
                            <p><?= htmlspecialchars($result['address']); ?></p>
                        </div>
                        <div class="data-line">
                            <span>Validity Period:</span>
                            <p>From <b><?= htmlspecialchars($result['license_issue_date']); ?></b> To <b><?= htmlspecialchars($result['license_expiry_date']); ?></b></p>
                        </div>
                        <div class="data-line">
                            <span>Number of Seats:</span>
                            <p><?= htmlspecialchars($result['no_of_seats']); ?></p>
                        </div>
                        
                        <form action="../report-stolen-vehicle/index.php" method="POST">
                            <input type="hidden" name="license_plate_number" value="<?= htmlspecialchars($result['license_plate_number']); ?>">
                            <button class="btn margintop" type="submit">Report to Authorities</button>
                        </form>

                    
                    <?php else: ?>
                        <h3>Vehicle Revenue Licence</h3>
                        <div class="data-line">
                            <span>Licence Id:</span>
                            <p><?= htmlspecialchars($result['license_id']); ?></p>
                        </div>
                        <div class="data-line">
                            <span>Vehicle Type:</span>
                            <p><?= htmlspecialchars($result['vehicle_type']); ?></p>
                        </div>
                        <div class="data-line">
                            <span>License Type:</span>
                            <p><?= htmlspecialchars($result['license_type']); ?></p>
                        </div>
                        <div class="data-line">
                            <span>License Plate Number:</span>
                            <p><?= htmlspecialchars($result['license_plate_number']); ?></p>
                        </div>
                        <div class="data-line">
                            <span>Vehicle Owner’s Name:</span>
                            <p><?= htmlspecialchars($result['vehicle_owner_fname'] . " " . $result['vehicle_owner_lname']); ?></p>
                        </div>
                        <div class="data-line">
                            <span>Vehicle Owner’s Address:</span>
                            <p><?= htmlspecialchars($result['address']); ?></p>
                        </div>
                        <div class="data-line">
                            <span>Validity Period:</span>
                            <p>From <b><?= htmlspecialchars($result['license_issue_date']); ?></b> To <b><?= htmlspecialchars($result['license_expiry_date']); ?></b></p>
                        </div>
                        <div class="data-line">
                            <span>Number of Seats:</span>
                            <p><?= htmlspecialchars($result['no_of_seats']); ?></p>
                        </div>
                        <a href="../generate-e-ticket/index.php?id=<?= htmlspecialchars($id); ?>" class="btn margintop">Issue Fine</a>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>

<?php include_once "../../../includes/footer.php"; ?>
