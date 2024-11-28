<?php
require_once "../../db/connect.php";
require_once "../../constants.php";

session_start();

$currentUser = $_SESSION['user'];
if ($currentUser['role'] !== 'admin') {
    $userid = $currentUser['id'];
    $asPolice = $currentUser['role'] === 'officer' || $currentUser['role'] === 'oic';
    if ($asPolice) {
        $sql = "SELECT fname, lname, email, nic, phone_no, police_station FROM officers WHERE id = '$userid'";
    } else {
        $sql = "SELECT fname, lname, email, nic, phone_no FROM drivers WHERE id = '$userid'";
    }
    $result = $conn->query($sql);

    if (!$result) {
        die("Error: " . $conn->error);
    }

    if ($result->num_rows === 0) {
        die("User not found");
    }

    $user = $result->fetch_assoc();


    // fetch police station info
    $policeStation = null;
    if ($asPolice) {
        $result = $conn->query("SELECT name, province FROM police_stations WHERE id = '" . $user['police_station'] . "'");
        if (!$result) {
            die("Error: " . $conn->error);
        }
        if ($result->num_rows === 0) {
            die("Police Station not found!");
        }
        $policeStation = $result->fetch_assoc();
    }
}


?>

<?php
$pageConfig = [
    'title' => 'Driver Dashboard',
    'styles' => ["../dashboard.css", "./profile.css"],
    'scripts' => ["../dashboard.js"],
    'authRequired' => true
];

include_once "../../includes/header.php";


?>

<main>
    <?php include_once "../includes/navbar.php" ?>
    <div class="dashboard-layout">
        <?php include_once "../includes/sidebar.php" ?>
        <div class="content">
            <div class="container large">
                <?php if ($currentUser['role'] === 'admin'): ?>
                    <div class="avatar-wrapper">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="avatar">
                            <path fill-rule="evenodd"
                                d="M18.685 19.097A9.723 9.723 0 0 0 21.75 12c0-5.385-4.365-9.75-9.75-9.75S2.25 6.615 2.25 12a9.723 9.723 0 0 0 3.065 7.097A9.716 9.716 0 0 0 12 21.75a9.716 9.716 0 0 0 6.685-2.653Zm-12.54-1.285A7.486 7.486 0 0 1 12 15a7.486 7.486 0 0 1 5.855 2.812A8.224 8.224 0 0 1 12 20.25a8.224 8.224 0 0 1-5.855-2.438ZM15.75 9a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z"
                                clip-rule="evenodd" />
                        </svg>
                        <h1 class="h1">
                            Admin's Profile
                        </h1>
                    </div>
                    <div class="data-line">
                        <span>id:</span>
                        <p><?php echo $currentUser['id'] ?></p>
                    </div>
                    <a href="/digifine/logout" class="btn" style="margin-right: auto;margin-top:20px">Logout</a>
                <?php else: ?>
                    <div class="avatar-wrapper">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="avatar">
                            <path fill-rule="evenodd"
                                d="M18.685 19.097A9.723 9.723 0 0 0 21.75 12c0-5.385-4.365-9.75-9.75-9.75S2.25 6.615 2.25 12a9.723 9.723 0 0 0 3.065 7.097A9.716 9.716 0 0 0 12 21.75a9.716 9.716 0 0 0 6.685-2.653Zm-12.54-1.285A7.486 7.486 0 0 1 12 15a7.486 7.486 0 0 1 5.855 2.812A8.224 8.224 0 0 1 12 20.25a8.224 8.224 0 0 1-5.855-2.438ZM15.75 9a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z"
                                clip-rule="evenodd" />
                        </svg>
                        <h1 class="h1">
                            <?php echo $user['fname'] . " " . $user['lname'] ?>'s Profile
                        </h1>
                    </div>

                    <div class="data-line">
                        <span>Email:</span>
                        <p><?php echo $user['email'] ?></p>
                    </div>
                    <div class="data-line">
                        <span>Phone No:</span>
                        <p><?php echo $user['phone_no'] ?></p>

                    </div>
                    <div class="data-line">
                        <span>NIC:</span>
                        <p><?php echo $user['nic'] ?></p>
                    </div>
                    <?php if ($asPolice): ?>
                        <div class="data-line">
                            <span>Police ID:</span>
                            <p><?php echo $userid ?></p>
                        </div>
                        <div class="data-line">
                            <span>Police Station:</span>
                            <p><?php echo $policeStation['name'] . ", " . $PROVINCES[$policeStation['province']] . ' Province' ?>
                            </p>
                        </div>
                    <?php else: ?>
                        <div class="data-line">
                            <span>Driver Licence ID:</span>
                            <p style="text-transform: uppercase;"><?php echo $userid ?></p>
                        </div>
                    <?php endif; ?>
                    <div class="wrapper">
                        <?php if (!$asPolice): // Check if the user is not an officer 
                        ?>
                            <a href="/digifine/dashboard/profile/update/index.php" class="btn"
                                style="margin-right: 10px;margin-top:20px">Edit Profile</a>
                        <?php endif; ?>
                        <a href="/digifine/logout" class="btn" style="margin-right: 10px;margin-top:20px">Logout</a>
                    </div>
                <?php endif; ?>

            </div>
        </div>
    </div>
</main>

<?php include_once "../../includes/footer.php" ?>