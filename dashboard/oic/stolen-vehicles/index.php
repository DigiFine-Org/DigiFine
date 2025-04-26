<?php
// Configuration and Authentication
$pageConfig = [
    'title' => 'Report Stolen Vehicle',
    'styles' => ["../../dashboard.css", "stolen-vehicle.css"],
    'scripts' => ["../../dashboard.js"],
    'authRequired' => true
];

require_once "../../../includes/header.php";
require_once "../../../db/connect.php";

// Authorization check
if ($_SESSION['user']['role'] !== 'oic') {
    die("Unauthorized user!");
}

// Form Processing
$popupMessage = '';
$popupSuccess = true;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect form data
    $formData = [
        'license_plate_number' => $_POST['license_plate_number'] ?? '',
        'absolute_owner' => $_POST['absolute_owner'] ?? '',
        'engine_no' => $_POST['engine_no'] ?? '',
        'model' => $_POST['model'] ?? '',
        'colour' => $_POST['colour'] ?? '',
        'date_of_registration' => $_POST['date_of_registration'] ?? '',
        'date_reported_stolen' => $_POST['date_reported_stolen'] ?? '',
        'location_last_seen' => $_POST['location_last_seen'] ?? '',
        'last_seen_date' => $_POST['last_seen_date'] ?? '',
        'vehicle_image' => ''
    ];

    // Handle file upload
    if (isset($_FILES['vehicle_image']) && $_FILES['vehicle_image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../../../uploads/';
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        
        $fileName = uniqid() . '_' . basename($_FILES['vehicle_image']['name']);
        $targetPath = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['vehicle_image']['tmp_name'], $targetPath)) {
            $formData['vehicle_image'] = $fileName;
        } else {
            $popupMessage = "Error uploading the vehicle image.";
            $popupSuccess = false;
        }
    }

    // Date validation
    if (strtotime($formData['last_seen_date']) > time()) {
        $popupMessage = "Error: 'Date Last Seen' cannot be in the future.";
        $popupSuccess = false;
    } else {
        // Database operations
        $checkSql = "SELECT * FROM dmt_vehicles WHERE license_plate_number = ?";
        $checkStmt = $conn->prepare($checkSql);
        $checkStmt->bind_param("s", $formData['license_plate_number']);
        $checkStmt->execute();
        $checkResult = $checkStmt->get_result();

        $checkStolenSql = "SELECT * FROM stolen_vehicles WHERE license_plate_number = ?";
        $checkStolenStmt = $conn->prepare($checkStolenSql);
        $checkStolenStmt->bind_param("s", $formData['license_plate_number']);
        $checkStolenStmt->execute();
        $checkStolenResult = $checkStolenStmt->get_result();

        if ($checkResult->num_rows === 0) {
            $popupMessage = "Error: No vehicle found with this license plate number in the registered vehicles database.";
            $popupSuccess = false;
        } elseif ($checkStolenResult->num_rows > 0) {
            $popupMessage = "Error: This vehicle has already been reported as stolen.";
            $popupSuccess = false;
        } else {
            // Insert stolen vehicle record
            $sql = "INSERT INTO stolen_vehicles 
                    (license_plate_number, absolute_owner, engine_no, model, colour, date_of_registration, 
                     date_reported_stolen, location_last_seen, last_seen_date, vehicle_image) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $stmt = $conn->prepare($sql);
            if ($stmt) {
                $stmt->bind_param(
                    "ssssssssss",
                    $formData['license_plate_number'],
                    $formData['absolute_owner'],
                    $formData['engine_no'],
                    $formData['model'],
                    $formData['colour'],
                    $formData['date_of_registration'],
                    $formData['date_reported_stolen'],
                    $formData['location_last_seen'],
                    $formData['last_seen_date'],
                    $formData['vehicle_image']
                );

                if ($stmt->execute()) {
                    // Update vehicle status
                    $updateSql = "UPDATE dmt_vehicles SET is_stolen = 1 WHERE license_plate_number = ?";
                    $updateStmt = $conn->prepare($updateSql);
                    if ($updateStmt) {
                        $updateStmt->bind_param("s", $formData['license_plate_number']);
                        if ($updateStmt->execute()) {
                            $popupMessage = "Stolen vehicle reported successfully and status updated!";
                            require_once "./send-notification-admin.php";
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
                    $popupMessage = "Error reporting stolen vehicle: " . $stmt->error;
                    $popupSuccess = false;
                }
                $stmt->close();
            } else {
                $popupMessage = "Error preparing statement: " . $conn->error;
                $popupSuccess = false;
            }
        }
        
        $checkStmt->close();
        $checkStolenStmt->close();
    }
}
?>

<!-- HTML Structure -->
<main>
    <?php include_once "../../includes/navbar.php" ?>
    <div class="dashboard-layout">
        <?php include_once "../../includes/sidebar.php" ?>
        <div class="content">
            <div class="container1">
                <button onclick="history.back()" class="back-btn" style="position: absolute; top: 7px; right: 8px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M15 8a.5.5 0 0 1-.5.5H3.707l3.147 3.146a.5.5 0 0 1-.708.708l-4-4a.5.5 0 0 1 0-.708l4-4a.5.5 0 1 1 .708.708L3.707 7.5H14.5a.5.5 0 0 1 .5.5z" />
                    </svg>
                </button>
                <h1>Report Stolen Vehicle</h1>
                
                <!-- Form Section -->
                <form action="" method="post" enctype="multipart/form-data" class="form-grid" onsubmit="return validateDates()">
                    <p class="form-header"><b>Vehicle Details</b></p>

                    <?php
                    $fieldGroups = [
                        // Group 1
                        [
                            'license_plate_number' => 'License Plate Number',
                            'absolute_owner' => 'Absolute Owner'
                        ],
                        // Group 2
                        [
                            'engine_no' => 'Engine Number',
                            'location_last_seen' => 'Location Last Seen',
                        ],
                        // Group 3
                        [
                            'model' => 'Model',
                            'colour' => 'Colour'
                        ],
                        // Full-width fields
                        [
                            'date_of_registration' => 'Date of Registration',
                            'date_reported_stolen' => 'Date Reported Stolen',
                            'last_seen_date' => 'Date Last Seen',
                            'vehicle_image' => 'Vehicle Image'
                        ]
                    ];

                    // Render form fields with validation
                    foreach (array_slice($fieldGroups, 0, 3) as $group) {
                        foreach ($group as $name => $label) {
                            echo '<div class="form-field">';
                            echo "<label for='$name'>$label:<span style='color: red;'> *</span></label>";
                            
                            if ($name === 'absolute_owner' || $name === 'colour') {
                                echo "<input type='text' name='$name' id='$name' class='input' required 
                                      pattern='[A-Za-z\\s]+'
                                      title='Should contain only letters and spaces'>";
                            }
                            elseif ($name === 'location_last_seen') {
                                echo "<input type='text' name='$name' id='$name' class='input' required 
                                      pattern='^(?!\\d+$).+'
                                      title='Cannot contain only numbers'>";
                            }
                            else {
                                echo "<input type='text' name='$name' id='$name' class='input' required>";
                            }
                            
                            echo '</div>';
                        }
                    }

                    // Render full-width fields
                    foreach ($fieldGroups[3] as $name => $label) {
                        $type = strpos($name, 'date') !== false ? 'date' : 'text';
                        echo '<div class="form-field form-field-full">';
                        echo "<label for='$name'>$label:<span style='color: red;'> *</span></label>";
                        
                        if ($name === 'vehicle_image') {
                            echo "<input type='file' name='vehicle_image' id='vehicle_image' accept='image/*' required>";
                            echo '<img id="vehiclePreview" src="#" alt="Vehicle Preview" style="display: none; max-width: 300px; margin-top: 10px; border: 1px solid #ddd; border-radius: 4px;">';
                        } else {
                            echo "<input type='$type' name='$name' id='$name' required>";
                        }
                        
                        echo '</div>';
                    }
                    ?>

                    <small id="dateError" class="error-message" style="display: none;"></small>
                    <button type="submit" class="submit-btn">Submit Report</button>
                </form>
            </div>
        </div>
    </div>
</main>

<!-- Popup Modal -->
<div id="popup-new" class="popup-new">
    <div class="popup-contentNew">
        <span id="popup-closeNew" class="popup-closeNew">&times;</span>
        <div id="popupIconNew" class="popup-icon"></div>
        <h2 id="popupTitleNew"></h2>
        <p id="popupTextNew"></p>
        <button id="popupButtonNew" class="popup-button">OK</button>
    </div>
</div>

<!-- JavaScript Section -->
<script>
    // Date validation and restrictions
    const lastSeenDateInput = document.getElementById('last_seen_date');
    const regDateInput = document.getElementById('date_of_registration');
    const reportDateInput = document.getElementById('date_reported_stolen');
    const today = new Date().toISOString().split('T')[0];

    // Set max dates to today
    [lastSeenDateInput, regDateInput, reportDateInput].forEach(input => {
        input.max = today;
    });

    // Comprehensive date validation
    function validateDates() {
        const dateError = document.getElementById('dateError');
        dateError.style.display = 'none';
        
        const regDate = new Date(regDateInput.value);
        const reportDate = new Date(reportDateInput.value);
        const lastSeenDate = new Date(lastSeenDateInput.value);
        const currentDate = new Date();
        
        let errorMessage = '';
        
        // Check individual dates aren't in the future
        if (regDate > currentDate) {
            errorMessage = "Registration date cannot be in the future";
        } else if (reportDate > currentDate) {
            errorMessage = "Report date cannot be in the future";
        } else if (lastSeenDate > currentDate) {
            errorMessage = "Last seen date cannot be in the future";
        }
        // Check date relationships
        else if (reportDate < lastSeenDate) {
            errorMessage = "Report date must be on or after last seen date";
        }
        else if (regDate > reportDate) {
            errorMessage = "Registration date must be before report date";
        }
        else if (regDate > lastSeenDate) {
            errorMessage = "Registration date must be before last seen date";
        }
        
        if (errorMessage) {
            dateError.textContent = errorMessage;
            dateError.style.display = 'block';
            return false;
        }
        return true;
    }

    // Attach event listeners
    [lastSeenDateInput, regDateInput, reportDateInput].forEach(input => {
        input.addEventListener('input', validateDates);
    });

    // Image preview functionality
    document.getElementById('vehicle_image').addEventListener('change', function(event) {
        const preview = document.getElementById('vehiclePreview');
        const file = event.target.files[0];

        if (file && file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        } else {
            preview.src = '';
            preview.style.display = 'none';
        }
    });

    // Popup management
    function showPopup(message, isSuccess = true) {
        const popup = document.getElementById('popup-new');
        const icon = document.getElementById('popupIconNew');
        const titleEl = document.getElementById('popupTitleNew');
        const textEl = document.getElementById('popupTextNew');
        const button = document.getElementById('popupButtonNew');

        if (isSuccess) {
            icon.style.color = '#28a745';
            titleEl.textContent = 'Success!';
            button.style.backgroundColor = 'var(--color-dark-blue)';
        } else {
            icon.style.color = '#dc3545';
            titleEl.textContent = 'Error!';
            button.style.backgroundColor = 'var(--color-dark-blue)';
        }

        textEl.textContent = message;
        popup.style.display = 'flex';
    }

    function closePopup() {
        document.getElementById('popup-new').style.display = 'none';
    }

    // Event listeners for popup
    document.getElementById('popup-closeNew').addEventListener('click', closePopup);
    document.getElementById('popupButtonNew').addEventListener('click', closePopup);
    window.addEventListener('click', function(event) {
        if (event.target === document.getElementById('popup-new')) {
            closePopup();
        }
    });

    // Show popup if there's a message from PHP
    <?php if (!empty($popupMessage)): ?>
        document.addEventListener('DOMContentLoaded', function() {
            showPopup("<?= htmlspecialchars($popupMessage, ENT_QUOTES) ?>", <?= $popupSuccess ? 'true' : 'false' ?>);
        });
    <?php endif; ?>
</script>

<?php include_once "../../../includes/footer.php" ?>