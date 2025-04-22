<?php
$pageConfig = [
    'title' => 'OIC Dashboard',
    'styles' => ["../dashboard.css", "oic-dashboard.css", "popup.css"],
    'scripts' => ["../dashboard.js"],
    'authRequired' => true
];

require_once "../../db/connect.php";
include_once "../../includes/header.php";



if ($_SESSION['user']['role'] !== 'oic') {
    die("unauthorized user!");
}

$oicId = $_SESSION['user']['id'] ?? null;

if (!$oicId) {
    die("Unauthorized access.");
}

// fetch officer's nameS
$sql = "SELECT o.fname, o.lname,police_station,  ps.name AS police_station_name 
    FROM officers o 
    INNER JOIN police_stations ps ON o.police_station = ps.id 
    WHERE o.id = ? ";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $oicId);

if (!$stmt->execute()) {
    die("Error fetching officer details " . $stmt->error);
}

$result = $stmt->get_result();
if ($result->num_rows === 0) {
    die("Officer not found.");
}

$oic = $result->fetch_assoc();

// Fetch duty locations
$location_sql = "
    SELECT id, police_station_id, location_name, created_at, updated_at FROM duty_locations";

$location_stmt = $conn->prepare($location_sql);
if (!$location_stmt) {
    die("Error preparing location statement: " . $conn->error);
}
$location_stmt->execute();
$location_result = $location_stmt->get_result();
$locations = $location_result->fetch_all(MYSQLI_ASSOC);

$location_stmt->close();

$stmt->close();


// Fetch duty locations
$location_sql = "
    SELECT id, police_station_id, location_name, created_at, updated_at FROM duty_locations WHERE police_station_id = ?";

$location_stmt = $conn->prepare($location_sql);

if (!$location_stmt) {
    die("Error preparing location statement: " . $conn->error);
}

$police_station_id = $oic['police_station'];
$location_stmt->bind_param("i", $police_station_id);

if (!$location_stmt->execute()) {
    die("Error executing location query: " . $location_stmt->error);
}

$location_result = $location_stmt->get_result();
$locations = $location_result->fetch_all(MYSQLI_ASSOC);

$location_stmt->close();


$query = "SELECT COUNT(*) AS report_fine_count
          FROM fines f
          INNER JOIN officers o ON f.police_id = o.id
          WHERE o.police_station = ? AND f.is_reported = 1 ";
$stmt_report = $conn->prepare($query);
$stmt_report->bind_param("i", $police_station_id);
$stmt_report->execute();
$result = $stmt_report->get_result();
$row = $result->fetch_assoc();
$reportFineCount = $row ? $row['report_fine_count'] : 0;
$stmt_report->close();

// Fetch officer count (excluding OIC)
$sql = "SELECT COUNT(*) AS officer_count FROM officers WHERE police_station = ? AND is_oic = 0";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $police_station_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$officerCount = $row ? $row['officer_count'] : 0; // Assign count to a variable
$stmt->close();


$query = "SELECT COUNT(*) AS court_fine_count
          FROM fines f
          INNER JOIN officers o ON f.police_id = o.id
          WHERE o.police_station = ?  AND f.offence_type = 'court'";
$stmt_court = $conn->prepare($query);
$stmt_court->bind_param("i", $police_station_id);
$stmt_court->execute();
$result = $stmt_court->get_result();
$row = $result->fetch_assoc();
$courtFineCount = $row ? $row['court_fine_count'] : 0;
$stmt_court->close();


$query = "
    SELECT COUNT(DISTINCT s.id) AS seized_vehicle_count
    FROM seized_vehicle s
    INNER JOIN police_stations ps ON ps.name = s.police_station
    INNER JOIN officers o ON o.police_station = ps.id
    WHERE o.police_station = ? AND s.is_released = 0";

$stmt = $conn->prepare($query);

if (!$stmt) {
    die("Query preparation failed: " . $conn->error);
}

