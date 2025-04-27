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
                <?php if (!$result): ?>
                    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                        <div style="margin-bottom: 15px;">
                            <label>Search By:</label>
                            <div style="display: flex; gap: 15px; margin-top: 5px;">
                                <label>
                                    <input type="radio" name="search_type" value="license" checked> Driver License ID
                                </label>
                                <label>
                                    <input type="radio" name="search_type" value="nic"> NIC
                                </label>
                            </div>
                        </div>
                        <input name="query" required type="search" class="input"
                            placeholder="Enter Driver License ID (B1234567) or NIC Number">
                        <button class="btn margintop">Search</button>
                    </form>
                <?php else: ?>
                    <div class="data-line" style="">
                        <span>Number OF past violations of this driver: </span>
                        <p><?= $fineCount ?></p>
                        <?php if ($fineCount > 0): ?>
                            <a href="past-violations.php?license_id=<?= $result['license_id'] ?>" class="btn" style="">View
                                Violations</a>
                        <?php endif; ?>
                    </div>
                    
                    <?php if ($isSuspended == 1): ?>
                        <div class="alert" style="background-color: #f8d7da; color: #721c24; padding: 10px; margin-bottom: 15px; border-radius: 5px; border: 1px solid #f5c6cb;">
                            <strong>Warning:</strong> This driver's license is currently suspended.
                        </div>
                    <?php endif; ?>
                    
                    <h3>Driver License</h3>
                    <div class="data-line">
                        <span>FULL NAME:</span>
                        <p><?= $result['fname'] . " " . $result['lname'] ?></p>
                    </div>
                    <div class="data-line">
                        <span>LICENSE ID:</span>
                        <p><?= $result['license_id'] ?></p>
                    </div>
                    <div class="data-line">
                        <span>NIC:</span>
                        <p><?= $result['nic'] ?></p>
                    </div>
                    <div class="data-line">
                        <span>PERMANENT PLACE OF RESIDENCE:</span>
                        <p><?= $result['address'] ?></p>
                    </div>
                    <div class="data-line">
                        <span>BIRTHDATE:</span>
                        <p><?= $result['birth_date'] ?></p>
                    </div>
                    <div class="data-line">
                        <span>DATE OF ISSUE LICENSE ID:</span>
                        <p><?= $result['license_issue_date'] ?></p>
                    </div>
                    <div class="data-line">
                        <span>DATE OF EXPIRY LICENSE ID:</span>
                        <p><?= $result['license_expiry_date'] ?></p>
                    </div>
                    <div class="data-line">
                        <span>BLOOD GROUP:</span>
                        <p><?= $result['blood_group'] ?></p>
                    </div>
                    <div class="data-line">
                        <span>RESTRICTIONS IN CODE FORM:</span>
                        <p><?= $result['restrictions'] ?></p>
                    </div>
                    <hr>
                    <div class="table-container">
                        <table>
                            <thead>
                                <tr>
                                    <th>Categories of Vehicle</th>
                                    <th>D. of Issue per category</th>
                                    <th>D. of expiry per category</th>
                                    <th>Restrictions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>A1<img src="/digifine/assets/license-icons/1.png" style="width:25px;"></td>
                                    <td><?= $result['A1_issue_date'] ?></td>
                                    <td><?= $result['A1_expiry_date'] ?></td>
                                    <td><?= $result['restrictions'] ?></td>
                                </tr>
                                <tr>
                                    <td>A<img src="/digifine/assets/license-icons/2.png" style="width:25px;"></td>
                                    <td><?= $result['A_issue_date'] ?></td>
                                    <td><?= $result['A_expiry_date'] ?></td>
                                    <td><?= $result['restrictions'] ?></td>
                                </tr>
                                <tr>
                                    <td>B1<img src="/digifine/assets/license-icons/3.png" style="width:25px;"></td>
                                    <td><?= $result['B1_issue_date'] ?></td>
                                    <td><?= $result['B1_expiry_date'] ?></td>
                                    <td><?= $result['restrictions'] ?></td>
                                </tr>
                                <tr>
                                    <td>B<img src="/digifine/assets/license-icons/4.png" style="width:25px;"></td>
                                    <td><?= $result['B_issue_date'] ?></td>
                                    <td><?= $result['B_expiry_date'] ?></td>
                                    <td><?= $result['restrictions'] ?></td>
                                </tr>
                                <tr>
                                    <td>C1<img src="/digifine/assets/license-icons/5.png" style="width:25px;"></td>
                                    <td><?= $result['C1_issue_date'] ?></td>
                                    <td><?= $result['C1_expiry_date'] ?></td>
                                    <td><?= $result['restrictions'] ?></td>
                                </tr>
                                <tr>
                                    <td>C<img src="/digifine/assets/license-icons/6.png" style="width:25px;"></td>
                                    <td><?= $result['C_issue_date'] ?></td>
                                    <td><?= $result['C_expiry_date'] ?></td>
                                    <td><?= $result['restrictions'] ?></td>
                                </tr>
                                <tr>
                                    <td>CE<img src="/digifine/assets/license-icons/7.png" style="width:35px;"
                                            style="width:25px;"></td>
                                    <td><?= $result['CE_issue_date'] ?></td>
                                    <td><?= $result['CE_expiry_date'] ?></td>
                                    <td><?= $result['restrictions'] ?></td>
                                </tr>
                                <tr>
                                    <td>D1<img src="/digifine/assets/license-icons/8.png" style="width:35px;"></td>
                                    <td><?= $result['D1_issue_date'] ?></td>
                                    <td><?= $result['D1_expiry_date'] ?></td>
                                    <td><?= $result['restrictions'] ?></td>
                                </tr>
                                <tr>
                                    <td>D<img src="/digifine/assets/license-icons/9.png" style="width:35px;"></td>
                                    <td><?= $result['D_issue_date'] ?></td>
                                    <td><?= $result['D_expiry_date'] ?></td>
                                    <td><?= $result['restrictions'] ?></td>
                                </tr>
                                <tr>
                                    <td>DE<img src="/digifine/assets/license-icons/10.png" style="width:35px;"></td>
                                    <td><?= $result['DE_issue_date'] ?></td>
                                    <td><?= $result['DE_expiry_date'] ?></td>
                                    <td><?= $result['restrictions'] ?></td>
                                </tr>
                                <tr>
                                    <td>G1<img src="/digifine/assets/license-icons/11.png" style="width:35px;"></td>
                                    <td><?= $result['G1_issue_date'] ?></td>
                                    <td><?= $result['G1_expiry_date'] ?></td>
                                    <td><?= $result['restrictions'] ?></td>
                                </tr>
                                <tr>
                                    <td>G<img src="/digifine/assets/license-icons/12.png" style="width:35px;"></td>
                                    <td><?= $result['G_issue_date'] ?></td>
                                    <td><?= $result['G_expiry_date'] ?></td>
                                    <td><?= $result['restrictions'] ?></td>
                                </tr>
                                <tr>
                                    <td>J<img src="/digifine/assets/license-icons/13.png" style="width:35px;"></td>
                                    <td><?= $result['J_issue_date'] ?></td>
                                    <td><?= $result['J_expiry_date'] ?></td>
                                    <td><?= $result['restrictions'] ?></td>
                                </tr>
                            </tbody>
                        </table>
                        <br>
                        <?php if ($isSuspended == 1): ?>
                            <button class="btn margintop" disabled style="background-color: #6c757d; cursor: not-allowed;">Issue Fine (License Suspended)</button>
                        <?php else: ?>
                            <a href="../generate-e-ticket/index.php?id=<?= $result['license_id'] ?>&nic=<?= $result['nic'] ?>"
                                class="btn margintop">Issue Fine</a>
                        <?php endif; ?>
                    </div>
                <?php endif ?>
            </div>
        </div>
    </div>
</main>

<?php include_once "../../../includes/footer.php" ?>