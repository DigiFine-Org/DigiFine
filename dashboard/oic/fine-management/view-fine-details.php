<?php
$pageConfig = [
    'title' => 'Fine Details',
    'styles' => ["../../dashboard.css"],
    'scripts' => ["../../dashboard.js"],
    'authRequired' => true
];

include_once "../../../includes/header.php";
require_once "../../../db/connect.php";


$oic_id = $_SESSION['user']['id'] ?? null;
if (!$oic_id) {
    die("Unauthorized access.");
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid or missing fine ID.");
}

$fine_id = intval($_GET['id']);

// Ensure the fine belongs to the OIC's police station
$sql = "
    SELECT f.id, f.police_id, f.driver_id, f.license_plate_number, f.issued_date, f.issued_time, 
           f.offence_type, f.nature_of_offence, f.offence, f.fine_status 
    FROM fines f
    INNER JOIN officers o ON f.police_id = o.id
    WHERE f.id = ? AND o.police_station = (SELECT police_station FROM officers WHERE id = ?)
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("is", $fine_id, $oic_id);
$stmt->execute();
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
                <a href="index.php" class="btn" style="margin-top: 20px;">Back to Fines</a>
            </div>
        </div>
    </div>
</main>

<?php include_once "../../../includes/footer.php"; ?>
