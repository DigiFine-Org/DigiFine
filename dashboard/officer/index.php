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

if (!$policeId) {
    header("Location: /unauthorized.php");
    exit();
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

// Last duty query
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

// Recent fines
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

// Total fines
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

// Duty submissions
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

// Reported fines
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

// Pending duties
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
            <h1>Welcome, Officer <?= htmlspecialchars($officer['fname'] . ' ' . $officer['lname']) ?></h1>
            <p class="station-info"><i class="fas fa-building"></i> <?= htmlspecialchars($officer['police_station_name']) ?></p>                                                    
            <div class="insights-bar">
                <div class="inner-tile">
                    <div class="icon" style="background-color: #FFEFB4;">
                    </div>
                    <div class="info">
                        <p >Duty Submissions</p>
                        <h3 ><?= $totalDutySubmissions ?></h3>
                    </div>
                </div>
                
                <div class="inner-tile">
                    <div class="icon" style="background-color: #CDE4FF;">
                    </div>
                    <div class="info">
                        <p >Fines Issued</p>
                        <h3 ><?= $totalFines ?></h3>
                    </div>
                </div>
                
                <div class="inner-tile">
                    <div class="icon" style="background-color: #F8C8D8;">
                    </div>
                    <div class="info">
                        <p >Reported Fines</p>
                        <h3 ><?= $reportedFines ?></h3>
                    </div>
                </div>
                
                <div class="inner-tile">
                    <div class="icon" style="background-color: #D5F2EA;">
                    </div>
                    <div class="info">
                        <p >Pending Duties</p>
                        <h3 ><?= $pendingDuties ?></h3>
                    </div>
                </div>
            </div>

            <div class="main-content">
                <div class="duties-section">
                    <div class="duty-card-container">
                        <div class="section-header">
                            <h2><i class="fas fa-calendar-plus"></i> New Duty Assignments</h2>
                            <a href="./submit-duty/index.php" class="view-all">View All <i class="fas fa-chevron-right"></i></a>
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
                                                <i class="fas fa-info-circle"></i> No fines issued in the last 7 days
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
                                                    <span class="status-badge <?= strtolower(str_replace(' ', '-', $fine['fine_status'])) ?>">
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

<style>
/* Add these styles to your officer-dashboard.css */
:root {
    --primary-color: #3a7bd5;
    --secondary-color: #00d2ff;
    --success-color: #4CAF50;
    --warning-color: #FFC107;
    --danger-color: #F44336;
    --light-gray: #f5f7fa;
    --medium-gray: #e1e5eb;
    --dark-gray: #6c757d;
    --text-color: #333;
    --text-light: #777;
}



.station-info i {
    margin-right: 0.5rem;
}

.duty-card-container {
    background-color: white;
    border-radius: 10px;
    padding: 1.5rem;
    box-shadow: 0 4px 6px rgba(0,0,0,0.05);
    margin-top:20px;
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
}

.section-header h2 {
    font-size: 1.2rem;
    font-weight: 500;
    color: var(--text-color);
    display: flex;
    align-items: center;
}

.section-header h2 i {
    margin-right: 0.5rem;
    color: var(--primary-color);
}

.view-all {
    font-size: 0.85rem;
    color: var(--primary-color);
    text-decoration: none;
    display: flex;
    align-items: center;
    padding-left:0.5rem;
}

.view-all i {
    margin-left: 0.3rem;
    font-size: 0.7rem;
}


.duty-card {
    display: flex;
    align-items: center;
    padding: 1rem;
    margin-bottom: 1rem;
    background-color: var(--light-gray);
    border-radius: 8px;
    transition: all 0.3s ease;
}


.duty-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background-color: var(--primary-color);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 1rem;
    font-size: 1rem;
}

.duty-info h3 {
    font-size: 1rem;
    margin-bottom: 0.3rem;
    color: var(--text-color);
}

.duty-meta {
    font-size: 0.8rem;
    color: var(--text-light);
    display: flex;
    align-items: center;
    margin-bottom: 0.2rem;
}

.duty-meta i {
    margin-right: 0.5rem;
    width: 15px;
    text-align: center;
}

.no-duties {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 150px;
    color: var(--dark-gray);
}

.no-duties i {
    font-size: 2rem;
    margin-bottom: 1rem;
    color: var(--medium-gray);
}

.no-duties p {
    font-size: 0.9rem;
}

.recent-fines-section {
    background-color: white;
    border-radius: 10px;
    padding: 1.5rem;
    box-shadow: 0 4px 6px rgba(0,0,0,0.05);
    margin-top:20px;
    margin-left:3rem;
    width: 900px;
}



.status-badge {
    display: inline-block;
    padding: 0.3rem 0.6rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 500;
}

.status-badge.pending {
    background-color: #fff3cd;
    color: #856404;
}

.status-badge.paid {
    background-color: #d4edda;
    color: #155724;
}

.status-badge.overdue {
    background-color: #f8d7da;
    color: #721c24;
}

@media (max-width: 768px) {
    .duty-card-container,
    .recent-fines-section {
        padding: 1rem;
        margin: 1rem 0.5rem;
        width: auto;
    }

}

</style>

<?php include_once "../../includes/footer.php"; ?>