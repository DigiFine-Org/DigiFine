<?php
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Invalid method");
}

require_once "../../../db/connect.php";

$action = $_POST['action'] ?? null;
$type = $_POST['type'] ?? null;
$id = $_POST['id'] ?? null;

if (!$action || !$type || !$id) {
    die("Invalid request data");
}


$request_table = $type === 'officer' ? 'update_officer_profile_requests' : 'update_driver_profile_requests';
$main_table = $type === 'officer' ? 'officers' : 'drivers';

if ($action === 'approve') {
    
    $sql = "SELECT * FROM $request_table WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        die("Request not found");
    }

    $request = $result->fetch_assoc();

    
    $sql_update = "
        UPDATE $main_table SET
        fname = ?,
        lname = ?,
        email = ?,
        phone_no = ?,
        nic = ?,
        password = IFNULL(?, password)
        WHERE id = ?
    ";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param(
        "sssssss",
        $request['fname'],
        $request['lname'],
        $request['email'],
        $request['phone_no'],
        $request['nic'],
        $request['password'],
        $id
    );

    if (!$stmt_update->execute()) {
        die("Error updating profile: " . $stmt_update->error);
    }

    
    $conn->query("DELETE FROM $request_table WHERE id = '$id'");
    echo "<script>
        alert('Profile updated successfully!');
        window.location.href = '/digifine/dashboard/admin/index.php';
    </script>";
} elseif ($action === 'reject') {
    
    $conn->query("DELETE FROM $request_table WHERE id = '$id'");
    echo "<script>
        alert('Request rejected!');
        window.location.href = '/digifine/dashboard/admin/index.php';
    </script>";
} else {
    die("Invalid action");
}
