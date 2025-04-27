<?php
$pageConfig = [
    'title' => 'Officer Dashboard',
    'styles' => ["https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css", "../dashboard.css", "./officer-dashboard.css"],
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

if (!$policeId) {
    header("Location: /unauthorized.php");
    exit();
}


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


$sql_future_duties = "SELECT police_id, duty, duty_date, duty_time_start 
                      FROM assigned_duties 
                      WHERE duty_date >= CURDATE() 
                      AND submitted = 0 
                      AND police_id = ?
                      LIMIT 1";

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
        $formattedDate = date("M j, Y", strtotime($row["duty_date"]));
        $formattedTime = date("g:i A", strtotime($row["duty_time_start"]));

        $dutiesHTML .= "<div class='duty-card'>";
        $dutiesHTML .= "<div class='duty-icon'><i class='fas fa-calendar-check'></i></div>";
        $dutiesHTML .= "<div class='duty-info'>";
        $dutiesHTML .= "<h3>" . htmlspecialchars($row["duty"]) . "</h3>";
        $dutiesHTML .= "<p class='duty-meta'><i class='far fa-calendar'></i> " . $formattedDate . "</p>";
        $dutiesHTML .= "<p class='duty-meta'><i class='far fa-clock'></i> " . $formattedTime . "</p>";
        $dutiesHTML .= "</div></div>";
    }
} else {
    $dutiesHTML = "<div class='no-duties'><i class='fas fa-check-circle'></i><p>No Newly Assigned Duties</p></div>";
}

$stmt->close();


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
    $formattedDate = date("M j, Y", strtotime($row["duty_date"]));
    $formattedTime = date("g:i A", strtotime($row["duty_time_start"]));

    $lastDutyHTML .= "<div class='duty-card'>";
    $lastDutyHTML .= "<div class='duty-icon'><i class='fas fa-clipboard-check'></i></div>";
    $lastDutyHTML .= "<div class='duty-info'>";
    $lastDutyHTML .= "<h3>" . htmlspecialchars($row["duty"]) . "</h3>";
    $lastDutyHTML .= "<p class='duty-meta'><i class='far fa-calendar'></i> " . $formattedDate . "</p>";
    $lastDutyHTML .= "<p class='duty-meta'><i class='far fa-clock'></i> " . $formattedTime . "</p>";
    $lastDutyHTML .= "</div></div>";
} else {
    $lastDutyHTML = "<div class='no-duties'><i class='fas fa-info-circle'></i><p>No Previous Duties Found</p></div>";
}

$stmt->close();


$sql = "SELECT * FROM fines 
WHERE police_id = ? 
AND issued_date >= DATE_SUB(NOW(), INTERVAL 3 DAY) 
ORDER BY issued_date DESC, issued_time DESC;";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $policeId);
$stmt->execute();
$result = $stmt->get_result();

$fines = [];
while ($row = $result->fetch_assoc()) {
    $fines[] = $row;
}

$stmt->close();


$sql_total_fines = "SELECT COUNT(*) as total_fines FROM fines WHERE police_id = ?";
$stmt = $conn->prepare($sql_total_fines);
$stmt->bind_param("i", $policeId);
$stmt->execute();
$result_total = $stmt->get_result();
$totalFines = 0;

if ($row = $result_total->fetch_assoc()) {
    $totalFines = $row['total_fines'];
}

$stmt->close();


$sql_total_duty_submissions = "SELECT COUNT(*) as total_duty_submissions FROM duty_submissions WHERE police_id = ?";
$stmt = $conn->prepare($sql_total_duty_submissions);
$stmt->bind_param("i", $policeId);
$stmt->execute();
$result_duty_submissions = $stmt->get_result();
$totalDutySubmissions = 0;

if ($row = $result_duty_submissions->fetch_assoc()) {
    $totalDutySubmissions = $row['total_duty_submissions'];
}

$stmt->close();


$sql_reported_fines = "SELECT COUNT(*) as reported_fines FROM fines WHERE police_id = ? AND is_reported = 1";
$stmt = $conn->prepare($sql_reported_fines);
$stmt->bind_param("i", $policeId);
$stmt->execute();
$result_reported = $stmt->get_result();
$reportedFines = 0;

if ($row = $result_reported->fetch_assoc()) {
    $reportedFines = $row['reported_fines'];
}

$stmt->close();


$sql_pending_duties = "SELECT COUNT(*) as pending_duties FROM assigned_duties WHERE police_id = ? AND submitted = 0";
$stmt = $conn->prepare($sql_pending_duties);
$stmt->bind_param("i", $policeId);
$stmt->execute();
$result_pending_duties = $stmt->get_result();
$pendingDuties = 0;

