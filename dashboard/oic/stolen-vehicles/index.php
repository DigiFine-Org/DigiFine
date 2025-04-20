<?php
$pageConfig = [
    'title' => 'Report Stolen Vehicle',
    'styles' => ["../../dashboard.css"],
    'scripts' => ["../../dashboard.js"],
    'authRequired' => true
];

require_once "../../../includes/header.php";
require_once "../../../db/connect.php";

if ($_SESSION['user']['role'] !== 'oic') {
    die("Unauthorized user!");
}

// Handle form submission
$popupMessage = '';
$popupSuccess = true;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $license_plate_number = $_POST['license_plate_number'] ?? '';
    $absoluteOwner = $_POST['absolute_owner'] ?? '';
    $engineNo = $_POST['engine_no'] ?? '';
    $make = $_POST['make'] ?? '';
    $model = $_POST['model'] ?? '';
    $colour = $_POST['colour'] ?? '';
    $dateOfRegistration = $_POST['date_of_registration'] ?? '';
    $status = $_POST['status'] ?? '';
    $dateReportedStolen = $_POST['date_reported_stolen'] ?? '';
    $locationLastSeen = $_POST['location_last_seen'] ?? '';
    $lastSeenDate = $_POST['last_seen_date'] ?? '';
    $vehicleImage = '';
    
    if(isset($_FILES['vehicle_image']) && $_FILES['vehicle_image']['error'] === UPLOAD_ERR_OK){
        $uploadDir = '../../../uploads/';
        if(!file_exists($uploadDir)){
            mkdir($uploadDir,0777,true);
        }
        $fileName=uniqid() . '_' . basename($_FILES['vehicle_image']['name']);
        $targetPath=$uploadDir.$fileName;

        if (move_uploaded_file($_FILES['vehicle_image']['tmp_name'], $targetPath)) {
            $vehicleImage = $fileName;
        } else {
            $popupMessage = "Error uploading the vehicle image.";
            $popupSuccess = false;
        }
    }


    if (strtotime($lastSeenDate) > time()) {
        $popupMessage = "Error: 'Date Last Seen' cannot be in the future.";
        $popupSuccess = false;
    } else {
        $checkSql = "SELECT * FROM dmt_vehicles WHERE license_plate_number = ?";
        $checkStmt = $conn->prepare($checkSql);
        $checkStmt->bind_param("s", $license_plate_number);
        $checkStmt->execute();
        $checkResult = $checkStmt->get_result();

        if ($checkResult->num_rows === 0) {
            $popupMessage = "Error: The license plate number does not exist in the registered vehicles database.";
            $popupSuccess = false;
        } else {
            $checkStmt->close();

            $sql = "INSERT INTO stolen_vehicles 
                    (license_plate_number, absolute_owner, engine_no, make, model, colour, date_of_registration, 
                    status, date_reported_stolen, location_last_seen, last_seen_date,vehicle_image) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?)";

            $stmt = $conn->prepare($sql);
            if ($stmt) {
                $stmt->bind_param("ssssssssssss", $license_plate_number, $absoluteOwner, $engineNo, $make, $model, $colour,
                    $dateOfRegistration, $status, $dateReportedStolen, $locationLastSeen, $lastSeenDate,$vehicleImage);

                if ($stmt->execute()) {
                    $updateSql = "UPDATE dmt_vehicles SET is_stolen = 1 WHERE license_plate_number = ?";
                    $updateStmt = $conn->prepare($updateSql);
                    if ($updateStmt) {
                        $updateStmt->bind_param("s", $license_plate_number);
                        if ($updateStmt->execute()) {
                            $popupMessage = "Stolen vehicle reported successfully and status updated!";
                        } else {
                            $popupMessage = "Error updating is_stolen column: " . $updateStmt->error;
                            $popupSuccess = false;
                        }
                        $updateStmt->close();
                    } else {
                        $popupMessage = "Error preparing update statement: " . $conn->error;
                        $popupSuccess = false;
                    }
                } else {
                    $popupMessage = "Error executing query: " . $stmt->error;
                    $popupSuccess = false;
                }
                $stmt->close();
            } else {
                $popupMessage = "Error preparing statement: " . $conn->error;
                $popupSuccess = false;
            }
        }
    }
}
?>

