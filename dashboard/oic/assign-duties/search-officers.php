<?php
session_start();
include_once "../../../db/connect.php";

// Check if user is authorized
if ($_SESSION['user']['role'] !== 'oic' && $_SESSION['user']['is_oic'] != 1) {
    header('HTTP/1.0 403 Forbidden');
    echo json_encode(['error' => 'Unauthorized access']);
    exit;
}

// Get search query
$query = trim($_GET['query'] ?? '');

if (empty($query) || strlen($query) < 2) {
    echo json_encode([]);
    exit;
}

try {
    // Get OIC's station ID first
    $oicStationQuery = "SELECT police_station FROM officers WHERE id = ? AND is_oic = 1";
    $oicStationStmt = $conn->prepare($oicStationQuery);
    $oicStationStmt->bind_param("i", $_SESSION['user']['id']);
    $oicStationStmt->execute();
    $oicStationResult = $oicStationStmt->get_result();
    $oicStation = $oicStationResult->fetch_assoc();

    if (!$oicStation) {
        echo json_encode([]);
        exit;
    }

    $stationId = $oicStation['police_station'];

    // Search for officers in the same station by name or ID
    $searchQuery = "SELECT id, fname, lname 
                  FROM officers 
                  WHERE (fname LIKE ? OR lname LIKE ? OR id = ?) 
                  AND police_station = ? 
                  AND is_oic = 0
                  LIMIT 10";
                  
    $stmt = $conn->prepare($searchQuery);
    $searchParam = "%$query%";
    
    // Check if query is numeric (could be an ID search)
    $idSearch = is_numeric($query) ? intval($query) : 0;
    
    $stmt->bind_param("ssii", $searchParam, $searchParam, $idSearch, $stationId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $officers = [];
    while ($row = $result->fetch_assoc()) {
        $officers[] = [
            'id' => $row['id'],
            'name' => $row['fname'] . ' ' . $row['lname']
        ];
    }
    
    header('Content-Type: application/json');
    echo json_encode($officers);
    
} catch (Exception $e) {
    header('HTTP/1.0 500 Internal Server Error');
    echo json_encode(['error' => $e->getMessage()]);
}
?>