$stmt->bind_param("i", $police_station_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$seizedVehicleCount = $row ? $row['seized_vehicle_count'] : 0;
$stmt->close();



//GET POLICE OFFICERS TODAY DUTIES

$today = date('Y-m-d');

$sql = "SELECT 
            po.id AS officer_id,
            CONCAT(po.fname, ' ', po.lname) AS officer_name,
            ad.duty,
            ad.notes AS location, 
            ad.duty_time_start,
            ad.duty_time_end
        FROM 
            assigned_duties ad
        JOIN  
            officers po ON ad.police_id = po.id
        WHERE 
            ad.assigned_by = ? AND ad.duty_date = ?";

$stmt_duties = $conn->prepare($sql);
if (!$stmt_duties) {
    die("Failed to prepare duties query: " . $conn->error);
}

$stmt_duties->bind_param("is", $oicId, $today);
$stmt_duties->execute();
$result_duties = $stmt_duties->get_result();
$dutiesToday = $result_duties->fetch_all(MYSQLI_ASSOC);
$stmt_duties->close();


$conn->close();
?>

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
            <div>
                <h2>Welcome OIC <?= htmlspecialchars($oic['fname'] . ' ' . $oic['lname']) ?>!</h2>
                <p>Police Station: <?= htmlspecialchars($oic['police_station_name']) ?></p>
                <p>Police Station ID: <?= htmlspecialchars($oic['police_station']) ?></p>
            </div>
            <div class="insights-bar" style="margin-bottom:20px">
                <div class="inner-tile">
                    <div class="icon" style="background-color: #FFEFB4;">
                        <!-- <img src="driver-icon.svg" alt="Driver Icon"> -->
                    </div>
                    <div class="info">
                        <?php renderLink("Station Officers", "/digifine/dashboard/oic/officer-management/index.php"); ?>
                        <h3><?php echo htmlspecialchars($officerCount, ENT_QUOTES, 'UTF-8'); ?></h3>

                    </div>
                </div>

                <div class="inner-tile">
                    <div class="icon" style="background-color: #CDE4FF;">
                        <!-- <img src="officer-icon.svg" alt="Officer Icon"> -->
                    </div>
                    <div class="info">
                        <?php renderLink("Court-violations", "/digifine/dashboard/oic/court-violations/index.php") ?>
                        <h3><?php echo htmlspecialchars($courtFineCount, ENT_QUOTES, 'UTF-8'); ?></h3>


                    </div>
                </div>
                <div class="inner-tile">
                    <div class="icon" style="background-color: #F8C8D8;">
                        <!-- <img src="report-icon.svg" alt="Report Icon"> -->
                    </div>
                    <div class="info">
                        <?php renderLink("Reported Fines", "/digifine/dashboard/oic/reported-fines/index.php") ?>
                        <h3><?php echo htmlspecialchars($reportFineCount, ENT_QUOTES, 'UTF-8'); ?></h3>



                    </div>
                </div>
                <div class="inner-tile">
                    <div class="icon" style="background-color: #D5F2EA;">
                        <!-- <img src="fines-icon.svg" alt="Fines Icon"> -->
                    </div>
                    <div class="info">
                        <?php renderLink("Seized Vehicles", "/digifine/dashboard/oic/seized_vehicle/index.php") ?>
                        <h3><?php echo htmlspecialchars($seizedVehicleCount, ENT_QUOTES, 'UTF-8'); ?></h3>


                    </div>
                </div>

            </div>
            <div>
                <h3>Today Duties of Police Officers</h3>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>officer id</th>
                                <th>officer name</th>
                                <th>duty</th>
                                <th>location</th>
                                <th>Start Time</th>
                                <th>End Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($dutiesToday)): ?>
                                <?php foreach ($dutiesToday as $duty): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($duty['officer_id']) ?></td>
                                        <td><?= htmlspecialchars($duty['officer_name']) ?></td>
                                        <td><?= htmlspecialchars($duty['duty']) ?></td>
                                        <td><?= htmlspecialchars($duty['location']) ?></td>
                                        <td><?= htmlspecialchars($duty['duty_time_start']) ?></td>
                                        <td><?= htmlspecialchars($duty['duty_time_end']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" style="text-align:center;">No duties assigned today.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div style="margin-top:10px;">
                <h3>Duty Locations</h3>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Location</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($locations as $location): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($location['location_name']) ?></td>
                                    <td>
                                        <div class="wrapper">
                                            <form action="delete-location-process.php" method="POST"
                                                style="display:inline;">
                                                <input type="hidden" name="location_id"
                                                    value="<?php echo htmlspecialchars($location['id']); ?>">
                                                <button type="submit" class="deletebtn">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <button class="btn" id="show-form">Add Duty Location</button>
            </div>
            <!-- Popup for Adding Duty Location -->
            <div class="popup-new-overlay" id="popupNewOverlay" onclick="closeAddLocationPopup()"></div>
            <div class="popup-new" id="addLocationPopupNew">
                <h3>Add Duty Location</h3>
                <form action="add-location-process.php" method="post">
                    <div class="field">
                        <label for="location_name">Location</label>
                        <input type="text" class="input" placeholder="Enter location name" name="location_name"
                            required>
                        <input type="hidden" name="police_station_id"
                            value="<?= htmlspecialchars($oic['police_station']) ?>">

                    </div>
                    <button class="btn" type="submit">Add</button>
                </form>
                <button class="close-btn" onclick="closeAddLocationPopup()">Cancel</button>
            </div>

</main>

<script>
    document.querySelector("#show-form").addEventListener("click", function () {
        document.querySelector(".popup-new").classList.add("active");
    });

    document.querySelector(".popup-new .close-btn").addEventListener("click", function () {
        document.querySelector(".popup-new").classList.remove("active");
    });
</script>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        function showAddLocationPopup() {
            document.getElementById('popupNewOverlay').style.display = 'block';
            document.getElementById('addLocationPopupNew').style.display = 'block';
        }

        function closeAddLocationPopup() {
            document.getElementById('popupNewOverlay').style.display = 'none';
            document.getElementById('addLocationPopupNew').style.display = 'none';
        }

        window.showAddLocationPopup = showAddLocationPopup;
        window.closeAddLocationPopup = closeAddLocationPopup;
    });
</script>



<?php include_once "../../includes/footer.php"; ?>