<main>
    <?php include_once "../../includes/navbar.php" ?>
    <div class="dashboard-layout">
        <?php include_once "../../includes/sidebar.php" ?>
        <div class="content">
    <div class="container1">
        <h1>Report Stolen Vehicle</h1>
        <form action="" method="post" enctype="multipart/form-data" class="form-grid">
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
                    'make' => 'Make'
                ],
                // Group 3
                [
                    'model' => 'Model',
                    'colour' => 'Colour'
                ],
                // Full-width fields
                [
                    'date_of_registration' => 'Date of Registration',
                    'status' => 'Status',
                    'date_reported_stolen' => 'Date Reported Stolen',
                    'location_last_seen' => 'Location Last Seen',
                    'last_seen_date' => 'Date Last Seen',
                    'vehicle_image' => 'Vehicle Image'
                ]
            ];
            
            // Render two-column fields
            foreach(array_slice($fieldGroups, 0, 3) as $group) {
                foreach($group as $name => $label) {
                    echo '<div class="form-field">';
                    echo "<label for='$name'>$label:</label>";
                    echo "<input type='text' name='$name' id='$name' required>";
                    echo '</div>';
                }
            }
            
            // Render full-width fields (dates)
            foreach($fieldGroups[3] as $name => $label) {
                $type = strpos($name, 'date') !== false ? 'date' : 'text';
                echo '<div class="form-field form-field-full">';
                echo "<label for='$name'>$label:</label>";
                if ($name === 'vehicle_image') {
                    echo "<input type='file' name='vehicle_image' id='vehicle_image' accept='image/*' required>";
                    echo '<img id="vehiclePreview" src="#" alt="Vehicle Preview" style="display: none; max-width: 300px; margin-top: 10px; border: 1px solid #ddd; border-radius: 4px;">';

                } else {
                    echo "<input type='$type' name='$name' id='$name' required>";
                }
                
                echo '</div>';
            }
            ?>
            
            <small id="dateError" class="error-message" style="display: none;">
                Date cannot be in the future!
            </small>
            
            <button type="submit" class="submit-btn">Submit Report</button>
        </form>
    </div>
</div>
    </div>
</main>

<div id="popup-new" class="popup-new">
    <div class="popup-contentNew">
        <span id="popup-closeNew" class="popup-closeNew">&times;</span>
        <div id="popupIconNew" class="popup-icon"></div>
        <h2 id="popupTitleNew"></h2>
        <p id="popupTextNew"></p>
        <button id="popupButtonNew" class="popup-button">OK</button>
    </div>
</div>

