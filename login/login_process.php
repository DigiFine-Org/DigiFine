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
        $_SESSION['message'] = "Incorrect password!";
        header("Location: /digifine/login/index.php");
        exit();
    }
    $user = ['id' => $userid, 'role' => 'admin'];
    $_SESSION['user'] = $user;
    header("Location: /digifine/dashboard/admin/index.php");
    return;
}


$asPolice = true;

$sql = "SELECT id,fname,lname,email,nic,password,is_oic,phone_no FROM officers WHERE id = '$userid'";
$result = $conn->query($sql);
if (!$result) {
    die("Error: " . $conn->error);
}

if ($result->num_rows == 0) {
    $sql = "SELECT id,fname,lname,email,nic,password,phone_no FROM drivers WHERE id = '$userid'";
    $result = $conn->query($sql);
    if (!$result) {
        die("Error: " . $conn->error);
    }
    if ($result->num_rows == 0) {
        $_SESSION['message'] = "No account found with that ID!";
        header("Location: /digifine/login/index.php");
        exit();
    }
    $asPolice = false;
}



$user = $result->fetch_assoc();


$dbPasswordHash = $user['password'];
if (!password_verify($password, $dbPasswordHash)) {
    $_SESSION['message'] = "Incorrect password!";
    header("Location: /digifine/login/index.php");
    exit();
}

unset($user['password']);

$user['role'] = $asPolice ? ($user['is_oic'] ? 'oic' : 'officer') : 'driver';
$_SESSION['user'] = $user;

header("Location: /digifine/dashboard/" . $user['role'] . "/index.php");
