<?php
$pageConfig = [
    'title' => 'Police Station Vehicle',
    'styles' => ["../../dashboard.css","seize-vehicle.css"],
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

// Get OIC's police station ID
$sql = "SELECT police_station FROM officers WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $oic_id);
$stmt->execute();
$result = $stmt->get_result();
$station = $result->fetch_assoc();
if (!$station) 
  die("Station not found.");
$police_station_id = $station['police_station'];
$stmt->close();

// Fetch all seized vehicles
$sql = "SELECT s.license_plate_number, o.id AS officer_id, s.officer_name, 
               s.seizure_date_time, s.seized_location, s.is_released
        FROM seized_vehicle s
        INNER JOIN police_stations ps ON ps.id = s.police_station
        INNER JOIN officers o ON o.police_station = ps.id
        WHERE ps.id = ?
        GROUP BY s.license_plate_number, s.seizure_date_time, s.seized_location, s.is_released
        ORDER BY s.is_released ASC, s.seizure_date_time DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $police_station_id);
$stmt->execute();
$result = $stmt->get_result();

$vehicles = [];
while ($row = $result->fetch_assoc()) {
    $vehicles[] = $row;
}
$stmt->close();





$conn->close();
?>

<main>
<?php include_once "../../includes/navbar.php"; ?>

<div class="dashboard-layout">
    <?php include_once "../../includes/sidebar.php"; ?>
    <div class="content">
        <div class="container x-large no-border">
            <div class="field">
                <h1>Seized Vehicles</h1>
            </div>
        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>License Plate Number</th>
                        <th>Officer ID</th>
                        <th>Officer Name</th>
                        <th>Date and Time</th>
                        <th>Seized Location</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (!empty($vehicles)): ?>
                    <?php foreach ($vehicles as $vehicle): ?>
                    <tr>
                        <td><?= htmlspecialchars($vehicle['license_plate_number']) ?></td>
                        <td><?= htmlspecialchars($vehicle['officer_id']) ?></td>
                        <td><?= htmlspecialchars($vehicle['officer_name']) ?></td>
                        <td><?= htmlspecialchars($vehicle['seizure_date_time']) ?></td>
                        <td><?= htmlspecialchars($vehicle['seized_location']) ?></td>
                        <td>
                            <?php if (empty($vehicle['is_released'])): ?>
                                <button class="btn open-release-modal" 
                                        data-license="<?= htmlspecialchars($vehicle['license_plate_number']) ?>">
                                    Release
                                </button>
                            <?php else: ?>
                                <button class="btn released" disabled>Released</button>
                                <button class="btn open-view-modal" 
                                        data-license="<?= htmlspecialchars($vehicle['license_plate_number']) ?>">
                                    View
                                </button>

                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="6">No seized vehicles found.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</main>

<!-- Release Modal -->
<div class="modal" id="releaseModal" style="display:none;">
  <div class="modal-content">
    <span class="close-button" id="closeModal">&times;</span>
    <h3>Release Vehicle</h3>
    <form action="release-vehicle.php" method="POST">
      <input type="hidden" name="license_plate_number" id="modalLicensePlate">

      <div class="field">
        <label>Owner Name</label>
        <input type="text" name="owner_name" required>
      </div>

      <div class="field">
        <label>National ID</label>
        <input type="text" name="national_id" required>
      </div>

      <div class="field">
        <label>Date</label>
        <input type="date" name="date" required>
      </div>

      <div class="field">
        <label>Notes</label>
        <textarea name="release_notes" rows="3"></textarea>
      </div>

      <div class="field">
        <button type="submit" class="btn">Confirm Release</button>
      </div>
    </form>
  </div>
</div>

<!-- View Modal -->
<div class="modal" id="viewModal" style="display:none;">
  <div class="modal-content">
    <span class="close-button" id="closeViewModal">&times;</span>
    <h3>Vehicle Release Details</h3>
    <div class="field"><strong>License Plate:</strong> <span id="view-license"></span></div>
    <div class="field"><strong>Owner Name:</strong> <span id="view-owner"></span></div>
    <div class="field"><strong>NIC:</strong> <span id="view-nic"></span></div>
    <div class="field"><strong>Release Date:</strong> <span id="view-date"></span></div>
    <div class="field"><strong>Notes:</strong> <span id="view-notes"></span></div>
  </div>
</div>


<?php include_once "../../../includes/footer.php"; ?>

<script>
  const releaseModal = document.getElementById("releaseModal");
  const closeRelease = document.getElementById("closeModal");
  const licenseInput = document.getElementById("modalLicensePlate");

  document.querySelectorAll(".open-release-modal").forEach(btn => {
    btn.onclick = () => {
      licenseInput.value = btn.dataset.license;
      releaseModal.style.display = "block";
    };
  });

  closeRelease.onclick = () => {
    releaseModal.style.display = "none";
  };

  window.onclick = (e) => {
    if (e.target === releaseModal) releaseModal.style.display = "none";
    if (e.target === viewModal) viewModal.style.display = "none";
  };

  // View Modal Logic
  const viewModal = document.getElementById("viewModal");
  const closeView = document.getElementById("closeViewModal");

 document.querySelectorAll(".open-view-modal").forEach(btn => {
  btn.onclick = () => {
    const license = btn.dataset.license;

    fetch(`view-release-details.php?license=${encodeURIComponent(license)}`)
      .then(res => res.json())
      .then(data => {
        if (data.error) {
          alert(data.error);
        } else {
          document.getElementById("view-license").textContent = data.license_plate_number;
          document.getElementById("view-owner").textContent = data.owner_name;
          document.getElementById("view-nic").textContent = data.nic;
          document.getElementById("view-date").textContent = data.release_date;
          document.getElementById("view-notes").textContent = data.notes;
          viewModal.style.display = "block";
        }
      })
      .catch(err => alert("Failed to load release info."));
  };
});

  closeView.onclick = () => {
    viewModal.style.display = "none";
  };
</script>