<script>
    // Date validation
    const lastSeenDateInput = document.getElementById('last_seen_date');
    const regDateInput = document.getElementById('date_of_registration');
    const reportDateInput=document.getElementById('date_reported_stolen');
    const today = new Date().toISOString().split('T')[0];

    lastSeenDateInput.max = today;
    regDateInput.max = today;
    reportDateInput.max = today;

    lastSeenDateInput.addEventListener('input', () => {
        const dateError = document.getElementById('dateError');
        if (new Date(lastSeenDateInput.value) > new Date()) {
            dateError.style.display = 'block';
        } else {
            dateError.style.display = 'none';
        }
    });

    regDateInput.addEventListener('input' , () =>{
        const dateError  = document.getElementById('dateError');
        if( new Date (regDateInput.value) > new Date()){
            dateError.style.display='block';
        }else{
            dateError.style.display='none';
        }
    });

    reportDateInput.addEventListener('input' , () =>{
        const dateError  = document.getElementById('dateError');
        if( new Date (reportDateInput.value) > new Date()){
            dateError.style.display='block';
        }else{
            dateError.style.display='none';
        }
    });

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


    // Enhanced Popup Functions
    function showPopup(message, isSuccess = true) {
        const popup = document.getElementById('popup-new');
        const icon = document.getElementById('popupIconNew');
        const titleEl = document.getElementById('popupTitleNew');
        const textEl = document.getElementById('popupTextNew');
        const button = document.getElementById('popupButtonNew');
        
        // Set content based on success/error
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

    // Event listeners
    document.getElementById('popup-closeNew').addEventListener('click', closePopup);
    document.getElementById('popupButtonNew').addEventListener('click', closePopup);
    window.addEventListener('click', function(event) {
        if (event.target === document.getElementById('popup-new')) {
            closePopup();
        }
    });

    <?php if (!empty($popupMessage)) : ?>
        document.addEventListener('DOMContentLoaded', function() {
            showPopup("<?= htmlspecialchars($popupMessage, ENT_QUOTES) ?>", <?= $popupSuccess ? 'true' : 'false' ?>);
        });
    <?php endif; ?>
</script>
<style>
    .container1 {
  border: 1px solid var(--color-gray);
  border-radius: 8px;
  margin: 0 auto;
  width: 100%;
  max-width: 900px; /* Wider to accommodate two columns */
  padding: 2rem;
  background-color: var(--color-white);
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
}

/* Form Grid Layout */
.form-grid {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 1.5rem 2rem; /* Vertical | Horizontal */
  align-items: start;
}

/* Form Header */
.form-header {
  grid-column: 1 / -1;
  margin-bottom: 1rem;
  padding-bottom: 0.75rem;
  border-bottom: 1px solid var(--color-light-gray);
}

/* Form Field Styling */
.form-field {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.form-field label {
  font-weight: 500;
  color: var(--color-dark-gray);
  font-size: 0.9rem;
}

.form-field input {
  width: 100%;
  padding: 0.75rem 1rem;
  border: 1px solid var(--color-light-gray);
  border-radius: 6px;
  font-size: 1rem;
  transition: all 0.2s ease;
  border: 1px solid #000;

}

.form-field input:focus {
  border-color: #000;
  box-shadow: 0 0 0 2px rgba(0, 0, 0, 0.1);
  outline: none;
}

/* Full-width fields (for dates and button) */
.form-field-full {
  grid-column: 1 / -1;
}

/* Submit Button */
.submit-btn {
  grid-column: 1 / -1;
  background-color: var(--color-dark-blue);
  color: white;
  border: none;
  padding: 0.75rem 1.5rem;
  border-radius: 6px;
  font-size: 1rem;
  font-weight: 500;
  cursor: pointer;
  transition: background-color 0.2s ease;
  width: 200px;
  margin: 1rem auto 0;
}



/* Error Message */
.error-message {
  grid-column: 1 / -1;
  color: #dc3545;
  font-size: 0.85rem;
  text-align: center;
  margin-top: -0.5rem;
}

/* Responsive Adjustments */
@media (max-width: 768px) {
  .form-grid {
      grid-template-columns: 1fr;
      gap: 1.25rem;
  }
  
  .container1 {
      padding: 1.5rem;
      max-width: 100%;
  }
}

.popup-new {
  display: none;
  position: fixed;
  z-index: 1000;
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  align-items: center;
  justify-content: center;
  animation: fadeIn 0.3s;
}

.popup-contentNew {
  background-color: #fff;
  padding: 30px;
  border-radius: 12px;
  width: 400px;
  max-width: 90%;
  text-align: center;
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
  position: relative;
}

.popup-closeNew {
  position: absolute;
  top: 15px;
  right: 15px;
  font-size: 24px;
  cursor: pointer;
  color: #aaa;
  transition: color 0.3s;
}

.popup-closeNew:hover {
  color: #333;
}

.popup-icon {
  font-size: 50px;
  margin-bottom: 15px;
  font-weight: bold;
}

#popupTitleNew {
  font-size: 22px;
  margin-bottom: 10px;
  color: #333;
}

#popupTextNew {
  font-size: 16px;
  color: #555;
  margin-bottom: 25px;
  line-height: 1.5;
}

.popup-button {
  background-color:  var(--color-dark-blue);
  color: white;
  border: none;
  padding: 10px 20px;
  border-radius: 5px;
  cursor: pointer;
  font-size: 16px;
  transition: background-color 0.3s;
}

.popup-button:hover {
  opacity: 0.9;
}

@keyframes fadeIn {
  from { opacity: 0; }
  to { opacity: 1; }
}

/* Form Styles (existing) */
.field {
  margin-bottom: 15px;
}

.field label {
  display: block;
  margin-bottom: 5px;
  font-weight: 500;
}

.field input {
  width: 100%;
  padding: 8px 12px;
  border: 1px solid #ddd;
  border-radius: 4px;
  font-size: 16px;
}

.btn {
  background-color: var(--color-dark-blue);
  color: white;
  border: none;
  padding: 10px 20px;
  border-radius: 4px;
  cursor: pointer;
  font-size: 16px;
  margin-top: 10px;
}

.btn:hover {
  background-color: var(--color-dark-blue);
}

.submit-btn {
  background-color: var(--color-dark-blue);
  color: white;
  border: none;
  padding: 10px 20px;
  border-radius: 4px;
  cursor: pointer;
  font-size: 14px;
  margin-top: 10px;
  grid-column: span 2;
}

.submit-btn:hover {
  opacity: 0.9;
}
</style>


<?php include_once "../../../includes/footer.php" ?>