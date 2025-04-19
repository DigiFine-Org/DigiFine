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
           f.offence_type, f.nature_of_offence, f.offence, f.fine_status, f.is_reported,f.is_solved
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
            <div class="page-header">
                <h1>Court Offences</h1>
                
            </div>

            <div class="cards-container">
                <?php if (!empty($fines)): ?>
                    <?php foreach ($fines as $fine): ?>
                        <div class="case-card <?= $fine['fine_status'] ?> <?= $fine['is_reported'] ? 'reported' : '' ?>">
                            <div class="card-header">
                                <span class="case-id">Case #CR-<?= htmlspecialchars($fine['id']) ?></span>
                                <!-- <span class="status-badge <?= $fine['fine_status'] ?>">
                                    <?= ucfirst(htmlspecialchars($fine['fine_status'])) ?>
                                    <?= $fine['is_reported'] ? ' | Reported' : '' ?>
                                </span> -->
                            </div>
                            
                            <div class="card-body">
                                <div class="case-detail">
                                    <span class="detail-label">Driver ID</span>
                                    <span class="detail-value"><?= htmlspecialchars($fine['driver_id']) ?></span>
                                </div>
                                
                                <div class="case-detail">
                                    <span class="detail-label">Vehicle No.</span>
                                    <span class="detail-value"><?= htmlspecialchars($fine['license_plate_number']) ?></span>
                                </div>
                                
                                <div class="case-detail">
                                    <span class="detail-label">Issued On</span>
                                    <span class="detail-value">
                                        <?= date('d M Y', strtotime($fine['issued_date'])) ?>
                                         <?= date('h:i A', strtotime($fine['issued_time'])) ?>
                                    </span>
                                </div>
                                
                                <div class="case-detail full-width">
                                    <span class="detail-label">Offence</span>
                                    <span class="detail-value"><?= htmlspecialchars($fine['offence']) ?></span>
                                </div>
                                
                                <div class="case-actions">
                                    <a href="view-fine-details.php?id=<?= htmlspecialchars($fine['id']) ?>" 
                                       class="btn btn-view" 
                                       title="View Full Details">
                                       <i class="fas fa-eye"></i> Details
                                    </a>
                                    
                                    <?php if ($fine['is_solved'] == 1): ?>
                                        <button class="btn btn-remove" disabled>
                                            Resolved
                                        </button>
                                    <?php else: ?>
                                        <button class="btn btn-remove" 
                                                data-case-id="<?= htmlspecialchars($fine['id']) ?>">
                                            Remove
                                        </button>
                                    <?php endif; ?>

                               </div>
                               
                            </div>
                        </div>
                    <?php endforeach ?>
                <?php else: ?>
                    <div class="empty-state">
                        <img src="../../../assets/no-cases.png" alt="No cases found">
                        <h3>No Court Cases Found</h3>
                        <p>There are currently no court offence records matching your criteria.</p>
                    </div>
                <?php endif ?> 
            </div>
        </div>
    </div>
</main>



<style>




.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
    flex-wrap: wrap;
    gap: 15px;
}

.page-header h1 {
    font-size: 24px;
    color: #1e293b;
    margin: 0;
}



.form-select {
    padding: 8px 12px;
    border: 1px solid #cbd5e1;
    border-radius: 6px;
    background-color: white;
    font-size: 14px;
    min-width: 180px;
}



.cards-container {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 20px;
    margin-top: 20px;
}

.case-card {
    background: white;
    border-radius: 8px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    border-left: 4px solid #94a3b8;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    border-left-color:var(--color-dark-blue);
}

.case-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}





/* Card Header */
.card-header {
    padding: 16px;
    background: #f1f5f9;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px solid #e2e8f0;
}

.case-id {
    font-weight: 600;
    color: #1e293b;
    font-size: 15px;
}

.card-body {
    padding: 0;
}

.case-detail {
    display: flex;
    justify-content: space-between;
    padding: 12px 16px;
    border-bottom: 1px solid #f1f5f9;
}

.case-detail:last-child {
    border-bottom: none;
}

.case-detail.full-width {
    flex-direction: column;
    gap: 4px;
}

.detail-label {
    font-weight: 500;
    color: #64748b;
    font-size: 13px;
}

.detail-value {
    color: #1e293b;
    text-align: right;
    font-size: 14px;
}

.case-detail.full-width .detail-value {
    text-align: left;
}

/* Card Actions */
.case-actions {
    padding: 12px 16px;
    display: flex;
    gap: 8px;
    background-color: #f8fafc;
    border-top: 1px solid #e2e8f0;
}

.btn-view {
    background-color: #e0f2fe;
    color: #0369a1;
}

.btn-view:hover {
    background-color: #bae6fd;
}



.btn-remove {
    background-color: #f0fdf4;
    color: #15803d;
    margin-left: auto;
    
}

.btn-remove:hover {
    background-color: #dcfce7;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 50px 20px;
    grid-column: 1 / -1;
    background: white;
    border-radius: 8px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}


/* Responsive Adjustments */
@media (max-width: 768px) {
    .cards-container {
        grid-template-columns: 1fr;
    }
    
    .page-header {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .filter-actions {
        width: 100%;
        flex-direction: column;
        align-items: flex-start;
    }
    
    .filter-controls {
        width: 100%;
    }
    
    .form-select {
        flex: 1;
    }
}

/* Icons */
.fas {
    font-size: 13px;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const removeButtons = document.querySelectorAll('.btn-remove');

    removeButtons.forEach(button => {
        button.addEventListener('click', () => {
            const caseId = button.dataset.caseId;
            if (!caseId) return;

            fetch('remove-fine-details.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `case_id=${encodeURIComponent(caseId)}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    button.textContent = 'Resolved';
                    button.disabled = true;
                    button.classList.add('resolved');
                } else {
                    alert('Failed to resolve case');
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });
    });
});
</script>



<?php include_once "../../../includes/footer.php"; ?>
