<?php
$pageConfig = [
    'title' => 'Assign Duties',
    'styles' => ["../../dashboard.css", "./search-officer.css"],
    'scripts' => ["../../dashboard.js"],
    'authRequired' => true
];

include_once "../../../includes/header.php";
include_once "../../../db/connect.php";

if ($_SESSION['user']['role'] !== 'oic') {
    die("Unauthorized user!");
}

$result = "";
if (isset($_GET)) {
    $result = $_GET['query'] ?? "";
}


$oicId = $_SESSION['user']['id'];
$stationQuery = "SELECT police_station FROM officers WHERE id = ? AND is_oic = 1";
$stationStmt = $conn->prepare($stationQuery);
$stationStmt->bind_param("i", $oicId);
$stationStmt->execute();
$stationResult = $stationStmt->get_result();
$stationData = $stationResult->fetch_assoc();
$stationId = $stationData['police_station'];


$officersQuery = "SELECT id, fname, lname FROM officers WHERE police_station = ? AND is_oic = 0 ORDER BY lname, fname";
$officersStmt = $conn->prepare($officersQuery);
$officersStmt->bind_param("i", $stationId);
$officersStmt->execute();
$officersResult = $officersStmt->get_result();
?>

<main>
    <?php include_once "../../includes/navbar.php"; ?>
    <div class="dashboard-layout">
        <?php include_once "../../includes/sidebar.php"; ?>
        <div class="content">
            <div class="container">
                <button onclick="history.back()" class="back-btn" style="position: absolute; top: 7px; right: 8px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M15 8a.5.5 0 0 1-.5.5H3.707l3.147 3.146a.5.5 0 0 1-.708.708l-4-4a.5.5 0 0 1 0-.708l4-4a.5.5 0 1 1 .708.708L3.707 7.5H14.5a.5.5 0 0 1 .5.5z" />
                    </svg>
                </button>
                <h1>Assign Duty</h1>
                
                
                <div class="search-officer-container">
                    <div class="search-input-container">
                        <input type="text" id="officerSearch" class="search-input" placeholder="Search officer by name or ID...">
                        <div class="search-results" id="searchResults"></div>
                    </div>
                    
                    <div id="existingDutiesContainer">
                        <h3 id="existingDutiesTitle" style="display: none;">Existing Duties</h3>
                        <div id="conflictWarning" class="warning-message" style="display: none;">
                            <strong>Warning:</strong> There are conflicting duties for the selected time period. Please choose a different time.
                        </div>
                        <table class="existing-duties" id="existingDutiesTable">
                            <thead>
                                <tr>
                                    <th>Duty</th>
                                    <th>Date</th>
                                    <th>Start Time</th>
                                    <th>End Time</th>
                                    <th>Notes</th>
                                </tr>
                            </thead>
                            <tbody id="existingDutiesBody"></tbody>
                        </table>
                        <div class="no-duties" id="noDutiesMessage">No existing duties found for this officer.</div>
                    </div>
                </div>
                
                <form action="assign-duty-handler.php" method="POST" id="assignDutyForm">
                    <div class="field">
                        <label for="policeId">Select Officer:</label>
                        <select name="policeId" class="input" id="policeIdSelect" required>
                            <option value="">Select Officer</option>
                            <?php while ($officer = $officersResult->fetch_assoc()): ?>
                                <option value="<?php echo $officer['id']; ?>">
                                    <?php echo $officer['fname'] . ' ' . $officer['lname'] . ' - ' . $officer['id']; ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="field">
                        <label for="duty">Duty:</label>
                        <input type="text" name="duty" class="input" required>
                    </div>
                    <div class="field">
                        <label for="dutyDate">Duty Date:</label>
                        <input type="date" name="dutyDate" class="input" id="dutyDate" min="<?= date('Y-m-d') ?>" required>
                    </div>
                    <div class="field">
                        <label for="">Duty Time (Start):</label>
                        <input type="time" class="input" name="duty_time_start" id="dutyTimeStart" required>
                    </div>
                    <div class="field">
                        <label for="">Duty Time (End):</label>
                        <input type="time" class="input" name="duty_time_end" id="dutyTimeEnd" required>
                    </div>
                    <div class="field">
                        <label for="notes">Additional Notes:</label>
                        <textarea name="notes" id="notes"></textarea>
                    </div>
                    <button class="btn" id="submitBtn">Assign Duty</button>
                </form>

                <?php if (isset($_SESSION['success'])): ?>
                    <div class="success-message"><?php echo $_SESSION['success']; ?></div>
                    <?php unset($_SESSION['success']); ?>
                <?php endif; ?>

                <?php if (isset($_SESSION['error'])): ?>
                    <div class="error-message"><?php echo $_SESSION['error']; ?></div>
                    <?php unset($_SESSION['error']); ?>
                <?php endif; ?>

                <?php if (isset($_SESSION['errors'])): ?>
                    <div class="error-message">
                        <ul>
                            <?php foreach ($_SESSION['errors'] as $error): ?>
                                <li><?php echo $error; ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <?php unset($_SESSION['errors']); ?>
                <?php endif; ?>

            </div>
        </div>
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const officerSearch = document.getElementById('officerSearch');
    const searchResults = document.getElementById('searchResults');
    const policeIdSelect = document.getElementById('policeIdSelect');
    const existingDutiesTable = document.getElementById('existingDutiesTable');
    const existingDutiesBody = document.getElementById('existingDutiesBody');
    const noDutiesMessage = document.getElementById('noDutiesMessage');
    const existingDutiesTitle = document.getElementById('existingDutiesTitle');
    const dutyDateInput = document.getElementById('dutyDate');
    const dutyTimeStartInput = document.getElementById('dutyTimeStart');
    const dutyTimeEndInput = document.getElementById('dutyTimeEnd');
    const conflictWarning = document.getElementById('conflictWarning');
    const submitBtn = document.getElementById('submitBtn');
    const assignDutyForm = document.getElementById('assignDutyForm');
    
   
    hideDutiesTable();
    

    function debounce(func, wait) {
        let timeout;
        return function(...args) {
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(this, args), wait);
        };
    }


    officerSearch.addEventListener('input', debounce(function(e) {
        const query = e.target.value.trim();
        
        if (query.length < 2) {
            searchResults.style.display = 'none';
            return;
        }
        
        fetch(`search-officers.php?query=${encodeURIComponent(query)}`)
            .then(response => response.json())
            .then(data => {
                searchResults.innerHTML = '';
                
                if (data.length === 0) {
                    searchResults.style.display = 'none';
                    return;
                }
                
                data.forEach(officer => {
                    const item = document.createElement('div');
                    item.className = 'search-result-item';
                    item.textContent = `${officer.name} (ID: ${officer.id})`;
                    item.addEventListener('click', () => {
                        officerSearch.value = `${officer.name} (ID: ${officer.id})`;
                        policeIdSelect.value = officer.id;
                        searchResults.style.display = 'none';
                        
                        
                        fetchExistingDuties(officer.id);
                    });
                    searchResults.appendChild(item);
                });
                
                searchResults.style.display = 'block';
            })
            .catch(error => {
                console.error('Error:', error);
            });
    }, 300));
    

    document.addEventListener('click', function(e) {
        if (e.target !== officerSearch) {
            searchResults.style.display = 'none';
        }
    });
    

    policeIdSelect.addEventListener('change', function() {
        if (this.value) {
            fetchExistingDuties(this.value);
        } else {
            hideDutiesTable();
        }
    });
    
 
    function fetchExistingDuties(officerId) {
        if (!officerId) return;
        
        fetch(`check-existing-duties.php?officer_id=${officerId}`)
            .then(response => response.json())
            .then(data => {
                displayDuties(data);
            })
            .catch(error => {
                console.error('Error:', error);
            });
    }
    

    function checkDutyConflicts() {
        const officerId = policeIdSelect.value;
        if (!officerId || !dutyDateInput.value || !dutyTimeStartInput.value || !dutyTimeEndInput.value) {
            conflictWarning.style.display = 'none';
            submitBtn.disabled = false;
            return;
        }
        
        const date = dutyDateInput.value;
        const startTime = dutyTimeStartInput.value;
        const endTime = dutyTimeEndInput.value;
        
        fetch(`check-existing-duties.php?officer_id=${officerId}&date=${date}&start_time=${startTime}&end_time=${endTime}`)
            .then(response => response.json())
            .then(data => {

                if (data.length > 0 && dutyTimeStartInput.value && dutyTimeEndInput.value) {
                    conflictWarning.style.display = 'block';
                    submitBtn.disabled = true;
                } else {
                    conflictWarning.style.display = 'none';
                    submitBtn.disabled = false;
                }
                
                displayDuties(data);
            })
            .catch(error => {
                console.error('Error:', error);
            });
    }
    

    function displayDuties(duties) {
        existingDutiesBody.innerHTML = '';
        
        if (duties.length === 0) {
            existingDutiesTable.style.display = 'none';
            existingDutiesTitle.style.display = 'none';
            noDutiesMessage.style.display = 'block';
            conflictWarning.style.display = 'none';
            return;
        }
        
        duties.forEach(duty => {
            const row = document.createElement('tr');
            if (duty.has_conflict) {
                row.className = 'conflict';
            }
            
            row.innerHTML = `
                <td>${duty.duty}</td>
                <td>${duty.duty_date}</td>
                <td>${duty.duty_time_start}</td>
                <td>${duty.duty_time_end}</td>
                <td>${duty.notes || ''}</td>
            `;
            existingDutiesBody.appendChild(row);
        });
        
        existingDutiesTable.style.display = 'table';
        existingDutiesTitle.style.display = 'block';
        noDutiesMessage.style.display = 'none';
    }
    
    function hideDutiesTable() {
        existingDutiesTable.style.display = 'none';
        existingDutiesTitle.style.display = 'none';
        noDutiesMessage.style.display = 'none';
        conflictWarning.style.display = 'none';
    }
    

    [dutyDateInput, dutyTimeStartInput, dutyTimeEndInput].forEach(input => {
        input.addEventListener('change', function() {
            if (policeIdSelect.value) {
                checkDutyConflicts();
            }
        });
    });
    

    dutyTimeEndInput.addEventListener('change', function() {
        if (dutyTimeStartInput.value && dutyTimeEndInput.value) {
            if (dutyTimeEndInput.value <= dutyTimeStartInput.value) {
                alert("End time must be after start time");
                dutyTimeEndInput.value = '';
            }
        }
    });
    

    assignDutyForm.addEventListener('submit', function(e) {
        const officerId = policeIdSelect.value;
        const date = dutyDateInput.value;
        const startTime = dutyTimeStartInput.value;
        const endTime = dutyTimeEndInput.value;
        
        if (officerId && date && startTime && endTime) {

            fetch(`check-existing-duties.php?officer_id=${officerId}&date=${date}&start_time=${startTime}&end_time=${endTime}`)
                .then(response => response.json())
                .then(data => {
                    if (data.length > 0) {
                        e.preventDefault();
                        alert("Cannot assign duty. There are conflicting duties for this officer during the selected time period.");
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }
    });
});
</script>

<?php

if (isset($stationStmt))
    $stationStmt->close();
if (isset($officersStmt))
    $officersStmt->close();

include_once "../../../includes/footer.php";
?>