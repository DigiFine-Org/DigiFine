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
                    <input type="text" id="police_id" name="police_id" style="height:30px;" required>
                    <button class="btn" style="margin-top:10px;" type="submit" name="search">Search</button>
                </form>

                <?php
                if (isset($_POST['search'])) {
                    // Sanitize user input to prevent SQL injection
                    $police_id = filter_input(INPUT_POST, 'police_id', FILTER_SANITIZE_STRING);
                
                    // Check if police_id is valid
                    if (!$police_id) {
                        die("Invalid Police ID.");
                    }
                
                    // Prepare the SQL query
                    $query = "SELECT police_id, duty, notes, duty_date FROM assigned_duties WHERE police_id=? AND submitted = 1";
                    $stmt = $conn->prepare($query);
                
                    if (!$stmt) {
                        die("Error preparing statement: " . $conn->error);
                    }
                
                    $stmt->bind_param("s", $police_id);
                    $stmt->execute();
                    $result = $stmt->get_result();
                
                    if ($result->num_rows > 0) {
                        echo "<table border='1' class='duty-table'>
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
                        echo "<p>No records found for the given Police ID or submission status.</p>";
                    }
                
                    $stmt->close();
                }
                
                $conn->close();
                
                ?>
            </div>
        </div>
    </div>
</main>

<?php include_once "../../../includes/footer.php"; ?>
