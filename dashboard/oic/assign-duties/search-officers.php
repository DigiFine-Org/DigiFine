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

    // Search for officers in the same station
    $searchQuery = "SELECT id, CONCAT(first_name, ' ', last_name) AS name 
                  FROM officers 
                  WHERE (first_name LIKE ? OR last_name LIKE ?) 
                  AND police_station = ? 
                  AND is_oic = 0
                  LIMIT 10";
                  
    $stmt = $conn->prepare($searchQuery);
    $searchParam = "%$query%";
    $stmt->bind_param("ssi", $searchParam, $searchParam, $stationId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $officers = [];
    while ($row = $result->fetch_assoc()) {
        $officers[] = [
            'id' => $row['id'],
            'name' => $row['name']
        ];
    }
    
    header('Content-Type: application/json');
    echo json_encode($officers);
    
} catch (Exception $e) {
    header('HTTP/1.0 500 Internal Server Error');
    echo json_encode(['error' => $e->getMessage()]);
}