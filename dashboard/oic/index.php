<?php
$pageConfig = [
    'title' => 'OIC Dashboard',
    'styles' => ["../dashboard.css", "oic-dashboard.css"],
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
$conn->close();

?>

<main>
    <?php include_once "../includes/navbar.php" ?>

    <div class="dashboard-layout">
        <?php include_once "../includes/sidebar.php" ?>
        <div class="content">
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
                        <p>Station Police Officers</p>
                        <h3>248</h3>
                    </div>
                </div>
                <div class="inner-tile">
                    <div class="icon" style="background-color: #CDE4FF;">
                        <!-- <img src="officer-icon.svg" alt="Officer Icon"> -->
                    </div>
                    <div class="info">
                        <p>Pending Fines</p>
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
                        <p>Overdue Fines</p>
                        <h3>20</h3>
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
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>15364</td>
                                <td>Pubuditha Walgampaya</td>
                                <td>Cross Line Handling</td>
                                <td>Kada Panaha Junction</td>
                            </tr>
                            <tr>
                                <td>12675</td>
                                <td>Wendt Edmund</td>
                                <td>Traffic Control</td>
                                <td>Abhayagiri Stupa Area</td>
                            </tr>
                            <tr>
                                <td>14567</td>
                                <td>Thihansa Sanjunie</td>
                                <td>Event Security</td>
                                <td>Sea Line</td>
                            </tr>
                            <tr>
                                <td>18934</td>
                                <td>John Manuel</td>
                                <td>VIP Escort</td>
                                <td>Election Meeting</td>
                            </tr>
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
                                            <!-- <a href="" class="btn marginbottom">Edit</a> -->
                                            <form action="delete-location-process.php" method="POST" style="display:inline;">
                                                <input type="hidden" name="offence_number"
                                                    value="<?php echo htmlspecialchars($offence['offence_number']); ?>">
                                                <button type="submit" class="deletebtn">Delete</button>
                                            </form>

                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <form action="/digifine/dashboard/oic/add-location.php" method="get">
                    <input type="submit" class="btn margintop marginbottom" value="Add Duty Location">
                </form>
            </div>
        </div>
    </div>
</main>

<?php include_once "../../includes/footer.php"; ?>