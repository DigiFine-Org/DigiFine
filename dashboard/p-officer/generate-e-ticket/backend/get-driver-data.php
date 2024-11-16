<?php
include('../../../../db/connect.php');
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch driver data based on the license number
    $query = "SELECT full_name, phone_no, license_valid_from, license_valid_to, competent_categories, d_address
              FROM drivers WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $driver = $result->fetch_assoc();
        echo json_encode([
            'success' => true,
            'full_name' => $driver['full_name'],
            'phone_no' => $driver['phone_no'],
            'license_valid_from' => $driver['license_valid_from'],
            'license_valid_to' => $driver['license_valid_to'],
            'competent_categories' => $driver['competent_categories'],
            'd_address' => $driver['d_address']
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Driver not found']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'License number not provided']);
}
