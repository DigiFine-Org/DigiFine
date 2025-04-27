<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("invalid method!!!");
}

require_once "../../../../db/connect.php";

$officerid = $_POST["officer_id"];
$police_station_id = $_POST["police_station_id"];

$stmt1 = $conn->prepare("SELECT * FROM officers WHERE id!=? AND police_station=? AND is_oic=1");
$stmt1->bind_param("ss", $officerid, $police_station_id);
if (!$stmt1->execute()) {
    die("mysql stmt1 execution error!");
}
$result = $stmt1->get_result();
$alreadyOICInCurrentStation = $result->fetch_assoc();
$stmt1->close();
if ($alreadyOICInCurrentStation) {

    $_SESSION['message'] = "There exists an OIC already";
    header("Location: /digifine/dashboard/admin/officer-management/assign-oic/index.php");
    exit();
}

$stmt2 = $conn->prepare("UPDATE officers SET is_oic=1 WHERE id=?");
$stmt2->bind_param("s", $officerid);
if (!$stmt2->execute()) {
    die("mysql stmt2 execution error!");
}
header('Location: /digifine/dashboard/admin/officer-management/assign-oic/index.php');
$_SESSION['message'] = "OIC assigned successfully!"; 
$stmt2->close();
