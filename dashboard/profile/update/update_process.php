<?php

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("invalid method!!!");
}

require_once '../../../db/connect.php';

session_start();

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    die("invalid request!");
}

$currentUser = $_SESSION['user'];
$asPolice = $currentUser['role'] === 'officer';
$userid = $currentUser['id'];

// fetch current user info
if ($asPolice) {
    $sql = "SELECT fname,lname,email,phone_no,nic,police_station FROM officers WHERE id = '$userid'";
} else {
    $sql = "SELECT fname,lname,email,phone_no,nic FROM drivers WHERE id = '$userid'";
}
$result = $conn->query($sql);
if (!$result) {
    die("Error: " . $conn->error);
}
if ($result->num_rows === 0) {
    die("User not found");
}

$user = $result->fetch_assoc();

$fname = $_POST['fname'];
$lname = $_POST['lname'];
$email = $_POST['email'];
$nic = $_POST['nic'];
$password = $_POST['password'] ?? null;
$policeStation = $_POST['policestation'] ?? null;
$phoneNo = $_POST['phoneno'];
$hashedPassword = $password ? password_hash($password, PASSWORD_DEFAULT) : '';

// check if any changes are made
if (
    $hashedPassword === '' &&
    $user['fname'] === $fname &&
    $user['lname'] === $lname &&
    $user['email'] === $email &&
    $user['phone_no'] === $phoneNo &&
    $user['nic'] === $nic &&
    ($asPolice ? $user['police_station'] === $policeStation : true)
) {
    die("No changes detected!");
}

// check for duplicate phone number, email, nic
if ($asPolice) {
    $sql = "SELECT * FROM officers WHERE (phone_no = '$phoneNo' OR email = '$email' OR nic = '$nic') AND id != '$userid'";
} else {
    $sql = "SELECT * FROM drivers WHERE (phone_no = '$phoneNo' OR email = '$email' OR nic = '$nic') AND id != '$userid'";
}
$result = $conn->query($sql);
if (!$result) {
    die("Error: " . $conn->error);
}
if ($result->num_rows > 0) {
    $duplicate = $result->fetch_assoc();
    if ($duplicate['phone_no'] === $phoneNo) {
        die("Phone number already in use!");
    }
    if ($duplicate['email'] === $email) {
        die("Email already in use!");
    }
    if ($duplicate['nic'] === $nic) {
        die("NIC already in use!");
    }
}


// insert into update requests table
if ($asPolice) {
    $sql = "INSERT INTO update_officer_profile_requests (id, fname, lname, email, phone_no, police_station, nic, password) VALUES ('$userid', '$fname', '$lname', '$email', '$phoneNo', '$policeStation', '$nic', NULLIF('$hashedPassword',''))";
} else {
    $sql = "INSERT INTO update_driver_profile_requests (id, fname, lname, email, phone_no, nic, password) VALUES ('$userid', '$fname', '$lname', '$email', '$phoneNo', '$nic', NULLIF('$hashedPassword',''))";
}
var_dump($sql);
$ok = $conn->query($sql);
if (!$ok) {
    die("Error: " . $conn->error);
}

echo "<script>
    alert('Profile update request sent successfully!');
    window.location.href = '/digifine/dashboard/profile';
    </script>";
