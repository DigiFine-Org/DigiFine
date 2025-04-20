<?php
$pageConfig = [
    'title' => 'Fine Details',
    'styles' => ["../../dashboard.css"],
    'scripts' => ["../../dashboard.js"],
    'authRequired' => true
];

include_once "../../../includes/header.php";
require_once "../../../db/connect.php";

$oic_id = $_SESSION['user']['id'] ?? null;
if (!$oic_id) {
    die("Unauthorized access.");
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid or missing fine ID.");
}

$fine_id = intval($_GET['id']);

// Ensure the fine belongs to the OIC's police station
$sql = "
    SELECT f.id, f.police_id, f.driver_id, f.license_plate_number, f.issued_date, f.issued_time, expire_date, 
           f.offence_type,f.location, f.nature_of_offence, f.offence, f.fine_status, f.fine_amount, f.is_reported, f.reported_description, 
           f.evidence
    FROM fines f
    INNER JOIN officers o ON f.police_id = o.id
    WHERE f.id = ? AND o.police_station = (SELECT police_station FROM officers WHERE id = ?)
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $fine_id, $oic_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("No fine found or unauthorized access.");
}

$fine = $result->fetch_assoc();
$stmt->close();
$conn->close();
?>

<main>
    <?php include_once "../../includes/navbar.php" ?>
    <div class="dashboard-layout">
        <?php include_once "../../includes/sidebar.php" ?>
        <div class="content">
            <div class="container large">
                <h1>Fine Details</h1>
                <div class="data-line">
                    <span>Police ID:</span>
                    <p><?= htmlspecialchars($fine['police_id']) ?></p>
                </div>
                <div class="data-line">
                    <span>Driver ID:</span>
                    <p><?= htmlspecialchars($fine['driver_id']) ?></p>
                </div>
                <div class="data-line">
                    <span>License Plate Number:</span>
                    <p><?= htmlspecialchars($fine['license_plate_number']) ?></p>
                </div>
                <div class="data-line">
                    <span>Issued Date:</span>
                    <p><?= htmlspecialchars($fine['issued_date']) ?></p>
                </div>
                <div class="data-line">
                    <span>Issued Time:</span>
                    <p><?= htmlspecialchars($fine['issued_time']) ?></p>
                </div>
                <div class="data-line">
                    <span>Expiry Date:</span>
                    <p><?= htmlspecialchars($fine['expire_date']) ?></p>
                </div>
                <div class="data-line">
                    <span>Offence Type:</span>
                    <p><?= htmlspecialchars($fine['offence_type']) ?></p>
                </div>
                <div class="data-line">
                    <span>Offence:</span>
                    <p><?= htmlspecialchars($fine['offence']) ?></p>
                </div>
                <div class="data-line">
                    <span>Nature of Offence:</span>
                    <p><?= htmlspecialchars($fine['nature_of_offence']) ?></p>
                </div>
                <div class="data-line">
                    <span>Location:</span>
                    <p><?= htmlspecialchars($fine['location']) ?></p>
                </div>
                <div class="data-line">
                    <span>Fine Status:</span>
                    <p><?= htmlspecialchars($fine['fine_status']) ?></p>
                </div>
                <div class="data-line">
                    <span>Fine Amount:</span>
                    <p><?= htmlspecialchars($fine['fine_amount']) ?></p>
                </div>

                <?php if ($fine['is_reported'] == 1): ?>
                    <div class="data-line">
                        <span>Reported Description:</span>
                        <p><?= htmlspecialchars($fine['reported_description'] ?? 'No description provided') ?></p>
                    </div>

                    <?php if (!empty($fine['evidence'])): ?>
                        <div class="data-line">
                            <span>Evidence:</span>
                            <div class="evidence-container">
                                <?php
                                $evidence_path = "../../../" . $fine['evidence'];
                                $file_extension = strtolower(pathinfo($evidence_path, PATHINFO_EXTENSION));
                                if (in_array($file_extension, ['jpg', 'jpeg', 'png', 'gif'])): ?>
                                    <img src="<?= htmlspecialchars($evidence_path) ?>"
                                        alt="Evidence Image"
                                        style="max-width: 300px; height: auto; margin-top: 10px; border: 1px solid #ddd; border-radius: 4px;"
                                        onclick="window.open(this.src, '_blank')">
                                <?php elseif ($file_extension === 'pdf'): ?>
                                    <div class="pdf-preview">
                                        <a href="<?= htmlspecialchars($evidence_path) ?>"
                                            target="_blank"
                                            class="pdf-link">
                                            View PDF Evidence
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($fine['oics_action'])): ?>
                        <!-- Display OIC's action if already taken -->
                        <div class="data-line">
                            <span>OIC's Action:</span>
                            <p><?= htmlspecialchars($fine['oics_action']) ?></p>
                        </div>

                        <div class="alert <?= $fine['is_discarded'] ? 'alert-danger' : 'alert-success' ?>">
                            <?php if ($fine['is_discarded']): ?>
                                <p>This reported Fine has been marked as unfair (discarded).</p>
                            <?php else: ?>
                                <p>This reported Fine has been marked as fair.</p>
                            <?php endif; ?>
                        </div>

                        <div class="wrapper" style="margin-top: 10px;">
                            <a href="index.php" class="btn">Back to Fines</a>
                        </div>
                    <?php else: ?>
                        <!-- OIC Comment Form if action not yet taken -->
                        <form action="oic-action-process.php" method="post" id="oicActionForm" style="margin-top: 20px;">
                            <div class="field">
                                <label for="oic_action">OIC Action (Comment):</label>
                                <textarea type="text" class="input" name="oic_action" id="oic_action"
                                    placeholder="Provide your comment here..." required></textarea>
                            </div>

                            <div class="wrapper" style="margin-top: 10px;">
                                <!-- Button to Discard Fine -->
                                <button type="submit" name="action_type" value="discard" class="deletebtn"
                                    style="margin-right: 10px;">
                                    Discard Fine (Unfair)
                                </button>

                                <!-- Button to Mark Fine as Fair -->
                                <button type="submit" name="action_type" value="fair" class="btn" style="margin-right: 10px;">
                                    Submit (Fair)
                                </button>
                            </div>

                            <!-- Hidden input for the Fine ID -->
                            <input type="hidden" name="fine_id" value="<?= htmlspecialchars($fine['id']) ?>">
                        </form>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>

<?php include_once "../../../includes/footer.php"; ?>