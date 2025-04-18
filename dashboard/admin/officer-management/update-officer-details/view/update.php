<?php
require_once "../../../../../db/connect.php";
session_start();

if ($_SESSION['user']['role'] !== 'admin') {
    die("Unauthorized access!");
}

$id = $_POST['id'];
$fname = $_POST['fname'];
$lname = $_POST['lname'];
$phone_no = $_POST['phone_no'];
$email = $_POST['email'];
$nic = $_POST['nic'];
$police_station = $_POST['police_station'];

$stmt = $conn->prepare("UPDATE officers SET fname=?, lname=?, phone_no=?, email=?, nic=?, police_station=? WHERE id=?");
$stmt->bind_param("ssssssi", $fname, $lname, $phone_no, $email, $nic, $police_station, $id);

if ($stmt->execute()) {
    echo "<script>alert('Officer details updated successfully!');
    window.location.href = '/digifine/dashboard/admin/officer-management/index.php';</script>";
} else {
    echo "Error: " . $conn->error;
}
$stmt->close();
