<?php
$pageConfig = [
    'title' => 'Verify Driver Details',
    'styles' => ["../../dashboard.css"],
    'scripts' => ["../../dashboard.js"],
    'authRequired' => true
];

require_once "../../../db/connect.php";
include_once "../../../includes/header.php";

if ($_SESSION['user']['role'] !== 'officer') {
    die("unauthorized user!");
}

$result = null;
$searchId = $_GET['query'] ?? null;
$searchType = $_GET['search_type'] ?? 'license';
$isSuspended = 0;

if ($searchId) {
    if ($searchType === 'license') {
        $searchField = "license_id";
    } else {
        $searchField = "nic";
    }

    $sql = "SELECT * FROM dmt_drivers WHERE $searchField=?";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("Database error: " . $conn->error);
    }

    $stmt->bind_param("s", $searchId);

    if (!$stmt->execute()) {
        die("Query execution error: " . $stmt->error);
    }

    $result = $stmt->get_result();
    if ($result->num_rows === 0) {
        $_SESSION['message'] = "Driver not found!";
        header("Location: /digifine/dashboard/officer/verify-driver-details/index.php");
        exit();
    }

    $result = $result->fetch_assoc();

    $fineCount = 0;

    $fineSql = "SELECT COUNT(*) as total FROM fines WHERE driver_id = ?";
    $fineStmt = $conn->prepare($fineSql);
    $fineStmt->bind_param("s", $result['license_id']);
    $fineStmt->execute();
    $fineResult = $fineStmt->get_result();

    if ($fineRow = $fineResult->fetch_assoc()) {
        $fineCount = $fineRow['total'];
    }

    $licenseSql = "SELECT license_suspended FROM drivers WHERE nic = ?";
    $licenseStmt = $conn->prepare($licenseSql);

    if (!$licenseStmt) {
        die("Database error: " . $conn->error);
    }

    $licenseStmt->bind_param("s", $result['nic']);

    if (!$licenseStmt->execute()) {
        die("Query execution error: " . $licenseStmt->error);
    }

    $licenseResult = $licenseStmt->get_result();

    if ($licenseResult->num_rows > 0) {
        $licenseData = $licenseResult->fetch_assoc();
        $isSuspended = (int)$licenseData['license_suspended'];
    }
}

?>

<main>
    <?php include_once "../../includes/navbar.php" ?>
    <div class="dashboard-layout">
        <?php include_once "../../includes/sidebar.php" ?>
        <div class="content">
            <img class="watermark" src="../../../assets/watermark.png" />
            <div class="container">
                <button onclick="history.back()" class="back-btn" style="position: absolute; top: 7px; right: 8px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        viewBox="0 0 16 16">
                        <path fill-rule="evenodd"
                            d="M15 8a.5.5 0 0 1-.5.5H3.707l3.147 3.146a.5.5 0 0 1-.708.708l-4-4a.5.5 0 0 1 0-.708l4-4a.5.5 0 1 1 .708.708L3.707 7.5H14.5a.5.5 0 0 1 .5.5z" />
                    </svg>
                </button>
                <h1>Verify Driver Details</h1>
                <?php if ($_SESSION['message'] ?? null): ?>
                    <?php $message = $_SESSION['message'];
                    unset($_SESSION['message']);
                    include '../../../includes/alerts/failed.php';
                    ?>
                <?php endif; ?>
                <?php
                if (!$result) {
                    include "search-form.php";
                } else {
                    include "driver-details.php";
                }
                ?>
            </div>
        </div>
    </div>
</main>

<?php include_once "../../../includes/footer.php" ?>