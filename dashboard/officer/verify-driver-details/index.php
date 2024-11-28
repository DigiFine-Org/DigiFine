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
$id = $_GET['query'] ?? null;
if ($id) {
    $sql = "SELECT * FROM dmt_drivers WHERE license_id=?";
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
        $_SESSION['message'] = "Driver not found!";
        header("Location: /digifine/dashboard/officer/verify-driver-details/index.php");
        exit();
    }

    $result = $result->fetch_assoc();
}

?>

<main>
    <?php include_once "../../includes/navbar.php" ?>
    <div class="dashboard-layout">
        <?php include_once "../../includes/sidebar.php" ?>
        <div class="content">
            <div class="container <?= $result ? "large" : "" ?>">
                <h1>Verify Driver Details</h1>
                <?php if ($_SESSION['message'] ?? null): ?>
                    <?php $message = $_SESSION['message'];
                    unset($_SESSION['message']);
                    include '../../../includes/alerts/failed.php';
                    ?>
                <?php endif; ?>
                <?php if (!$result): ?>
                    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                        <input name="query" required type="search" class="input"
                            placeholder="Enter Driver License ID (B1234567)">
                        <button class="btn margintop">Search</button>
                    </form>
                <?php else: ?>
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
                                    <td>A1</td>
                                    <td><?= $result['A1_issue_date'] ?></td>
                                    <td><?= $result['A1_expiry_date'] ?></td>
                                    <td><?= $result['restrictions'] ?></td>
                                </tr>
                                <tr>
                                    <td>A</td>
                                    <td><?= $result['A_issue_date'] ?></td>
                                    <td><?= $result['A_expiry_date'] ?></td>
                                    <td><?= $result['restrictions'] ?></td>
                                </tr>
                                <tr>
                                    <td>B1</td>
                                    <td><?= $result['B1_issue_date'] ?></td>
                                    <td><?= $result['B1_expiry_date'] ?></td>
                                    <td><?= $result['restrictions'] ?></td>
                                </tr>
                                <tr>
                                    <td>B</td>
                                    <td><?= $result['B_issue_date'] ?></td>
                                    <td><?= $result['B_expiry_date'] ?></td>
                                    <td><?= $result['restrictions'] ?></td>
                                </tr>
                                <tr>
                                    <td>C1</td>
                                    <td><?= $result['C1_issue_date'] ?></td>
                                    <td><?= $result['C1_expiry_date'] ?></td>
                                    <td><?= $result['restrictions'] ?></td>
                                </tr>
                                <tr>
                                    <td>C</td>
                                    <td><?= $result['C_issue_date'] ?></td>
                                    <td><?= $result['C_expiry_date'] ?></td>
                                    <td><?= $result['restrictions'] ?></td>
                                </tr>
                                <tr>
                                    <td>CE</td>
                                    <td><?= $result['CE_issue_date'] ?></td>
                                    <td><?= $result['CE_expiry_date'] ?></td>
                                    <td><?= $result['restrictions'] ?></td>
                                </tr>
                                <tr>
                                    <td>D1</td>
                                    <td><?= $result['D1_issue_date'] ?></td>
                                    <td><?= $result['D1_expiry_date'] ?></td>
                                    <td><?= $result['restrictions'] ?></td>
                                </tr>
                                <tr>
                                    <td>D</td>
                                    <td><?= $result['D_issue_date'] ?></td>
                                    <td><?= $result['D_expiry_date'] ?></td>
                                    <td><?= $result['restrictions'] ?></td>
                                </tr>
                                <tr>
                                    <td>DE</td>
                                    <td><?= $result['DE_issue_date'] ?></td>
                                    <td><?= $result['DE_expiry_date'] ?></td>
                                    <td><?= $result['restrictions'] ?></td>
                                </tr>
                                <tr>
                                    <td>G1</td>
                                    <td><?= $result['G1_issue_date'] ?></td>
                                    <td><?= $result['G1_expiry_date'] ?></td>
                                    <td><?= $result['restrictions'] ?></td>
                                </tr>
                                <tr>
                                    <td>G</td>
                                    <td><?= $result['G_issue_date'] ?></td>
                                    <td><?= $result['G_expiry_date'] ?></td>
                                    <td><?= $result['restrictions'] ?></td>
                                </tr>
                                <tr>
                                    <td>J</td>
                                    <td><?= $result['J_issue_date'] ?></td>
                                    <td><?= $result['J_expiry_date'] ?></td>
                                    <td><?= $result['restrictions'] ?></td>
                                </tr>
                            </tbody>
                        </table>
                        <br>
                        <a href="../generate-e-ticket/index.php?id=<?= $id ?>" class="btn margintop">Issue
                            Fine</a>
                    </div>
                <?php endif ?>
            </div>
        </div>
    </div>
</main>

<?php include_once "../../../includes/footer.php" ?>