<?php
include('../../../../db/connect.php');

// Start the session
session_start();  // Start the session to access session variables

// Ensure the user is logged in and the role is 'officer'
if (isset($_SESSION['user']) && $_SESSION['user']['role'] == 'officer') {
    $officer_id = $_SESSION['user']['id'];  // Officer's ID from the session
    echo "Officer ID: " . $officer_id;
} else {
    echo json_encode(['success' => false, 'message' => 'You are not an officer or no user is logged in!']);
    exit();
}

// Collect POST data
$id = isset($_POST['id']) ? $_POST['id'] : '';
$vehicle_number = isset($_POST['vehicle_number']) ? $_POST['vehicle_number'] : '';
$violation_id = isset($_POST['violation_id']) ? (int)$_POST['violation_id'] : 0; // Convert to integer
$description = isset($_POST['description']) ? $_POST['description'] : '';
$issued_place = isset($_POST['issued_place']) ? $_POST['issued_place'] : '';
$payment_status = 'Pending'; // Default status

// Validate required fields
if (empty($id) || empty($violation_id) || empty($vehicle_number)) {
    echo json_encode(['success' => false, 'message' => 'Required fields are missing.']);
    exit();
}

// Check if the id exists in the drivers table
$checkQuery = "SELECT COUNT(*) as count FROM drivers WHERE id = ?";
$checkStmt = $conn->prepare($checkQuery);
$checkStmt->bind_param("s", $id);
$checkStmt->execute();
$checkResult = $checkStmt->get_result();
$checkRow = $checkResult->fetch_assoc();

if ($checkRow['count'] == 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid driver id.']);
    exit();
}

// Calculate expire date as 14 days from the current date
$issued_on = date('Y-m-d H:i:s'); // Current timestamp
$expire_date = date('Y-m-d', strtotime($issued_on . ' + 14 days'));

// Insert data into the fines table
$query = "INSERT INTO fines (officer_id, driver_id, vehicle_number, violation_id, issued_on, expire_date, description, issued_place, payment_status)
          VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($query);

if ($stmt === false) {
    echo json_encode(['success' => false, 'message' => 'Prepare failed: ' . $conn->error]);
    exit();
}

// Bind parameters with correct types
$stmt->bind_param("ississsss", $officer_id, $id, $vehicle_number, $violation_id, $issued_on, $expire_date, $description, $issued_place, $payment_status);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Fine issued successfully.']);
    echo "<script>alert('Fine issued successfully.');</script>";
} else {
    echo json_encode(['success' => false, 'message' => 'Error issuing fine: ' . $stmt->error]);
    echo "<script>alert('Error issuing fine: " . $stmt->error . "');</script>";
}

// Close the prepared statement and the connection
$stmt->close();
$checkStmt->close();
$conn->close();
