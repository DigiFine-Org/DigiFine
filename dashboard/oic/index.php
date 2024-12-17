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

$driverId = $_SESSION['user']['id'] ?? null;

if (!$driverId) {
    die("Unauthorized access.");
}

// fetch officer's nameS
$sql = "SELECT o.fname, o.lname, ps.name AS police_station_name 
    FROM officers o 
    INNER JOIN police_stations ps ON o.police_station = ps.id 
    WHERE o.id = ? ";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $driverId);

if (!$stmt->execute()) {
    die("Error fetching officer details " . $stmt->error);
}

$result = $stmt->get_result();
if ($result->num_rows === 0) {
    die("Officer not found.");
}

$oic = $result->fetch_assoc();
$stmt->close();
$conn->close();

?>

<main>
    <?php include_once "../includes/navbar.php" ?>

    <div class="dashboard-layout">
        <?php include_once "../includes/sidebar.php" ?>
        <div class="content">
            <h2>Welcome OIC <?= htmlspecialchars($oic['fname'] . ' ' . $oic['lname']) ?>!</h2>
            <p>Police Station: <?= htmlspecialchars($oic['police_station_name']) ?></p>
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
            <h3>Duties of Police Officers</h3>
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
                        <tr>
                            <td>17256</td>
                            <td>Nikila Silva</td>
                            <td>Night Patrol</td>
                            <td>Town</td>
                        </tr>
                        <tr>
                            <td>16321</td>
                            <td>Nadun Madusanka</td>
                            <td>Station Duty</td>
                            <td>Police Station</td>
                        </tr>
                        <tr>
                            <td>13452</td>
                            <td>Abdhul Basith</td>
                            <td>Cross Line Handling</td>
                            <td>Joseph Central College</td>
                        </tr>
                        <tr>
                            <td>14678</td>
                            <td>Nimsara Wickramathanthri</td>
                            <td>Border Checkpoint</td>
                            <td>Highway Entrance</td>
                        </tr>
                        <tr>
                            <td>17892</td>
                            <td>Chamath Abeysinghe</td>
                            <td>Crowd Management</td>
                            <td>Musical Event</td>
                        </tr>
                        <tr>
                            <td>12543</td>
                            <td>Heshan Fernando</td>
                            <td>Traffic Control</td>
                            <td>Roundabout</td>
                        </tr>
                        <tr>
                            <td>13987</td>
                            <td>Dinuka Perera</td>
                            <td>VIP Escort</td>
                            <td>Stage II Road Junction</td>
                        </tr>
                        <tr>
                            <td>14236</td>
                            <td>Ravindu Wijesinghe</td>
                            <td>Event Security</td>
                            <td>Stage I Housing Area</td>
                        </tr>
                        <tr>
                            <td>16897</td>
                            <td>Samadhi Peiris</td>
                            <td>Night Patrol</td>
                            <td>Town</td>
                        </tr>
                        <tr>
                            <td>17562</td>
                            <td>Mahesh Samarasinghe</td>
                            <td>Border Checkpoint</td>
                            <td>Main Road</td>
                        </tr>
                        <tr>
                            <td>15834</td>
                            <td>Ruwan Lakmal</td>
                            <td>Crowd Management</td>
                            <td>Beach</td>
                        </tr>
                        <tr>
                            <td>12098</td>
                            <td>Isuru Dissanayake</td>
                            <td>Traffic Control</td>
                            <td>Beach Road</td>
                        </tr>
                        <tr>
                            <td>17345</td>
                            <td>Sachith Karunaratne</td>
                            <td>Event Security</td>
                            <td>Exhibition Center</td>
                        </tr>
                        <tr>
                            <td>18234</td>
                            <td>Janith Rathnayake</td>
                            <td>Night Patrol</td>
                            <td>Exhibition Center</td>
                        </tr>
                        <tr>
                            <td>16245</td>
                            <td>Ranuka Jayasooriya</td>
                            <td>Cross Line Handling</td>
                            <td>Naval College</td>
                        </tr>
                        <tr>
                            <td>18567</td>
                            <td>Chamal Silva</td>
                            <td>Traffic Control</td>
                            <td>Town</td>
                        </tr>
                        <tr>
                            <td>14123</td>
                            <td>Sumudu Perera</td>
                            <td>VIP Escort</td>
                            <td>Roundabout</td>
                        </tr>
                        <tr>
                            <td>15789</td>
                            <td>Pradeep Gunasekara</td>
                            <td>Station Duty</td>
                            <td>Police Station</td>
                        </tr>
                        <tr>
                            <td>19045</td>
                            <td>Kusal Ratnayake</td>
                            <td>Border Checkpoint</td>
                            <td>Roundabout</td>
                        </tr>
                        <tr>
                            <td>14856</td>
                            <td>Asela Gunarathna</td>
                            <td>Event Security</td>
                            <td>Hambantota Port</td>
                        </tr>
                        <tr>
                            <td>13045</td>
                            <td>Chandima Liyanage</td>
                            <td>Crowd Management</td>
                            <td>Stage I Housing Area</td>
                        </tr>
                    </tbody>

                </table>
            </div>

        </div>
    </div>
</main>

<?php include_once "../../includes/footer.php"; ?>