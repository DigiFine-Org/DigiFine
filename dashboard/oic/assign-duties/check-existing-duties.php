<?php
session_start();
include_once "../../../db/connect.php";

// Check if user is authorized
if ($_SESSION['user']['role'] !== 'oic' && $_SESSION['user']['is_oic'] != 1) {
    header('HTTP/1.0 403 Forbidden');
    echo json_encode(['error' => 'Unauthorized access']);
    exit;
}

// Get parameters
$officerId = intval($_GET['officer_id'] ?? 0);
$date = $_GET['date'] ?? '';
$startTime = $_GET['start_time'] ?? '';
$endTime = $_GET['end_time'] ?? '';

if (!$officerId) {
    echo json_encode([]);
    exit;
}

try {
    // If no date specified, get all upcoming duties for the officer
    if (empty($date)) {
        $query = "SELECT id, duty, duty_date, duty_time_start, duty_time_end, notes 
                  FROM assigned_duties 
                  WHERE police_id = ? 
                  AND duty_date >= CURDATE()
                  AND submitted = 0
                  ORDER BY duty_date ASC, duty_time_start ASC
                  LIMIT 10";
        
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $officerId);
    } else {
        // If date is specified, check for duties on that date
        $query = "SELECT id, duty, duty_date, duty_time_start, duty_time_end, notes 
                FROM assigned_duties 
                WHERE police_id = ? 
                AND duty_date = ?
                AND submitted = 0";
        
        $params = [$officerId, $date];
        $types = "is";
        
        // If time range is provided, check for overlapping duties
        if ($startTime && $endTime) {
            $query .= " AND (
                (duty_time_start <= ? AND duty_time_end > ?) OR  -- New duty starts during existing duty
                (duty_time_start < ? AND duty_time_end >= ?) OR  -- New duty ends during existing duty
                (duty_time_start >= ? AND duty_time_end <= ?)    -- Existing duty is within new duty
            )";
            
            $params = array_merge($params, [$endTime, $startTime, $endTime, $startTime, $startTime, $endTime]);
            $types .= "ssssss";
        }
        
        $stmt = $conn->prepare($query);
        $stmt->bind_param($types, ...$params);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    
    $duties = [];
    while ($row = $result->fetch_assoc()) {
        $duties[] = [
            'id' => $row['id'],
            'duty' => $row['duty'],
            'duty_date' => $row['duty_date'],
            'duty_time_start' => substr($row['duty_time_start'], 0, 5), // Format as HH:MM
            'duty_time_end' => substr($row['duty_time_end'], 0, 5),
            'notes' => $row['notes'],
            'has_conflict' => !empty($startTime) && !empty($endTime)
        ];
    }
    
    header('Content-Type: application/json');
    echo json_encode($duties);
    
} catch (Exception $e) {
    header('HTTP/1.0 500 Internal Server Error');
    echo json_encode(['error' => $e->getMessage()]);
}
?>