<?php
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    session_start(); // Ensure session is started
    // Get the search ID and type from the query parameters
    $searchId = isset($_GET['query']) ? htmlspecialchars($_GET['query']) : '';
    $searchType = isset($_GET['search_type']) ? htmlspecialchars($_GET['search_type']) : 'license';

    if (!$searchId) {
        $_SESSION['message'] = "Search ID is required!";
        header("Location: /digifine/dashboard/officer/verify-driver-details/index.php");
        exit();
    }

    // Determine which field to search based on search type
    if ($searchType === 'license') {
        $searchField = "license_id";
    } else {
        $searchField = "nic";
    }

    // Fetch driver details
    $sql = "SELECT
                fname,
                lname,
                license_id,
                nic,
                address,
                birth_date,
                license_issue_date,
                license_expiry_date,
                restrictions,
                blood_group,
                A1_issue_date, A1_expiry_date,
                A_issue_date, A_expiry_date,
                B1_issue_date, B1_expiry_date,
                B_issue_date, B_expiry_date,
                C1_issue_date, C1_expiry_date,
                C_issue_date, C_expiry_date,
                CE_issue_date, CE_expiry_date,
                D1_issue_date, D1_expiry_date,
                D_issue_date, D_expiry_date,
                DE_issue_date, DE_expiry_date,
                G1_issue_date, G1_expiry_date,
                G_issue_date, G_expiry_date,
                J_issue_date, J_expiry_date
            FROM dmt_drivers
            WHERE $searchField = ?";

    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("Database error: " . $conn->error);
    }

    $stmt->bind_param("s", $searchId);

    if (!$stmt->execute()) {
        die("Query execution error: " . $stmt->error);
    }

    $result = $stmt->get_result();

    // Check if the driver exists
    if ($result->num_rows === 0) {
        $_SESSION['message'] = "Driver not found!";
        header("Location: /digifine/dashboard/officer/verify-driver-details/index.php");
        exit(); // Stop further execution
    }

    $driver = $result->fetch_assoc();

    // Map vehicle categories
    $vehicleCategories = [
        'A1' => ['issue_date' => $driver['A1_issue_date'], 'expiry_date' => $driver['A1_expiry_date']],
        'A' => ['issue_date' => $driver['A_issue_date'], 'expiry_date' => $driver['A_expiry_date']],
        'B1' => ['issue_date' => $driver['B1_issue_date'], 'expiry_date' => $driver['B1_expiry_date']],
        'B' => ['issue_date' => $driver['B_issue_date'], 'expiry_date' => $driver['B_expiry_date']],
        'C1' => ['issue_date' => $driver['C1_issue_date'], 'expiry_date' => $driver['C1_expiry_date']],
        'C' => ['issue_date' => $driver['C_issue_date'], 'expiry_date' => $driver['C_expiry_date']],
        'CE' => ['issue_date' => $driver['CE_issue_date'], 'expiry_date' => $driver['CE_expiry_date']],
        'D1' => ['issue_date' => $driver['D1_issue_date'], 'expiry_date' => $driver['D1_expiry_date']],
        'D' => ['issue_date' => $driver['D_issue_date'], 'expiry_date' => $driver['D_expiry_date']],
        'DE' => ['issue_date' => $driver['DE_issue_date'], 'expiry_date' => $driver['DE_expiry_date']],
        'G1' => ['issue_date' => $driver['G1_issue_date'], 'expiry_date' => $driver['G1_expiry_date']],
        'G' => ['issue_date' => $driver['G_issue_date'], 'expiry_date' => $driver['G_expiry_date']],
        'J' => ['issue_date' => $driver['J_issue_date'], 'expiry_date' => $driver['J_expiry_date']],
    ];

    // Redirect with driver details
    header("Location: verify-driver-details.php?query=$searchId&search_type=$searchType&driver=" . urlencode(json_encode($driver)) . "&categories=" . urlencode(json_encode($vehicleCategories)));
    exit();
} else {
    die("Invalid request method!");
}