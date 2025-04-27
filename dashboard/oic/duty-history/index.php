<?php
$pageConfig = [
    'title' => 'Check duty history',
    'styles' => ["../../dashboard.css"],
    'scripts' => ["../../dashboard.js"],
    'authRequired' => true
];

session_start();
require_once "../../../db/connect.php";
include_once "../../../includes/header.php";


if ($_SESSION['user']['role'] !== 'oic') {
    die("Unauthorized user!");
}


$oicId = $_SESSION['user']['id'];
$stationQuery = "SELECT police_station FROM officers WHERE id = ? AND is_oic = 1";
$stationStmt = $conn->prepare($stationQuery);
$stationStmt->bind_param("i", $oicId);
$stationStmt->execute();
$stationResult = $stationStmt->get_result();
$stationData = $stationResult->fetch_assoc();
$stationId = $stationData['police_station'];

$officersQuery = "SELECT id, fname, lname FROM officers WHERE police_station = ? AND is_oic = 0 ORDER BY lname, fname";
$officersStmt = $conn->prepare($officersQuery);
$officersStmt->bind_param("i", $stationId);
$officersStmt->execute();
$officersResult = $officersStmt->get_result();
?>

<main>
    <?php include_once "../../includes/navbar.php"; ?>
    <div class="dashboard-layout">
        <?php include_once "../../includes/sidebar.php"; ?>
        <div class="content">
            <div class="container">
                <button onclick="history.back()" class="back-btn" style="position: absolute; top: 7px; right: 8px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M15 8a.5.5 0 0 1-.5.5H3.707l3.147 3.146a.5.5 0 0 1-.708.708l-4-4a.5.5 0 0 1 0-.708l4-4a.5.5 0 1 1 .708.708L3.707 7.5H14.5a.5.5 0 0 1 .5.5z" />
                    </svg>
                </button>
                <h1>Duty History</h1>


                <form action="" method="POST" style="height:140px; position:relative;">
                    <div class="field">
                        <select name="policeId" class="input" required>
                            <option value="">Select Officer</option>
                            <?php 

                            $officersResult->data_seek(0);
                            while ($officer = $officersResult->fetch_assoc()): ?>
                                <option value="<?php echo $officer['id']; ?>"
                                    <?php if (isset($_POST['policeId']) && $_POST['policeId'] == $officer['id']) echo 'selected'; ?>>
                                    <?php echo htmlspecialchars($officer['fname'] . ' ' . $officer['lname'] . ' - ' . $officer['id']); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                        <br>
                        <button class="btn" style="margin-top:10px;" type="submit" name="search">Search</button>
                    </div>
                </form>

                <?php
                if (isset($_POST['search'])) {
                    $police_id = filter_input(INPUT_POST, 'policeId', FILTER_SANITIZE_NUMBER_INT);
                    
                    if (!$police_id) {
                        echo "<p style='color: red;'>Please select a valid officer.</p>";
                    } else {

                        $verifyQuery = "SELECT id FROM officers WHERE id = ? AND police_station = ?";
                        $verifyStmt = $conn->prepare($verifyQuery);
                        $verifyStmt->bind_param("ii", $police_id, $stationId);
                        $verifyStmt->execute();
                        $verifyResult = $verifyStmt->get_result();

                        if ($verifyResult->num_rows === 0) {
                            echo "<p style='color: red;'>Error: Officer not found in your station.</p>";
                        } else {
                            $query = "SELECT ad.police_id, ad.duty, ad.notes, ad.duty_date 
                                      FROM assigned_duties ad
                                      JOIN officers o ON ad.police_id = o.id
                                      WHERE ad.police_id = ? 
                                      AND ad.submitted = 1
                                      AND o.police_station = ?
                                      ORDER BY ad.duty_date DESC";
                            $stmt = $conn->prepare($query);

                            if ($stmt) {
                                $stmt->bind_param("ii", $police_id, $stationId);
                                $stmt->execute();
                                $result = $stmt->get_result();

                                if ($result->num_rows > 0) {
                                    echo "<table class='duty-table'>
                                            <thead>
                                                <tr>
                                                    <th>Police ID</th>
                                                    <th>Duty</th>
                                                    <th>Notes</th>
                                                    <th>Duty Date</th>
                                                </tr>
                                            </thead>
                                            <tbody>";
                                    while ($row = $result->fetch_assoc()) {
                                        echo "<tr>
                                                <td>" . htmlspecialchars($row['police_id']) . "</td>
                                                <td>" . htmlspecialchars($row['duty']) . "</td>
                                                <td>" . htmlspecialchars($row['notes']) . "</td>
                                                <td>" . htmlspecialchars($row['duty_date']) . "</td>
                                              </tr>";
                                    }
                                    echo "</tbody></table>";
                                } else {
                                    echo "<p style='color: red;'>No duty history found for selected officer.</p>";
                                }
                                $stmt->close();
                            } else {
                                echo "<p style='color: red;'>Database error. Please try again.</p>";
                            }
                        }
                    }
                }
                $conn->close();
                ?>
            </div>
        </div>
    </div>
</main>
            
<?php include_once "../../../includes/footer.php"; ?>