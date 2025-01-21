<?php
$pageConfig = [
    'title' => 'Fines',
    'styles' => ["../../dashboard.css"],
    'scripts' => ["../../dashboard.js"],
    'authRequired' => true
];

include_once "../../../includes/header.php";
require_once "../../../db/connect.php";

if ($_SESSION['user']['role'] !== 'admin') {
    die("unauthorized user!");
}

// Fetch filter options
$offenceTypes = [];
$offences = [];
$fineStatuses = ['Paid', 'Overdue', 'Pending'];

$stmt = $conn->prepare("SELECT DISTINCT offence_type FROM fines");
$stmt->execute();
$result = $stmt->get_result();
$offenceTypes = $result->fetch_all(MYSQLI_ASSOC);

$stmt = $conn->prepare("SELECT DISTINCT offence FROM fines");
$stmt->execute();
$result = $stmt->get_result();
$offences = $result->fetch_all(MYSQLI_ASSOC);

$stmt->close();

// Handle filters
$whereClauses = [];
$params = [];
$types = '';

if (isset($_GET['fine_id']) && !empty($_GET['fine_id'])) {
    $whereClauses[] = "id LIKE ?";
    $params[] = "%" . $_GET['fine_id'] . "%";
    $types .= 's';
}

if (isset($_GET['police_id']) && !empty($_GET['police_id'])) {
    $whereClauses[] = "police_id LIKE ?";
    $params[] = "%" . $_GET['police_id'] . "%";
    $types .= 's';
}

if (isset($_GET['driver_id']) && !empty($_GET['driver_id'])) {
    $whereClauses[] = "driver_id LIKE ?";
    $params[] = "%" . $_GET['driver_id'] . "%";
    $types .= 's';
}

if (isset($_GET['date-from']) && !empty($_GET['date-from'])) {
    $whereClauses[] = "issued_date >= ?";
    $params[] = $_GET['date-from'];
    $types .= 's';
}

if (isset($_GET['date-to']) && !empty($_GET['date-to'])) {
    $whereClauses[] = "issued_date <= ?";
    $params[] = $_GET['date-to'];
    $types .= 's';
}

if (isset($_GET['offence_type']) && !empty($_GET['offence_type'])) {
    $whereClauses[] = "offence_type = ?";
    $params[] = $_GET['offence_type'];
    $types .= 's';
}

if (isset($_GET['offence']) && !empty($_GET['offence'])) {
    $whereClauses[] = "offence = ?";
    $params[] = $_GET['offence'];
    $types .= 's';
}

if (isset($_GET['fine_status']) && !empty($_GET['fine_status'])) {
    $whereClauses[] = "fine_status = ?";
    $params[] = $_GET['fine_status'];
    $types .= 's';
}

$query = "SELECT id, police_id, driver_id, license_plate_number, issued_date, issued_time,
          offence_type, nature_of_offence, offence, fine_status FROM fines";
if (!empty($whereClauses)) {
    $query .= " WHERE " . implode(' AND ', $whereClauses);
}

$stmt = $conn->prepare($query);
if ($types && $stmt) {
    $stmt->bind_param($types, ...$params);
}

if (!$stmt->execute()) {
    die("Query error!!!");
}

$result = $stmt->get_result();
$fines = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<main>
    <?php include_once "../../includes/navbar.php" ?>

    <div class="dashboard-layout">
        <?php include_once "../../includes/sidebar.php" ?>
        <div class="content">
            <div class="table-container">

                <h2>All Fines Issued</h2>

                <!-- Filter Form -->
                <div class="feild">
                    <button class="btn margintop marginbottom">Filter Results</button>
                </div>

                <?php include_once "./filter-results.php"; ?>
            </div>
        </div>
    </div>

    <!-- Fines Table -->
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>FINE ID</th>
                    <th>POLICE ID</th>
                    <th>DRIVER ID</th>
                    <th>ISSUED DATE</th>
                    <th>OFFENCE TYPE</th>
                    <th>OFFENCE</th>
                    <th>FINE STATUS</th>
                    <th>ACTION</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($fines)): ?>
                    <?php foreach ($fines as $fine): ?>
                        <tr>
                            <td><?= htmlspecialchars($fine['id']) ?></td>
                            <td><?= htmlspecialchars($fine['police_id']) ?></td>
                            <td><?= htmlspecialchars($fine['driver_id']) ?></td>
                            <td><?= htmlspecialchars($fine['issued_date']) ?></td>
                            <td><?= htmlspecialchars($fine['offence_type']) ?></td>
                            <td><?= htmlspecialchars($fine['offence']) ?></td>
                            <td><?= htmlspecialchars($fine['fine_status']) ?></td>
                            <td>
                                <a href="view-fine-details.php?id=<?= htmlspecialchars($fine['id']) ?>"
                                    class="btn">View</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7">No fines found for the selected filters.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>

<?php include_once "../../../includes/footer.php"; ?>

<script>
    document.querySelector('.btn').addEventListener('click', function() {
        var filterForm = document.getElementById('filter-form');
        if (filterForm.style.display === 'none') {
            filterForm.style.display = 'block';
        } else {
            filterForm.style.display = 'none';
        }
    });
</script>