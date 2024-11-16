<?php
include('../../../../db/connect.php');

if (isset($_GET['violation_id'])) {
    $violation_id = $_GET['violation_id'];

    // Fetch violation data based on the violation ID
    $query = "SELECT violation_name, price 
              FROM violations WHERE violation_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $violation_id); // "i" for integer type
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $violation = $result->fetch_assoc();
        echo json_encode([
            'success' => true,
            'violation_name' => $violation['violation_name'],
            'price' => $violation['price']
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Violation not found']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Violation ID not provided']);
}
