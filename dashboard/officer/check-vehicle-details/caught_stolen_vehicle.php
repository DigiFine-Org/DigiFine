<?php
$pageConfig = [
    'title' => 'Check Vehicle Details',
    'styles' => ["../../dashboard.css", "stolen-form.css"],
    'scripts' => ["../../dashboard.js"],
    'authRequired' => true
];

session_start();
require_once "../../../db/connect.php";
include_once "../../../includes/header.php";


if ($_SESSION['user']['role'] !== 'officer') {
    die("Unauthorized user!");
}


$license_plate_number = $_GET['license_plate_number'] ?? null;
$officerID = $_SESSION['user']['id'] ?? null;

if (!$license_plate_number) {
    die("No license plate number provided!");
}


$officer = [];
$sqlOfficer = "SELECT id, CONCAT(fname, ' ', lname) AS full_name, police_station FROM officers WHERE id=?";
$stmt = $conn->prepare($sqlOfficer);
$stmt->bind_param("i", $officerID);
$stmt->execute();
$result = $stmt->get_result();
$officer = $result->fetch_assoc();
$stmt->close();


$vehicle = [];
$sql = "SELECT sv.*, CONCAT(dv.vehicle_owner_fname, ' ', dv.vehicle_owner_lname) AS full_name 
        FROM stolen_vehicles sv 
        JOIN dmt_vehicles dv ON dv.license_plate_number = sv.license_plate_number 
        WHERE sv.license_plate_number=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $license_plate_number);
$stmt->execute();
$result = $stmt->get_result();
$vehicle = $result->fetch_assoc();
$stmt->close();


$error = "";
$popupMessage = "";
$popupSuccess = true;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $inputs = [
        'license_plate_number' => $_POST['license_plate_number'] ?? '',
        'seizure_date_time' => $_POST['seizure_date_time'] ?? '',
        'seized_location' => trim($_POST['seized_location'] ?? ''),
        'officer_id' => $_POST['officer_id'] ?? '',
        'officer_name' => trim($_POST['officer_name'] ?? ''),
        'police_station' => $_POST['police_station'] ?? '',
        'driver_NIC' => trim($_POST['driver_NIC'] ?? ''),
        'owner_name' => trim($_POST['owner_name'] ?? '')
    ];


    $errors = [];
    foreach ($inputs as $key => $value) {
        if (empty($value)) {
            $errors[$key] = "This field is required";
        }
    }


    if (!empty($inputs['driver_NIC']) && !preg_match('/^(?:\d{12}|\d{9}[vVxX])$/', $inputs['driver_NIC'])) {
        $errors['driver_NIC'] = "Invalid NIC format. Must be 12 digits or 9 digits followed by V/X";
    }

    if (empty($errors)) {

        $sql = "INSERT INTO seized_vehicle 
                (license_plate_number, seizure_date_time, seized_location, officer_id, 
                 officer_name, police_station, driver_NIC, owner_name) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param(
                "ssssssss",
                $inputs['license_plate_number'],
                $inputs['seizure_date_time'],
                $inputs['seized_location'],
                $inputs['officer_id'],
                $inputs['officer_name'],
                $inputs['police_station'],
                $inputs['driver_NIC'],
                $inputs['owner_name']
            );

            if ($stmt->execute()) {

                $updateSql = "UPDATE dmt_vehicles SET is_stolen = 0 WHERE license_plate_number = ?";
                $updateStmt = $conn->prepare($updateSql);

                if ($updateStmt) {
                    $updateStmt->bind_param("s", $inputs['license_plate_number']);
                    if ($updateStmt->execute()) {

                        $deleteSql = "DELETE FROM stolen_vehicles WHERE license_plate_number = ?";
                        $deleteStmt = $conn->prepare($deleteSql);
                        if ($deleteStmt) {
                            $deleteStmt->bind_param("s", $inputs['license_plate_number']);
                            $deleteStmt->execute();
                            $deleteStmt->close();
                        }
                        $popupMessage = "Vehicle seized successfully!";
                        $popupSuccess = true;
                    } else {
                        $popupMessage = "Error updating vehicle status: " . $updateStmt->error;
                        $popupSuccess = false;
                    }
                    $updateStmt->close();
                } else {
                    $popupMessage = "Error preparing update statement: " . $conn->error;
                    $popupSuccess = false;
                }
            } else {
                if ($conn->errno === 1062) { 
                    $popupMessage = "This vehicle has already been seized";
                } else {
                    $popupMessage = "Error seizing vehicle: " . $stmt->error;
                }
                $popupSuccess = false;
            }
            $stmt->close();
        } else {
            $popupMessage = "Error preparing insert statement: " . $conn->error;
            $popupSuccess = false;
        }
    } else {
        $popupMessage = "Please correct the following errors:<br>- " . implode("<br>- ", $errors);
        $popupSuccess = false;
    }
}
?>


