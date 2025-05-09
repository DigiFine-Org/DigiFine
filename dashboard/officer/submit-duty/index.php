<?php

$pageConfig = [
  'title' => 'Submit Duty',
  'styles' => ["../../dashboard.css", "./my-duties.css"],
  'scripts' => ["../../dashboard.js"],
  'authRequired' => true
];

session_start();
include_once "../../../includes/header.php";
require_once "../../../db/connect.php";

if ($_SESSION['user']['role'] !== 'officer') {
  die("Unauthorized user!");
}

$officer_id = $_SESSION['user']['id'];

$duties = [];
$stmt = $conn->prepare("
    SELECT ad.id, ad.duty, ad.notes, ad.duty_date, ad.duty_time_start, ad.duty_time_end
    FROM assigned_duties AS ad
    WHERE ad.police_id = ? AND ad.submitted = 0
");

if (!$stmt) {
  die("Error preparing query!" . $conn->error);
}

$stmt->bind_param("i", $officer_id);

if (!$stmt->execute()) {
  die("Error executing query" . $stmt->error);
}

$result = $stmt->get_result();
$duties = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<main>
  <?php include_once "../../includes/navbar.php"; ?>
  <div class="dashboard-layout">
    <?php include_once "../../includes/sidebar.php"; ?>
    <div class="content">
      <button onclick="history.back()" class="back-btn" style="position: absolute; top: 7px; right: 8px;">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
          <path fill-rule="evenodd"
            d="M15 8a.5.5 0 0 1-.5.5H3.707l3.147 3.146a.5.5 0 0 1-.708.708l-4-4a.5.5 0 0 1 0-.708l4-4a.5.5 0 1 1 .708.708L3.707 7.5H14.5a.5.5 0 0 1 .5.5z" />
        </svg>
      </button>
      <div class="home-grid">
        <?php foreach ($duties as $duty): ?>
          <div class="ticket">
            <span class="id">Duty ID: <?= htmlspecialchars($duty['id']) ?></span>
            <div class="data-line">
              <div class="label">Duty:</div>
              <p><?= htmlspecialchars($duty['duty']) ?></p>
            </div>
            <div class="data-line">
              <div class="label">Duty Date:</div>
              <p><?= htmlspecialchars($duty['duty_date']) ?></p>
            </div>
            <div class="data-line">
              <div class="label">Duty starts at:</div>
              <p><?= htmlspecialchars($duty['duty_time_start']) ?></p>
            </div>
            <div class="data-line">
              <div class="label">Duty ends at:</div>
              <p><?= htmlspecialchars($duty['duty_time_end']) ?></p>
            </div>
            <div class="data-line">
              <div class="label">Notes:</div>
              <p><?= htmlspecialchars($duty['notes'] ?? "N/A") ?></p>
            </div>
            <div class="bottom-bar">
              <div class="actions">
                <a href="submit-duty.php?id=<?= htmlspecialchars($duty['id']) ?>" class="btn">Submit Duty</a>
              </div>
            </div>
          </div>
        <?php endforeach; ?>

        <?php if (empty($duties)): ?>
          <div class="no-duty-container">
            <img src="../../../assets/note.png" alt="No duties illustration" class="no-duty-image">
            <h2>No Duties Assigned</h2>
            <p>You haven’t received any duties yet. Please check back later.</p>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</main>

<?php include_once "../../../includes/footer.php"; ?>