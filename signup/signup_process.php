<?php

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    die("invalid request!");
}

require_once '../db/connect.php';

$fname = $_POST['fname'];
$lname = $_POST['lname'];
$email = $_POST['email'];
$nic = $_POST['nic'];
$userid = strtolower($_POST['userid']);
$password = $_POST['password'];
$cpassword = $_POST['cpassword'];
$asPolice = $_POST['aspolice'] ?? false;
$policeStation = $_POST['policestation'] ?? null;
$phoneNo = $_POST['phoneno'];
$province = $_POST['province'];

if ($password !== $cpassword) {
    die("Passwords do not match!");
}

$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

if ($asPolice) {
    $sql = "INSERT INTO officers (id, fname, lname, email, phone_no, nic, police_station, password) VALUES ('$userid', '$fname', '$lname', '$email', '$phoneNo', '$nic', '$policeStation', '$hashedPassword')";
} else {
    $sql = "INSERT INTO drivers (id, fname, lname, email, phone_no, nic, password) VALUES ('$userid', '$fname', '$lname', '$email', '$phoneNo', '$nic', '$hashedPassword')";
}

if (!$conn->query($sql)) {
    die("Error: " . $conn->error);
}

header('Location: /digifine/login/index.php');
