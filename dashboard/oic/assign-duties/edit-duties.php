<?php
$pageConfig = [
    'title' => 'Edit Duties',
    'styles' => ["../../dashboard.css", "./edit-duties.css"],
    'scripts' => ["../../dashboard.js"],
    'authRequired' => true
];

include_once "../../../includes/header.php";
include_once "../../../db/connect.php";

if ($_SESSION['user']['role'] !== 'oic') {
    die("Unauthorized user!");
}

// Fetch OIC's station
$oicId = $_SESSION['user']['id'];
$stationQuery = "SELECT police_station FROM officers WHERE id = ? AND is_oic = 1";
$stationStmt = $conn->prepare($stationQuery);

if (!$stationStmt) {
    die("Database error: Failed to prepare station query. " . $conn->error);
}

$stationStmt->bind_param("i", $oicId);
$stationStmt->execute();
$stationResult = $stationStmt->get_result();

if (!$stationResult) {
    die("Database error: Failed to execute station query. " . $conn->error);
}

$stationData = $stationResult->fetch_assoc();
if (!$stationData) {
    die("No station found for the current OIC.");
}

$stationId = $stationData['police_station'];

// Fetch all duties assigned by this OIC
$dutiesQuery = "SELECT d.id, d.duty, d.duty_date, d.duty_time_start, d.duty_time_end, d.notes, o.fname, o.lname 
                FROM assigned_duties d
                JOIN officers o ON d.police_id = o.id 
                WHERE o.police_station = ? 
                ORDER BY d.duty_date, d.duty_time_start";
$dutiesStmt = $conn->prepare($dutiesQuery);

if (!$dutiesStmt) {
    die("Database error: Failed to prepare duties query. " . $conn->error);
}

$dutiesStmt->bind_param("i", $stationId);
$dutiesStmt->execute();
$dutiesResult = $dutiesStmt->get_result();

if (!$dutiesResult) {
    die("Database error: Failed to execute duties query. " . $conn->error);
}

if (isset($_SESSION['message'])) {
    if ($_SESSION['message'] === 'deleted') {
        $message = "Duty Deleted successfully!";
        unset($_SESSION['message']); // Clear the session message
        include '../../../includes/alerts/success.php';
    } elseif ($_SESSION['message'] === 'edited') {
        $message = "Duty Edited successfully!";
        unset($_SESSION['message']); // Clear the session message
        include '../../../includes/alerts/success.php';
    } else {
        $message = $_SESSION['message']; // Store the message
        unset($_SESSION['message']); // Clear the session message
        include '../../../includes/alerts/failed.php';
    }
}
?>

<main>
    <?php include_once "../../includes/navbar.php"; ?>
    <div class="dashboard-layout">
        <?php include_once "../../includes/sidebar.php"; ?>
        <div class="content">
            <!-- <div class="container"> -->
            <h1>Edit or Delete Duties</h1>
            <table class="duties-table">
                <thead>
                    <tr>
                        <th>Officer</th>
                        <th>Duty</th>
                        <th>Date</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                        <th>Notes</th>
                        <th>Edit</th>
                        <th>Delete</th>

                    </tr>
                </thead>
                <tbody>
                    <?php while ($duty = $dutiesResult->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $duty['fname'] . ' ' . $duty['lname']; ?></td>
                            <td><?php echo $duty['duty']; ?></td>
                            <td><?php echo $duty['duty_date']; ?></td>
                            <td><?php echo $duty['duty_time_start']; ?></td>
                            <td><?php echo $duty['duty_time_end']; ?></td>
                            <td><?php echo $duty['notes']; ?></td>
                            <td>
                                <a href="edit-duty-form.php?id=<?php echo $duty['id']; ?>" class="btn btn-edit">Edit</a>

                            </td>
                            <td>
                                <form action="delete-duty-handler.php" method="POST" style="display:inline;">
                                    <input type="hidden" name="dutyId" value="<?php echo $duty['id']; ?>">
                                    <button type="submit" class="btn btn-delete" onclick="return confirm('Are you sure you want to delete this duty?');">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
    </div>
</main>

<?php
// Close statements and connection
if (isset($stationStmt))
    $stationStmt->close();
if (isset($dutiesStmt))
    $dutiesStmt->close();

include_once "../../../includes/footer.php";
?>