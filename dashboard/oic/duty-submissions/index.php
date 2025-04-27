<?php
$pageConfig = [
    'title' => 'Submitted Duties',
    'styles' => ["../../dashboard.css", "./search-duties.css"],
    'scripts' => ["../../dashboard.js"],
    'authRequired' => true
];

require_once "../../../db/connect.php";
include_once "../../../includes/header.php";

if ($_SESSION['user']['role'] !== 'oic') {
    die("Unauthorized user!");
}

try {
    $oic_id = $_SESSION['user']['id'];

    $sql = "SELECT police_station FROM officers WHERE is_oic = 1 AND id = ? LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $oic_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        die("OIC not found or police station not assigned.");
    }

    $oic_data = $result->fetch_assoc();
    $police_station_id = $oic_data['police_station'];


    $search_officer = isset($_GET['officer_name']) ? trim($_GET['officer_name']) : '';
    $search_date_from = isset($_GET['date_from']) ? trim($_GET['date_from']) : '';
    $search_date_to = isset($_GET['date_to']) ? trim($_GET['date_to']) : '';


    $query = "
        SELECT 
            ds.id AS submission_id, 
            ds.police_id, 
            CONCAT(o.fname, ' ', o.lname) AS officer_name,
            a.duty_date,
            ds.patrol_location, 
            a.duty_time_start, 
            ds.patrol_time_started, 
            a.duty_time_end, 
            ds.patrol_time_ended, 
            ds.is_late_submission
        FROM duty_submissions ds
        INNER JOIN officers o ON ds.police_id = o.id
        INNER JOIN assigned_duties a ON ds.assigned_duty_id = a.id
        WHERE o.police_station = ?";
    
    $params = [$police_station_id];
    $types = "i";
    

    if (!empty($search_officer)) {
        $query .= " AND (o.fname LIKE ? OR o.lname LIKE ? OR CONCAT(o.fname, ' ', o.lname) LIKE ?)";
        $search_term = "%" . $search_officer . "%";
        $params = array_merge($params, [$search_term, $search_term, $search_term]);
        $types .= "sss";
    }
    
    if (!empty($search_date_from)) {
        $query .= " AND a.duty_date >= ?";
        $params[] = $search_date_from;
        $types .= "s";
    }
    
    if (!empty($search_date_to)) {
        $query .= " AND a.duty_date <= ?";
        $params[] = $search_date_to;
        $types .= "s";
    }
    

    $query .= " ORDER BY a.duty_date DESC, o.lname ASC";

    $stmt = $conn->prepare($query);
    if (!$stmt) {
        throw new Exception("Failed to prepare statement: " . $conn->error);
    }

    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();

    $duties = [];
    while ($row = $result->fetch_assoc()) {
        $duties[] = $row;
    }
    

    $officersQuery = "SELECT id, CONCAT(fname, ' ', lname) as name FROM officers WHERE police_station = ? ORDER BY lname, fname";
    $officersStmt = $conn->prepare($officersQuery);
    $officersStmt->bind_param("i", $police_station_id);
    $officersStmt->execute();
    $officersResult = $officersStmt->get_result();
    $officers = [];
    while ($row = $officersResult->fetch_assoc()) {
        $officers[] = $row;
    }
} catch (Exception $e) {
    die("Error fetching submitted duties: " . $e->getMessage());
}
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
            <div class="">
                <h1>Duty Submissions</h1>
                

                <div class="search-container">
                    <form id="searchForm" method="GET" action="">
                        <div class="search-fields">
                            <div class="search-field">
                                <label for="officer_name">Officer Name:</label>
                                <input type="text" id="officer_name" name="officer_name" class="search-input" value="<?= htmlspecialchars($search_officer) ?>" placeholder="Search by name">
                                <div class="search-results" id="officerSearchResults"></div>
                            </div>
                            <div class="search-field">
                                <label for="date_from">From Date:</label>
                                <input type="date" id="date_from" name="date_from" class="search-input" value="<?= htmlspecialchars($search_date_from) ?>">
                            </div>
                            <div class="search-field">
                                <label for="date_to">To Date:</label>
                                <input type="date" id="date_to" name="date_to" class="search-input" value="<?= htmlspecialchars($search_date_to) ?>">
                            </div>
                            <div class="search-buttons">
                                <button type="submit" class="search-btn">Search</button>
                                <button type="button" id="resetBtn" class="reset-btn">Reset</button>
                            </div>
                        </div>
                    </form>
                </div>
                
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Police ID</th>
                                <th>Officer Name</th>
                                <th>Duty Date</th>
                                <th>Duty Location</th>
                                <th>Assigned Start</th>
                                <th>Actual Start</th>
                                <th>Assigned End</th>
                                <th>Actual End</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($duties)): ?>
                                <tr>
                                    <td colspan="9">No submitted duties found.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($duties as $duty): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($duty['police_id']) ?></td>
                                        <td><?= htmlspecialchars($duty['officer_name']) ?></td>
                                        <td><?= htmlspecialchars($duty['duty_date']) ?></td>
                                        <td><?= htmlspecialchars($duty['patrol_location']) ?></td>
                                        <td><?= htmlspecialchars(substr($duty['duty_time_start'], 0, 5)) ?></td>
                                        <td><?= htmlspecialchars(substr($duty['patrol_time_started'], 0, 5)) ?></td>
                                        <td><?= htmlspecialchars(substr($duty['duty_time_end'], 0, 5)) ?></td>
                                        <td><?= htmlspecialchars(substr($duty['patrol_time_ended'], 0, 5)) ?></td>
                                        <td class="<?= $duty['is_late_submission'] ? 'late-status' : 'ontime-status' ?>"><?= $duty['is_late_submission'] ? 'Late' : 'On Time' ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const officerInput = document.getElementById('officer_name');
    const searchResults = document.getElementById('officerSearchResults');
    const resetBtn = document.getElementById('resetBtn');
    

    const officers = <?= json_encode($officers) ?>;
    

    function debounce(func, wait) {
        let timeout;
        return function(...args) {
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(this, args), wait);
        };
    }
    

    officerInput.addEventListener('input', debounce(function(e) {
        const query = e.target.value.trim().toLowerCase();
        
        if (query.length < 2) {
            searchResults.style.display = 'none';
            return;
        }
        

        const filteredOfficers = officers.filter(officer => 
            officer.name.toLowerCase().includes(query)
        );
        
        searchResults.innerHTML = '';
        
        if (filteredOfficers.length === 0) {
            searchResults.style.display = 'none';
            return;
        }
        
        filteredOfficers.forEach(officer => {
            const item = document.createElement('div');
            item.className = 'search-result-item';
            item.textContent = officer.name;
            item.addEventListener('click', () => {
                officerInput.value = officer.name;
                searchResults.style.display = 'none';
            });
            searchResults.appendChild(item);
        });
        
        searchResults.style.display = 'block';
    }, 300));
    

    document.addEventListener('click', function(e) {
        if (e.target !== officerInput) {
            searchResults.style.display = 'none';
        }
    });
    

    resetBtn.addEventListener('click', function() {
        officerInput.value = '';
        document.getElementById('date_from').value = '';
        document.getElementById('date_to').value = '';
        document.getElementById('searchForm').submit();
    });
    

    document.getElementById('searchForm').addEventListener('submit', function(e) {
        const dateFrom = document.getElementById('date_from').value;
        const dateTo = document.getElementById('date_to').value;
        
        if (dateFrom && dateTo && dateFrom > dateTo) {
            e.preventDefault();
            alert('From date cannot be after To date');
        }
    });
});
</script>

<?php include_once "../../../includes/footer.php"; ?>