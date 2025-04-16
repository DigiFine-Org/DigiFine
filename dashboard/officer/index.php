<?php
$pageConfig = [
    'title' => 'Officer Dashboard',
    'styles' => ["../dashboard.css", "./officer-dashboard.css"],
    'scripts' => ["../dashboard.js"],
    'authRequired' => true
];

include_once "../../includes/header.php";
require_once "../../db/connect.php";

if ($_SESSION['user']['role'] !== 'officer') {
    die("Unauthorized user!");
}

$policeId = $_SESSION['user']['id'] ?? null;

if (!$policeId) {
    die("Unauthorized access.");
}

// Fetch officers name
$sql = "SELECT o.fname, o.lname, ps.name AS police_station_name 
        FROM officers o 
        INNER JOIN police_stations ps ON o.police_station = ps.id 
        WHERE o.id = ? ";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Error preparing statement: " . $conn->error);
}

$stmt->bind_param("i", $policeId);

if (!$stmt->execute()) {
    die("Error fetching officer details: " . $stmt->error);
}

$result = $stmt->get_result();
if ($result->num_rows === 0) {
    die("Officer not found.");
}

$officer = $result->fetch_assoc();
$stmt->close();

// Fetch upcoming duties
$sql_future_duties = "SELECT police_id, duty, duty_date, duty_time_start 
                      FROM assigned_duties 
                      WHERE duty_date > CURDATE() 
                      AND submitted = 0 
                      AND police_id = ?";



$stmt = $conn->prepare($sql_future_duties);
if (!$stmt) {
    die("Error preparing duties query: " . $conn->error);
}

$stmt->bind_param("i", $policeId);
$stmt->execute();
$result_future = $stmt->get_result();



$dutiesHTML = "";

if ($result_future->num_rows > 0) {
    while ($row = $result_future->fetch_assoc()) {
        $dutiesHTML .= "<div class='duty_item' style='margin-bottom: 10px; border-bottom: 1px solid #ddd; padding-bottom: 10px;'>";
        $dutiesHTML .= "<p><strong>Duty: </strong>" . htmlspecialchars($row["duty"]) . "</p>";
        $dutiesHTML .= "<p><strong>Date: </strong>" . htmlspecialchars($row["duty_date"]) . "</p>";
        $dutiesHTML .= "<p><strong>Time: </strong>" . htmlspecialchars($row["duty_time_start"]) . "</p>";
        $dutiesHTML .= "</div>";
    }
} else {
    $dutiesHTML = "<p>No Newly Assigned Duties.</p>";
}

$stmt->close();

// Last duty query corrected
$sql_last_duty = "SELECT police_id, duty, duty_date, duty_time_start 
                  FROM assigned_duties 
                  WHERE submitted = 1 
                  AND police_id = ? 
                  ORDER BY duty_date DESC, duty_time_start DESC 
                  LIMIT 1";

$stmt = $conn->prepare($sql_last_duty);
if (!$stmt) {
    die("Error in preparing duty queries: " . $conn->error);
}

$stmt->bind_param("i", $policeId);
$stmt->execute();
$result_last = $stmt->get_result();

$lastDutyHTML = "";

if ($row = $result_last->fetch_assoc()) {
    $lastDutyHTML .= "<div class='duty_item' style='margin-bottom: 10px; border-bottom: 1px solid #ddd; padding-bottom: 10px;'>";
    $lastDutyHTML .= "<p><strong>Duty: </strong>" . htmlspecialchars($row["duty"]) . "</p>";
    $lastDutyHTML .= "<p><strong>Date: </strong>" . htmlspecialchars($row["duty_date"]) . "</p>";
    $lastDutyHTML .= "<p><strong>Time: </strong>" . htmlspecialchars($row["duty_time_start"]) . "</p>";
    $lastDutyHTML .= "</div>";
} else {
    $lastDutyHTML = "<p>No previous duties found.</p>";
}

$stmt->close();



$sql = "SELECT * FROM fines 
        WHERE police_id = ? 
        AND issued_date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY) 
        ORDER BY issued_date DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $policeId);
