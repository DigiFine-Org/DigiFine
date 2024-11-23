<?php

require_once "./admin-auth.php";

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    die("invalid request!");
}

require_once '../db/connect.php';
session_start();

$userid = htmlspecialchars($_POST['userid']);
$password = $_POST['password'];



$isAdmin = AdminAuth::check_credential($userid, $password);
if (!is_null($isAdmin)) {
    if (!$isAdmin) {
        die("Incorrect password!!!");
    }
    $user = ['id' => $userid, 'role' => 'admin'];
    $_SESSION['user'] = $user;
    header("Location: /digifine/dashboard/index.php");
}


$asPolice = true;

// search in officers
$sql = "SELECT id,fname,lname,email,nic,password,is_oic,phone_no FROM officers WHERE id = '$userid'";
$result = $conn->query($sql);
if (!$result) {
    die("Error: " . $conn->error);
}

if ($result->num_rows == 0) {
    // search in drivers
    $sql = "SELECT id,fname,lname,email,nic,password,phone_no FROM drivers WHERE id = '$userid'";
    $result = $conn->query($sql);
    if (!$result) {
        die("Error: " . $conn->error);
    }
    // Neither a officer nor a driver found
    if ($result->num_rows == 0) {
        die("No account found with that ID!");
    }
    $asPolice = false;
}



$user = $result->fetch_assoc();


$dbPasswordHash = $user['password'];
if (!password_verify($password, $dbPasswordHash)) {
    die("Incorrect password!");
}

// remove password field from database record to save to session
unset($user['password']);

// set user role
$user['role'] = $asPolice ? 'officer' : 'driver';
$_SESSION['user'] = $user;
header("Location: /digifine/dashboard/index.php");
