<?php

$pageConfig = [
    'title' => 'Fine Details',
    'styles' => ["../../dashboard.css"],
    'scripts' => ["../../dashboard.js"],
    'authRequired' => true
];

require_once "../../../db/connect.php";
include_once "../../../includes/header.php";

if ($_SESSION['user']['role'] !== 'driver') {
    die("unauthorized user!");
}

$driver_id = $_SESSION['user']['id'] ?? null;

// Fine ID Numeric Validation
$fine_id = isset($_GET['fine_id']) ? intval($_GET['fine_id']) : 0;
if ($fine_id <= 0) {
    die("Invalid fine ID.");
}


if (!$driver_id) {
    die("Unathorized access.");
}

$sql = "
    SELECT f.id AS fine_id, f.police_id, f.driver_id, f.license_plate_number, f.issued_date, 
    f.issued_time, f.offence_type, f.nature_of_offence, f.offence, f.fine_status FROM fines AS
     f INNER JOIN drivers AS d ON f.driver_id = d.id WHERE f.id = ? AND d.id = ?;
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("is", $fine_id, $driver_id);

if (!$stmt->execute()) {
    die("Error executing query: " . $stmt->error);
}

$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("No fine found or unauthorized access.");
}

$fine = $result->fetch_assoc();
$stmt->close();
$conn->close();

?>

<main>
    <?php include_once "../../includes/navbar.php" ?>
    <div class="dashboard-layout">
        <?php include_once "../../includes/sidebar.php" ?>
        <div class="content">
            <div class="container large">
                <h1>Fine Details</h1>
                <div class="data-line">
                    <span>Police ID:</span>
                    <p><?= htmlspecialchars($fine['police_id']) ?></p>
                </div>
                <div class="data-line">
                    <span>Driver ID:</span>
                    <p><?= htmlspecialchars($fine['driver_id']) ?></p>
                </div>
                <div class="data-line">
                    <span>License Plate Number:</span>
                    <p><?= htmlspecialchars($fine['license_plate_number']) ?></p>
                </div>
                <div class="data-line">
                    <span>Issued Date:</span>
                    <p><?= htmlspecialchars($fine['issued_date']) ?></p>
                </div>
                <div class="data-line">
                    <span>Issued Time:</span>
                    <p><?= htmlspecialchars($fine['issued_time']) ?></p>
                </div>
                <div class="data-line">
                    <span>Offence Type:</span>
                    <p><?= htmlspecialchars($fine['offence_type']) ?></p>
                </div>
                <div class="data-line">
                    <span>Offence:</span>
                    <p><?= htmlspecialchars($fine['offence']) ?></p>
                </div>
                <div class="data-line">
                    <span>Nature of Offence:</span>
                    <p><?= htmlspecialchars($fine['nature_of_offence']) ?></p>
                </div>
                <div class="data-line">
                    <span>Fine Status:</span>
                    <p><?= htmlspecialchars($fine['fine_status']) ?></p>
                </div>
                <div class="wrapper">
                    <a href="index.php" class="btn" style="margin-right: 10px;margin-top:20px">Back to Fines</a>
                    <a href="index.php" class="btn" style="margin-right: 10px;margin-top:20px">Report Fine</a>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include_once "../../../includes/footer.php" ?>