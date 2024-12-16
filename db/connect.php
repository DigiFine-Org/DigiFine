<?php

$servername = "mysql-digifine.alwaysdata.net";
$username = "digifine_db";
$password = "Digifine1234#";
$dbname = "digifine_db";

$conn = new mysqli($servername, $username, $password, $dbname);
mysqli_report(MYSQLI_REPORT_OFF);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
