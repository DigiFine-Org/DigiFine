<?php
$pageConfig = [
    'title' => 'Police Station Fines',
    'styles' => ["../../dashboard.css", "a.css"],
    'scripts' => ["../../dashboard.js", "court-offences.js"],
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
           f.offence_type, f.nature_of_offence, f.offence, f.fine_status, f.is_reported, f.is_solved
    FROM fines f
    INNER JOIN officers o ON f.police_id = o.id
    WHERE o.police_station = ? AND f.offence_type = 'court'
    ORDER BY f.issued_date DESC ,  f.is_solved=1";

if (!empty($fine_status_filter)) {
    if ($fine_status_filter === 'reported') {
        $fines_sql .= " AND f.is_reported = 1";
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
            <div class="page-header">
                <h1>Court Offences</h1>
            </div>

            <?php if (!empty($fines)): ?>
                <div class="table-responsive">
                    <table class="fines-table">
                        <thead>
                            <tr>
                                <th>Case ID</th>
                                <th>Driver ID</th>
                                <th>Vehicle No.</th>
                                <th>Issued On</th>
                                <th>Offence</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($fines as $fine): ?>
                                <tr class="<?= $fine['fine_status'] ?> <?= $fine['is_reported'] ? 'reported' : '' ?>">
                                    <td>#CR-<?= htmlspecialchars($fine['id']) ?></td>
                                    <td><?= htmlspecialchars($fine['driver_id']) ?></td>
                                    <td><?= htmlspecialchars($fine['license_plate_number']) ?></td>
                                    <td>
                                        <?= date('d M Y', strtotime($fine['issued_date'])) ?><br>
                                        <?= date('h:i A', strtotime($fine['issued_time'])) ?>
                                    </td>
                                    <td><?= htmlspecialchars($fine['nature_of_offence']) ?></td>
                                    
                                    <td class="actions">
                                    <button onclick="window.location.href='view-fine-details.php?id=<?= htmlspecialchars($fine['id']) ?>'" 
                                            class="btn btn-view" 
                                            title="View Full Details">
                                        Details
                                    </button>
                                        
                                        <?php if ($fine['is_solved'] == 1): ?>
                                            <button class="btn btn-remove" disabled>
                                                Resolved
                                            </button>
                                        <?php else: ?>
                                            <button class="btn btn-resolve" data-case-id="<?= htmlspecialchars($fine['id']) ?>">                                             
                                            Remove</button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <img src="../../../assets/no-cases.png" alt="No cases found">
                    <h3>No Court Cases Found</h3>
                    <p>There are currently no court offence records matching your criteria.</p>
                </div>
            <?php endif ?>
        </div>
    </div>
</main>

<!-- Confirmation Popup -->
<div id="court-resolve-popup" class="court-popup">
    <div class="court-popup-content">
        <h3>Resolve Court Case</h3>
        <form id="court-resolve-form">
            <input type="hidden" id="court-case-id" name="case_id" value="">
            
            <div class="court-form-group">
                <label for="court-reference-number">Reference Document Number *</label>
                <input type="text" id="court-reference-number" name="reference_number" required>
            </div>
            
            <div class="court-form-group">
                <label>Driver's License Action *</label>
                <div>
                    <label><input type="radio" name="license_action" value="0" checked> Keep License</label>
                    <label><input type="radio" name="license_action" value="1"> Cancel License</label>
                </div>
            </div>
            
            <div class="court-popup-buttons">
                <button type="button" class="court-btn-cancel">Cancel</button>
                <button type="submit" class="court-btn-confirm">Confirm</button>
            </div>
        </form>
    </div>
</div>


<?php include_once "../../../includes/footer.php"; ?>