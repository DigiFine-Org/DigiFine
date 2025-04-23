// include_once "config.php";

// session_start();

// if (isset($_SESSION['user'])) {
// header("Location: /digifine/dashboard/index.php");
// } else {
// header("Location: /digifine/landingPage/index.php");
// }


<?php
include_once "config.php";
session_start();

if (isset($_SESSION['user'])) {
    $role = $_SESSION['user']['role']; // Assuming role is stored in the user session data

    switch ($role) {
        case 'admin':
            header("Location: /digifine/dashboard/admin/index.php");
            break;
        case 'oic':
            header("Location: /digifine/dashboard/oic/index.php");
            break;
        case 'driver':
            header("Location: /digifine/dashboard/driver/index.php");
            break;
        case 'officer':
            header("Location: /digifine/dashboard/officer/index.php");
            break;
        default:
            header("Location: /digifine/dashboard/index.php");
            break;
    }
} else {
    header("Location: /digifine/landingPage/index.php");
}
exit();