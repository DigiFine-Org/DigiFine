<?php
include('../../../../db/connect.php');
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

// // Debugging: Print the contents of $_POST
// echo '<pre>';
// print_r($_POST);
// echo '</pre>';


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect form data
    session_start();  // Start the session to access session variables

    // Check if the session is set and the user is an officer
    if (isset($_SESSION['user'])) {
        // Access the officer's ID
        $officer_id = $_SESSION['user']['id'];  // This is the officer's ID from the session

        // You can now use $officerId to query the database, display information, etc.
        echo "Officer ID: " . $officer_id;

        // Optionally check the user's role
        if ($_SESSION['user']['role'] == 'officer') {
            // Perform actions specific to officers
        } else {
            echo "You are not an officer!";
        }
    } else {
        echo "No user is logged in!";
    }
    $officer_id = isset($_POST['officer_id']) ? $_POST['officer_id'] : '';
    $driver_id = isset($_POST['driver_id']) ? $_POST['driver_id'] : '';
    $vehicle_number = isset($_POST['vehicle_number']) ? $_POST['vehicle_number'] : '';
    $violation_id = isset($_POST['violation_id']) ? (int)$_POST['violation_id'] : 0; // Convert to integer
    $description = isset($_POST['description']) ? $_POST['description'] : '';
    $issued_place = isset($_POST['issued_place']) ? $_POST['issued_place'] : '';
    $payment_status = 'Pending'; // Default status or based on form input if available

    // Validate form inputs
    echo '<pre>';
    print_r($_POST); // Print all POST data to verify if all fields are sent
    echo '</pre>';
    if (empty($officer_id) || empty($driver_id) || empty($violation_id) || empty($vehicle_number)) {
        echo json_encode(['success' => false, 'message' => 'Required fields are missing.']);
        exit();
    }

    // Check if the driver_id exists in the drivers table
    $checkQuery = "SELECT COUNT(*) as count FROM drivers WHERE driver_id = ?";
    $checkStmt = $conn->prepare($checkQuery);
    $checkStmt->bind_param("s", $driver_id);
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
    $stmt->bind_param("ississsss", $officer_id, $driver_id, $vehicle_number, $violation_id, $issued_on, $expire_date, $description, $issued_place, $payment_status);
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Fine issued successfully.']);
        echo "<script>alert('Fine issued successfully.');</script>";
    } else {
        echo json_encode(['success' => false, 'message' => 'Error issuing fine: ' . $stmt->error]);
        echo "<script>alert('Error issuing fine: " . $stmt->error . "');</script>";
    }

    $stmt->close();
    $checkStmt->close();
}
$conn->close();