if ($row = $result_pending_duties->fetch_assoc()) {
    $pendingDuties = $row['pending_duties'];
}

$conn->close();
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">

<main>
    <?php include_once "../includes/navbar.php" ?>
    <div class="dashboard-layout">
        <?php include_once "../includes/sidebar.php" ?>
        <div class="content">
            <button onclick="history.back()" class="back-btn" style="position: absolute; top: 7px; right: 8px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                    <path fill-rule="evenodd"
                        d="M15 8a.5.5 0 0 1-.5.5H3.707l3.147 3.146a.5.5 0 0 1-.708.708l-4-4a.5.5 0 0 1 0-.708l4-4a.5.5 0 1 1 .708.708L3.707 7.5H14.5a.5.5 0 0 1 .5.5z" />
                </svg>
            </button>

            <h1>Welcome, Officer <?= htmlspecialchars($officer['fname'] . ' ' . $officer['lname']) ?></h1>
            <p class="station-info"><i class="fas fa-building"></i>
                <?= htmlspecialchars($officer['police_station_name']) ?></p>
            <div class="stats-grid">
                <a href="/digifine/dashboard/officer/verify-driver-details/index.php" class="stat-card">
                    <div class="icon" style="background-color: #FFEFB4; font-size: 1.7rem;">
                        <i class="fas fa-id-card"></i>
                    </div>
                    <div class="info">
                        <p>Check Driver Details</p>
                        
                    </div>
                </a>

                <a href="/digifine/dashboard/officer/check-vehicle-details/index.php" class="stat-card">
                    <div class="icon" style="background-color: #CDE4FF;  font-size: 1.7rem;">
                        <i class="fas fa-car"></i>
                    </div>
                    <div class="info">
                        <p>Check Vehicle Details</p>
                        
                    </div>
                </a>

                <a href="/digifine/dashboard/officer/generate-e-ticket/index.php" class="stat-card">
                    <div class="icon" style="background-color: #F8C8D8; font-size: 1.7rem;">
                        <i class="fas fa-file-invoice-dollar"></i>
                    </div>
                    <div class="info">
                        <p>Issue Fines</p>
                        
                    </div>
                </a>

                <a href="/digifine/dashboard/officer/submit-duty/index.php" class="stat-card">
                    <div class="icon" style="background-color: #D5F2EA; font-size: 1.7rem;">
                        <i class="fas fa-tasks"></i>
                    </div>
                    <div class="info">
                        <p>Submit Duty</p>
                        
                    </div>
                </a>
            </div>

            <div class="main-content">
                <div class="duties-section">
                    <div class="duty-card-container">
                        <div class="section-header">
                            <h2><i class="fas fa-calendar-plus"></i> New Duty Assignments</h2>
                            <a href="./submit-duty/index.php" class="view-all">View All <i
                                    class="fas fa-chevron-right"></i></a>
                        </div>
                        <div class="duty-details">
                            <?= $dutiesHTML ?>
                        </div>
                    </div>

                    <div class="duty-card-container">
                        <div class="section-header">
                            <h2><i class="fas fa-history"></i> Last Completed Duty</h2>
                        </div>
                        <div class="duty-details">
                            <?= $lastDutyHTML ?>
                        </div>
                    </div>
                </div>

                <div class="recent-fines-section">
                    <div class="section-header">
                        <h2>Recent Fines Issued</h2>
                    </div>


                    <div class="table-container">
                        <table>
                            <thead>
                                <tr>
                                    <th>Driver ID</th>
                                    <th>License No.</th>
                                    <th>Issued Date</th>
                                    <th>Expire Date</th>
                                    <th>Status</th>
                                    <th>Reported</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($fines)): ?>
                                    <tr>
                                        <td colspan="7">
                                            No fines issued in the last 7 days
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($fines as $fine): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($fine['driver_id']) ?></td>
                                            <td><?= htmlspecialchars($fine['license_plate_number']) ?></td>
                                            <td><?= htmlspecialchars($fine['issued_date']) ?></td>
                                            <td><?= htmlspecialchars($fine['expire_date']) ?></td>
                                            <td>
                                                <span
                                                    class="status-badge <?= strtolower(str_replace(' ', '-', $fine['fine_status'])) ?>">
                                                    <?= htmlspecialchars($fine['fine_status']) ?>
                                                </span>
                                            </td>
                                            <td><?= $fine['is_reported'] == 1 ? 'Yes' : "No " ?></td>
                                            <td>Rs.<?= htmlspecialchars($fine['fine_amount']) ?></td>

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