<?php
session_start();
require_once "../../../db/connect.php";


if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'oic') {
    die("Unauthorized access.");
}


$license_plate_number = $_POST['license_plate_number'] ?? null;
$owner_name = $_POST['owner_name'] ?? null;
$national_id = $_POST['national_id'] ?? null;
$release_date = $_POST['date'] ?? null;
$release_notes = $_POST['release_notes'] ?? null;

if (!$license_plate_number || !$owner_name || !$national_id || !$release_date) {
    die("Missing required data.");
}


$sql = "INSERT INTO vehicle_release_log (license_plate_number, owner_name, national_id, release_date, notes) VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssss", $license_plate_number, $owner_name, $national_id, $release_date, $release_notes);
$stmt->execute();
$stmt->close();

$sql = "UPDATE seized_vehicle SET is_released = 1 WHERE license_plate_number = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $license_plate_number);

if ($stmt->execute()) {
    $stmt->close();
    $conn->close();
    header("Location: index.php?success=1");
    exit();
} else {
    echo "Error releasing vehicle: " . $stmt->error;
    $stmt->close();
    $conn->close();
}
