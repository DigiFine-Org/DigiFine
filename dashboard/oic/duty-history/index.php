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

// Ensure the user is authorized to access this page
if ($_SESSION['user']['role'] !== 'oic') {
    die("Unauthorized user!");
}
?>

<main>
    <?php include_once "../../includes/navbar.php"; ?>
    <div class="dashboard-layout">
        <?php include_once "../../includes/sidebar.php"; ?>
        <div class="content">
            <div class="container">
                <h1>Duty History</h1>

                <!-- Search Form -->
                <form action="" method="POST" style="height:100px">
                    <label for="police_id">Enter Police ID:</label>
                    <input type="text" id="police_id" name="police_id" style="height:30px;" 
                        value="<?= isset($_POST['police_id']) ? htmlspecialchars($_POST['police_id']) : '' ?>" required>
                    
                    <button class="btn" style="margin-top:10px;" type="submit" name="search">Search</button>
                </form>

                <?php
                if (isset($_POST['search'])) {
                    $police_id = filter_input(INPUT_POST, 'police_id', FILTER_SANITIZE_STRING);
                    
                    if (!$police_id || !ctype_digit($police_id)) {
                        echo "<p style='color: red;'>Please enter a valid Police ID.</p>";
                    } else {

                        $oic_station_query = "SELECT police_station FROM officers WHERE id = ?";
                        $oic_stmt = $conn->prepare($oic_station_query);
                        $oic_stmt->bind_param("s", $_SESSION['user']['id']);
                        $oic_stmt->execute();
                        $oic_result = $oic_stmt->get_result();
                        
                        if ($oic_result->num_rows === 0) {
                            die("Error: OIC record not found.");
                        }

                        $oic_station = $oic_result->fetch_assoc()['police_station'];
                        $oic_stmt->close();

                        $query = "SELECT ad.police_id, ad.duty, ad.notes, ad.duty_date 
                                FROM assigned_duties ad
                                JOIN officers o ON ad.police_id = o.id
                                WHERE ad.police_id = ? 
                                AND ad.submitted = 1
                                AND o.police_station = ?";
                        $stmt = $conn->prepare($query);
                        
                        if ($stmt) {
                            $stmt->bind_param("ss", $police_id,$oic_station);
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
                                echo "<p style='color: red;'>No duty history found for Police ID: <strong>" . htmlspecialchars($police_id) . "</strong></p>";
                            }
                            $stmt->close();
                        } else {
                            echo "<p style='color: red;'>Database error. Please try again.</p>";
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
