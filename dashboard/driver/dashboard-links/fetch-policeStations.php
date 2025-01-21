<?php
require_once "../../../db/connect.php";

if (!isset($_GET['province']) || !is_numeric($_GET['province'])) {
    echo json_encode(["error" => "Invalid province ID"]);
    exit();
}

$provinceId = (int)$_GET['province'];
$sql = "SELECT name, telephone FROM police_stations WHERE province = ?";
$stmt = $conn->prepare($sql);

if ($stmt) {
    $stmt->bind_param("i", $provinceId);
    $stmt->execute();
    $result = $stmt->get_result();

    $stations = [];
    while ($row = $result->fetch_assoc()) {
        $stations[] = [
            'name' => $row['name'],
            'telephone' => $row['telephone']
        ];
    }

    echo json_encode($stations);
} else {
    echo json_encode(["error" => "Database error: " . $conn->error]);
}
exit();
?>