$stmt->execute();
$result = $stmt->get_result();

$fines = [];
while ($row = $result->fetch_assoc()) {
    $fines[] = $row;
}

$stmt->close();
$conn->close();
?>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<main>
    <?php include_once "../includes/navbar.php" ?>
    <div class="dashboard-layout">
        <?php include_once "../includes/sidebar.php" ?>
        <div class="content">
            <h2>Welcome Officer <?= htmlspecialchars($officer['fname'] . ' ' . $officer['lname']) ?>!</h2>
            <p>Police Station: <?= htmlspecialchars($officer['police_station_name']) ?></p>
            <div class="insights-bar">
                <div class="inner-tile">
                    <div class="icon" style="background-color: #FFEFB4;">
                        <!-- <img src="driver-icon.svg" alt="Driver Icon"> -->
                    </div>
                    <div class="info">
                        <p>Duty Submissions</p>
                        <h3>248</h3>
                    </div>
                </div>
                <div class="inner-tile">
                    <div class="icon" style="background-color: #CDE4FF;">
                        <!-- <img src="officer-icon.svg" alt="Officer Icon"> -->
                    </div>
                    <div class="info">
                        <p>Fines Issued</p>
                        <h3>56</h3>
                    </div>
                </div>
                <div class="inner-tile">
                    <div class="icon" style="background-color: #F8C8D8;">
                        <!-- <img src="report-icon.svg" alt="Report Icon"> -->
                    </div>
                    <div class="info">
                        <p>Reported Fines</p>
                        <h3>15</h3>
                    </div>
                </div>
                <div class="inner-tile">
                    <div class="icon" style="background-color: #D5F2EA;">
                        <!-- <img src="fines-icon.svg" alt="Fines Icon"> -->
                    </div>
                    <div class="info">
                        <p>Duty Assignments</p>
                        <h3>3</h3>
                    </div>
                </div>
            </div>

            <div class="main-content">
                <div class="duties">
                    <div class="tile1 tile-green">
                        <h2>New Duty</h2>
                        <div id="new-duty">
                            <!-- <img id="duty-image" src="/digifine/assets/image 1.png" style="width:350px; height: 100px; border-radius:10px;"> -->
                            <div class="duty-details">
                                <?= $dutiesHTML ?>
                            </div>
                        </div>
                    </div>
                    <?php
?>

                    <div class="tile1 tile-yellow">
                        <h2>Last Duty</h2>
                        <div id="last-duty">
                            <!-- <img id="duty-image" src="/digifine/assets/image 2.png" style="width:350px; height: 100px; border-radius:10px;"> -->
                            <div class="duty-details">
                                <?= $lastDutyHTML ?>
                            </div>
                        </div>
                    </div>


                </div>

                <div class="table-section" style="margin-top:20px;">
                    <div class="table-container">
                        <table>
                            <thead>
                                <tr>
                                    <th>Driver ID</th>
                                    <th>License Number</th>
                                    <th>Issued Date</th>
                                    <th>Expire Date</th>
                                    <th>Fine Status</th>
                                    <th>Reported</th>
                                    <th>Fine Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($fines)): ?>
                                    <tr>
                                        <td colspan="7">No fines for the last week qwdnsc</td>
                                    </tr>

                                <?php else: ?>
                                    <?php foreach ($fines as $fine): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($fine['driver_id']) ?></td>
                                            <td><?= htmlspecialchars($fine['license_plate_number']) ?></td>
                                            <td><?= htmlspecialchars($fine['issued_date']) ?></td>
                                            <td><?= htmlspecialchars($fine['expire_date']) ?></td>
                                            <td><?= htmlspecialchars($fine['fine_status']) ?></td>
                                            <td><?= $fine['is_reported'] == 1 ? 'Yes' : "No " ?></td>
                                            <td><?= htmlspecialchars($fine['fine_amount']) ?></td>

                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>


                            </tbody>
                        </table>

                    </div>
                </div>


            </div>
        </div>
    </div>
</main>

<?php include_once "../../includes/footer.php"; ?>