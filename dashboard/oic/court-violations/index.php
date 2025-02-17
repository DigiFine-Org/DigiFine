<?php
$pageConfig = [
    'title' => 'Police Station Fines',
    'styles' => ["../../dashboard.css","a.css"],
    'scripts' => ["../../dashboard.js"],
    'authRequired' => true
];

require_once "../../../db/connect.php";
include_once "../../../includes/header.php";

if ($_SESSION['user']['role'] !== 'oic') {
    die("unauthorized user!");
}

$oic_id = $_SESSION['user']['id'] ?? null;

if (!$oic_id) {
    die("Unauthorized access.");
}

// Retrieve OIC's police station ID
$sql = "SELECT * FROM officers WHERE is_oic = '1' AND id = ? LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $oic_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    die("OIC not found or police station not assigned.");
}
$oic_data = $result->fetch_assoc();
$police_station_id = $oic_data['police_station'];

// Retrieve filter from GET
$fine_status_filter = isset($_GET['fine_status']) ? htmlspecialchars($_GET['fine_status']) : null;

// Fetch fines related to the OIC's police station and where offence_type is court
$fines_sql = "SELECT f.id, f.police_id, f.driver_id, f.license_plate_number, f.issued_date, f.issued_time, 
           f.offence_type, f.nature_of_offence, f.offence, f.fine_status, f.is_reported
    FROM fines f
    INNER JOIN officers o ON f.police_id = o.id
    WHERE o.police_station = ? AND f.offence_type = 'court'
";

if (!empty($fine_status_filter)) {
    if ($fine_status_filter === 'reported') {
        $fines_sql = " AND f.is_reported = 1";
        $fines_stmt = $conn->prepare($fines_sql);
        $fines_stmt->bind_param("i", $police_station_id);
    } else {
        $fines_sql .= " AND f.fine_status = ?";
        $fines_stmt = $conn->prepare($fines_sql);
        $fines_stmt->bind_param("is", $police_station_id, $fine_status_filter);
    }
} else {
    $fines_stmt = $conn->prepare($fines_sql);
    $fines_stmt->bind_param("i", $police_station_id);
}

$fines_stmt->execute();
$fines_result = $fines_stmt->get_result();
$fines = $fines_result->fetch_all(MYSQLI_ASSOC);

$fines_stmt->close();
$stmt->close();
$conn->close();
?>

<main>
    <?php include_once "../../includes/navbar.php" ?>

    <div class="dashboard-layout">
        <?php include_once "../../includes/sidebar.php" ?>
        <div class="content">
            <div class="container x-large no-border">
                <h1>Court Offences</h1>
                <!-- FILTER FINES -->
                <form method="get" action="" style="margin-bottom: 10px;">
                    <div class="wrapper">
                        <select name="fine_status" id="filter"
                            style="padding: 4px 6px; width:100px; margin-right:10px;">
                            <option value="">All</option>
                            <option value="reported" <?= isset($_GET['fine_status']) && $_GET['fine_status'] === 'reported' ? 'selected' : '' ?>>Reported</option>
                            <option value="overdue" <?= isset($_GET['fine_status']) && $_GET['fine_status'] === 'overdue' ? 'selected' : '' ?>>Overdue</option>
                            <option value="pending" <?= isset($_GET['fine_status']) && $_GET['fine_status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                            <option value="paid" <?= isset($_GET['fine_status']) && $_GET['fine_status'] === 'paid' ? 'selected' : '' ?>>Paid</option>
                        </select>
                        <button type="submit" class="btn">Apply</button>
                    </div>
                </form>

                <div class="tiles-container">
                    <?php if (!empty($fines)): ?>
                        <?php foreach ($fines as $fine): ?>
                            <div class="fine-tile <?= $fine['offence_type'] === 'court' ? 'court-violation' : '' ?>">
                                <h2>Police ID: <?= htmlspecialchars($fine['police_id']) ?></h2>
                                <p><strong>Driver ID:</strong> <?= htmlspecialchars($fine['driver_id']) ?></p>
                                <p><strong>Issued Date:</strong> <?= htmlspecialchars($fine['issued_date']) ?></p>
                                <p><strong>Offence Type:</strong> <?= htmlspecialchars($fine['offence_type']) ?></p>
                                <p><strong>Offence:</strong> <?= htmlspecialchars($fine['offence']) ?></p>
                                <p><strong>Fine Status:</strong> <?= htmlspecialchars($fine['fine_status']) ?></p>
                                <p><strong>Reported:</strong> <?= $fine['is_reported'] == 1 ? 'Yes' : 'No' ?></p>
                                <a href="view-fine-details.php?id=<?= htmlspecialchars($fine['id']) ?>" class="btn">View Details</a>
                                <a href="#" class="btn delete-fine" data-id="<?= htmlspecialchars($fine['id']) ?>">Remove</a>

                            </div>
                            
                        <?php endforeach ?>
                    <?php else: ?>
                        <p>"No Court Violations to show"</p>
                    <?php endif ?> 
                </div>
            </div>
        </div>
    </div>
</main>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


<script>//study this code
    $(document).on('click', '.delete-fine', function (e) {
        e.preventDefault();

        var fineId = $(this).data('id');
        var $row = $(this).closest('tr'); // Adjust selector if not in a table

        if (confirm("Are you sure you want to delete this fine?")) {
            $.ajax({
                url: 'remove-fine-details.php',
                type: 'POST',
                data: { id: fineId },
                success: function (response) {
                    if (response.status === 'success') {
                        alert(response.message);
                        $row.remove(); // Remove the row from the table
                    } else {
                        alert(response.message);
                    }
                },
                error: function () {
                    alert('Failed to delete the fine. Please try again.');
                }
            });
        }
    });
</script>

<?php include_once "../../../includes/footer.php"; ?>