<main>
    <?php include_once "../../includes/navbar.php"; ?>

    <div class="dashboard-layout">
        <?php include_once "../../includes/sidebar.php"; ?>

        <div class="content">
            <div class="container">


                <h1>Seizing the Vehicle <?= htmlspecialchars($vehicle['license_plate_number']) ?></h1>


                <form method="POST" action="" class="seizure-form">
    
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">License Plate Number</label>
                            <input type="text" class="form-control readonly" name="license_plate_number"
                                value="<?= htmlspecialchars($vehicle['license_plate_number']) ?>" readonly>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Owner Name</label>
                            <input type="text" class="form-control readonly" name="owner_name"
                                value="<?= htmlspecialchars($vehicle['full_name']) ?>" readonly>
                        </div>
                    </div>


                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Seizure Date & Time <span class="required">*</span></label>
                            <input type="datetime-local" class="form-control" id="seizure-date-time"
                                name="seizure_date_time" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Seizure Location <span class="required">*</span></label>
                            <input type="text" class="form-control" name="seized_location"
                                value="<?= htmlspecialchars($_POST['seized_location'] ?? '') ?>"
                                placeholder="Street name, City" required>
                        </div>
                    </div>


                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Officer ID</label>
                            <input type="text" class="form-control readonly" name="officer_id"
                                value="<?= htmlspecialchars($officer['id']) ?>" readonly>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Officer Name</label>
                            <input type="text" class="form-control readonly" name="officer_name"
                                value="<?= htmlspecialchars($officer['full_name']) ?>" readonly>
                        </div>
                    </div>


                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Police Station <span class="required">*</span></label>
                            <select id="police_station" name="police_station" class="form-control select2-dropdown"
                                required>
                                <option value="">Select Police Station</option>
                                <?php
                                $stationQuery = "SELECT id, name FROM police_stations ORDER BY name";
                                $stationStmt = $conn->prepare($stationQuery);
                                $stationStmt->execute();
                                $stationResult = $stationStmt->get_result();

                                while ($station = $stationResult->fetch_assoc()): ?>
                                    <option value="<?= htmlspecialchars($station['id']) ?>"
                                        <?= (isset($officer['police_station']) && $officer['police_station'] == $station['id']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($station['name']) ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                    </div>


                    <div class="form-group">
                        <label class="form-label">Driver NIC <span class="required">*</span></label>
                        <input type="text" class="form-control" name="driver_NIC"
                            value="<?= htmlspecialchars($_POST['driver_NIC'] ?? '') ?>"
                            placeholder="NIC Number (12 digits or 11 digits with V/X)"
                            pattern="^(?:\d{12}|\d{9}[vVxX])$" title="Must be 12 digits or 9 digits followed by V/X"
                            required>
                        <?php if (isset($errors['driver_NIC'])): ?>
                            <small class="error-message"><?= $errors['driver_NIC'] ?></small>
                        <?php endif; ?>

                    </div>



                    <form action="process-seizure.php" method="post" class="seizure-form">    

                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">Seize Vehicle</button>
                            <a href="index.php" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </form>
            </div>
        </div>
    </div>
</main>


<div id="popupNew" class="popupNew">
    <div class="popup-contentNew">
        <span id="popup-closeNew" class="popup-closeNew">&times;</span>
        <div id="popupIconNew" class="popup-icon"></div>
        <h2 id="popupTitleNew"></h2>
        <p id="popupTextNew"></p>
        <button id="popupButtonNew" class="popup-button">OK</button>
    </div>
</div>


<script>

    document.getElementById('seizure-date-time').value = new Date().toISOString().slice(0, 16);


    function showPopup(message, isSuccess = true) {
        const popup = document.getElementById('popupNew');
        const titleEl = document.getElementById('popupTitleNew');
        const textEl = document.getElementById('popupTextNew');
        const button = document.getElementById('popupButtonNew');

        titleEl.textContent = isSuccess ? 'Success!' : 'Error!';
        button.style.backgroundColor = '#003366';
        textEl.innerHTML = message;
        popup.style.display = 'flex';

        button.onclick = function () {
            closePopup();
            if (isSuccess) {
                window.location.href = 'index.php';
            }
        };
    }

    function closePopup() {
        document.getElementById('popupNew').style.display = 'none';
    }


    document.getElementById('popup-closeNew').addEventListener('click', closePopup);
    window.addEventListener('click', function (event) {
        if (event.target === document.getElementById('popupNew')) {
            closePopup();
        }
    });


    $(document).ready(function () {
        $('.select2-dropdown').select2({
            placeholder: "Type to search police stations...",
            allowClear: true,
            width: '100%',
            minimumInputLength: 1
        });


        <?php if (isset($officer['police_station'])): ?>
            $('#police_station').val('<?= $officer['police_station'] ?>').trigger('change');
        <?php endif; ?>
    });


    <?php if (!empty($popupMessage)): ?>
        document.addEventListener('DOMContentLoaded', function () {
            showPopup("<?= addslashes($popupMessage) ?>", <?= $popupSuccess ? 'true' : 'false' ?>);
        });
    <?php endif; ?>
</script>

<?php include_once "../../../includes/footer.php"; ?>