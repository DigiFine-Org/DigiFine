<?php
$pageConfig = [
    'title' => 'Police Station Fines',
    'styles' => ["../../dashboard.css"],
    'scripts' => ["../../dashboard.js"],
    'authRequired' => true
];

require_once "../../../db/connect.php";
include_once "../../../includes/header.php";

if ($_SESSION['user']['role'] !== 'oic') {
    die("Unauthorized user!");
}

$oic_id = $_SESSION['user']['id'] ?? null;

if (!$oic_id) {
    die("Unauthorized access.");
}

// Retrieve OIC's police station ID
$sql = "SELECT * FROM officers WHERE is_oic = '1' AND id = ? LIMIT 1";
$oic_stmt = $conn->prepare($sql);
$oic_stmt->bind_param("i", $oic_id);
$oic_stmt->execute();
$result = $oic_stmt->get_result();
if ($result->num_rows === 0) {
    die("OIC not found or police station not assigned.");
}
$oic_data = $result->fetch_assoc();
$police_station_id = $oic_data['police_station'];

$offenceTypes = [];
$offences = [];
$fineStatuses = ['Paid', 'Overdue', 'Pending'];

$stmt = $conn->prepare("SELECT DISTINCT offence_type FROM fines WHERE offence_type IS NOT NULL AND offence_type != ''");
$stmt->execute();
$result = $stmt->get_result();
$offenceTypes = $result->fetch_all(MYSQLI_ASSOC);

$stmt = $conn->prepare("SELECT DISTINCT offence FROM fines WHERE offence IS NOT NULL AND offence != ''");
$stmt->execute();
$result = $stmt->get_result();
$offences = $result->fetch_all(MYSQLI_ASSOC);

$stmt->close();

// Filters for fines
$whereClauses = [];
$params = [];
$types = '';

// Handle filters
if (isset($_GET['fine_id']) && !empty($_GET['fine_id'])) {
    $whereClauses[] = "f.id LIKE ?";
    $params[] = "%" . $_GET['fine_id'] . "%";
    $types .= 's';
}

if (isset($_GET['police_id']) && !empty($_GET['police_id'])) {
    $whereClauses[] = "f.police_id LIKE ?";
    $params[] = "%" . $_GET['police_id'] . "%";
    $types .= 's';
}

if (isset($_GET['driver_id']) && !empty($_GET['driver_id'])) {
    $whereClauses[] = "f.driver_id LIKE ?";
    $params[] = "%" . $_GET['driver_id'] . "%";
    $types .= 's';
}

if (isset($_GET['date-from']) && !empty($_GET['date-from'])) {
    $whereClauses[] = "f.issued_date >= ?";
    $params[] = $_GET['date-from'];
    $types .= 's';
}

if (isset($_GET['date-to']) && !empty($_GET['date-to'])) {
    $whereClauses[] = "f.issued_date <= ?";
    $params[] = $_GET['date-to'];
    $types .= 's';
}

if (isset($_GET['price-from']) && !empty($_GET['price-from'])) {
    $whereClauses[] = "f.fine_amount >= ?";
    $params[] = $_GET['price-from'];
    $types .= 'd';
}

if (isset($_GET['price-to']) && !empty($_GET['price-to'])) {
    $whereClauses[] = "f.fine_amount <= ?";
    $params[] = $_GET['price-to'];
    $types .= 'd';
}

if (isset($_GET['offence_type']) && !empty($_GET['offence_type'])) {
    $whereClauses[] = "f.offence_type = ?";
    $params[] = $_GET['offence_type'];
    $types .= 's';
}

if (isset($_GET['offence']) && !empty($_GET['offence'])) {
    $whereClauses[] = "f.offence = ?";
    $params[] = $_GET['offence'];
    $types .= 's';
}

if (isset($_GET['fine_status']) && !empty($_GET['fine_status'])) {
    $whereClauses[] = "f.fine_status = ?";
    $params[] = $_GET['fine_status'];
    $types .= 's';
}

if (isset($_GET['is_reported']) && !empty($_GET['is_reported'])) {
    $whereClauses[] = "f.is_reported = ?";
    $params[] = $_GET['is_reported'];
    $types .= 'i';
}

// Base query
$query = "
    SELECT f.id, f.police_id, f.driver_id, f.license_plate_number, f.issued_date, f.issued_time, 
           f.offence_type, f.nature_of_offence, f.offence, f.fine_status, f.is_reported, f.fine_amount
    FROM fines f
    INNER JOIN officers o ON f.police_id = o.id
    WHERE o.police_station = ?
";

// Append dynamic WHERE clauses
if (!empty($whereClauses)) {
    $query .= " AND " . implode(" AND ", $whereClauses);
}

// Prepare and execute the query
$stmt = $conn->prepare($query);

// Bind parameters dynamically
if (!empty($params)) {
    $stmt->bind_param("i" . $types, $police_station_id, ...$params);
} else {
    $stmt->bind_param("i", $police_station_id);
}

$stmt->execute();
$fines_result = $stmt->get_result();
$fines = $fines_result->fetch_all(MYSQLI_ASSOC);

$stmt->close();
$oic_stmt->close();
$conn->close();
?>

<main>
    <?php include_once "../../includes/navbar.php" ?>

    <div class="dashboard-layout">
        <?php include_once "../../includes/sidebar.php" ?>
        <div class="content">
            <div class="container x-large no-border">
                <h1>Fines Issued by Station Officers</h1>
                <!-- FILTER FINES -->
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
                    <th>Is Reported</th>
                    <th>FINE STATUS</th>
                    <th>Amount</th>
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
                            <td><?= $fine['is_reported'] ? 'Yes' : 'No' ?></td>
                            <td><?= htmlspecialchars($fine['fine_status']) ?></td>
                            <td><?= htmlspecialchars($fine['fine_amount']) ?></td>
                            <td>
                                <a href="view-fine-details.php?id=<?= htmlspecialchars($fine['id']) ?>" class="btn">View</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="10">No fines found for the selected filters.</td>
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