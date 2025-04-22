<?php
require_once "../../../db/connect.php";

$license = $_GET['license'] ?? '';

if (!$license) {
    echo json_encode(['error' => 'License plate missing.']);
    exit;
}

$sql = "SELECT owner_name, national_id, release_date, notes FROM vehicle_release_log WHERE license_plate_number = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $license);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    echo json_encode([
        'license_plate_number' => $license,
        'owner_name' => $row['owner_name'],
        'nic' => $row['national_id'],
        'release_date' => $row['release_date'],
        'notes' => $row['notes']
    ]);
} else {
    echo json_encode(['error' => 'No data found for this license.']);
}

$stmt->close();
$conn->close();
