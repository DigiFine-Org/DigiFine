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

$vehicleDetails = null;
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

    $vehicleDetails = $result->fetch_assoc();
}

$imagePath = null;
if ($id) {
    $sql = "SELECT sv.vehicle_image 
            FROM stolen_vehicles sv 
            JOIN dmt_vehicles dv 
            ON sv.license_plate_number = dv.license_plate_number 
            WHERE sv.license_plate_number = ? 
            LIMIT 1";

    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("s", $id);
        $stmt->execute();
        $imageResult = $stmt->get_result();

        if ($imageResult->num_rows > 0) {
            $row = $imageResult->fetch_assoc();
            $imagePath = $row['vehicle_image'];
        }
    }
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

                <?php if (!$vehicleDetails): ?>
                    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="GET">
                        <input name="query" required type="search" class="input"
                               placeholder="Enter Licence Plate Number (e.g., SP|BBY-1683)">
                        <button class="btn margintop">Search</button>
                    </form>
                <?php else: ?>
                    <?php if ($vehicleDetails['is_stolen'] == 1): ?>
                        <div class="alert alert-danger">
                            <h3>Alert: Stolen Vehicle</h3>
                            <p>This vehicle is marked as stolen in the system. Please proceed with caution.</p>
                        </div>

                        <?php if ($imagePath): ?>
                            <img src="../../../uploads/<?php echo htmlspecialchars($imagePath); ?>"
                                 alt="Stolen Vehicle"
                                 style="max-width:400px ;height:200px; ;margin-bottom:20px;">
                        <?php endif; ?>

                        <div class="data-line">
                            <span>Licence Id:</span>
                            <p><?= htmlspecialchars($vehicleDetails['license_id']); ?></p>
                        </div>
                        <div class="data-line">
                            <span>Vehicle Type:</span>
                            <p><?= htmlspecialchars($vehicleDetails['vehicle_type']); ?></p>
                        </div>
                        <div class="data-line">
                            <span>License Type:</span>
                            <p><?= htmlspecialchars($vehicleDetails['license_type']); ?></p>
                        </div>
                        <div class="data-line">
                            <span>License Plate Number:</span>
                            <p><?= htmlspecialchars($vehicleDetails['license_plate_number']); ?></p>
                        </div>
                        <div class="data-line">
                            <span>Vehicle Owner’s Name:</span>
                            <p><?= htmlspecialchars($vehicleDetails['vehicle_owner_fname'] . " " . $vehicleDetails['vehicle_owner_lname']); ?></p>
                        </div>
                        <div class="data-line">
                            <span>Vehicle Owner’s NIC:</span>
                            <p><?= htmlspecialchars($vehicleDetails['nic']); ?></p>
                        </div>
                        <div class="data-line">
                            <span>Vehicle Owner’s Address:</span>
                            <p><?= htmlspecialchars($vehicleDetails['address']); ?></p>
                        </div>
                        <div class="data-line">
                            <span>Validity Period:</span>
                            <p>From <b><?= htmlspecialchars($vehicleDetails['license_issue_date']); ?></b> To <b><?= htmlspecialchars($vehicleDetails['license_expiry_date']); ?></b></p>
                        </div>
                        <div class="data-line">
                            <span>Number of Seats:</span>
                            <p><?= htmlspecialchars($vehicleDetails['no_of_seats']); ?></p>
                        </div>
                        <form action="./caught_stolen_vehicle.php" method="GET">
                            <input type="hidden" name="license_plate_number" value="<?= htmlspecialchars($vehicleDetails['license_plate_number']); ?>">
                            <button class="btn margintop" type="submit">Seize</button>
                        </form>

                    <?php else: ?>
                        <h3>Vehicle Revenue Licence</h3>
                        <div class="data-line">
                            <span>Licence Id:</span>
                            <p><?= htmlspecialchars($vehicleDetails['license_id']); ?></p>
                        </div>
                        <div class="data-line">
                            <span>Vehicle Type:</span>
                            <p><?= htmlspecialchars($vehicleDetails['vehicle_type']); ?></p>
                        </div>
                        <div class="data-line">
                            <span>License Type:</span>
                            <p><?= htmlspecialchars($vehicleDetails['license_type']); ?></p>
                        </div>
                        <div class="data-line">
                            <span>License Plate Number:</span>
                            <p><?= htmlspecialchars($vehicleDetails['license_plate_number']); ?></p>
                        </div>
                        <div class="data-line">
                            <span>Vehicle Owner’s NIC:</span>
                            <p><?= htmlspecialchars($vehicleDetails['nic']); ?></p>
                        </div>
                        <div class="data-line">
                            <span>Vehicle Owner’s Name:</span>
                            <p><?= htmlspecialchars($vehicleDetails['vehicle_owner_fname'] . " " . $vehicleDetails['vehicle_owner_lname']); ?></p>
                        </div>
                        <div class="data-line">
                            <span>Vehicle Owner’s Address:</span>
                            <p><?= htmlspecialchars($vehicleDetails['address']); ?></p>
                        </div>
                        <div class="data-line">
                            <span>Validity Period:</span>
                            <p>From <b><?= htmlspecialchars($vehicleDetails['license_issue_date']); ?></b> To <b><?= htmlspecialchars($vehicleDetails['license_expiry_date']); ?></b></p>
                        </div>
                        <div class="data-line">
                            <span>Number of Seats:</span>
                            <p><?= htmlspecialchars($vehicleDetails['no_of_seats']); ?></p>
                        </div>
                        <a href="../generate-e-ticket/index.php?license_plate_number=<?= htmlspecialchars($vehicleDetails['license_plate_number']); ?>" class="btn margintop">Issue Fine</a>
                        <a href="../generate-e-ticket/index.php?nic=<?= htmlspecialchars($vehicleDetails['nic']); ?>&license_plate_number=<?= htmlspecialchars($vehicleDetails['license_plate_number']); ?>" class="btn margintop">Issue Fine to Vehicle Owner</a>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>

<?php include_once "../../../includes/footer.php"; ?>
