<?php
require_once "../../../db/connect.php";

session_start();

$currentUser = $_SESSION['user'];
$asPolice = $currentUser['role'] === 'officer';
$userid = $currentUser['id'];

// if ($asPolice) {
//     $sql = "SELECT * FROM update_officer_profile_requests WHERE id = '$userid'";
// } else {
//     $sql = "SELECT * FROM update_driver_profile_requests WHERE id = '$userid'";
// }
// $result = $conn->query($sql);
// if (!$result) {
//     die("Error: " . $conn->error);
// }

// if ($result->num_rows > 0) {
//     die("You have already requested to update your profile. Please wait until the request is approved.");
// }

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

// fetch police stations info
$policeStations = [];
if ($asPolice) {
    $sql = "SELECT id,name,address,created_at FROM police_stations";
    $result = $conn->query($sql);
    if (!$result) {
        die("Error: " . $conn->error);
    }

    while ($station = $result->fetch_assoc()) {
        $policeStations[] = $station;
    }
}
?>

<?php
$pageConfig = [
    'title' => 'Driver Dashboard',
    'styles' => ["../../dashboard.css"],
    'scripts' => ["../../dashboard.js"],
    'authRequired' => true
];

include_once "../../../includes/header.php";
?>


<main>
    <?php include_once "../../includes/navbar.php" ?>
    <div class="dashboard-layout">
        <?php include_once "../../includes/sidebar.php" ?>
        <div class="content">
            <form action="update_process.php" method="post" class="container">
                <button onclick="history.back()" class="back-btn" style="position: absolute; top: 7px; right: 8px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        viewBox="0 0 16 16">
                        <path fill-rule="evenodd"
                            d="M15 8a.5.5 0 0 1-.5.5H3.707l3.147 3.146a.5.5 0 0 1-.708.708l-4-4a.5.5 0 0 1 0-.708l4-4a.5.5 0 1 1 .708.708L3.707 7.5H14.5a.5.5 0 0 1 .5.5z" />
                    </svg>
                </button>
                <h1 class="h1">
                    Update Profile
                </h1>
                <div class="field">
                    <label for="fname">First Name:<span style="color: red;">*</span> </label>
                    <input type="text" value="<?php echo $user['fname'] ?>" id="fname" name="fname" required
                        class="input" placeholder="<?php echo $user['fname'] ?>" readonly>
                </div>
                <div class="field">
                    <label for="fname">Last Name:<span style="color: red;">*</span> </label>
                    <input type="text" value="<?php echo $user['lname'] ?>" id="lname" name="lname" required
                        class="input" placeholder="<?php echo $user['lname'] ?>" readonly>
                </div>
                <div class="field">
                    <label for="email">Email:<span style="color: red;">*</span></label>
                    <input type="email" id="email" name="email" value="<?php echo $user['email'] ?>"
                        placeholder="<?php echo $user['email'] ?>" required class="input">
                </div>
                <div class="field">
                    <label for="phoneno">Phone No:<span style="color: red;">*</span></label>
                    <input type="tel" id="phoneno" pattern="\d{10}" name="phoneno"
                        value="<?php echo $user['phone_no'] ?>" placeholder="<?php echo $user['phone_no'] ?>" required
                        class="input">
                </div>
                <div class="field">
                    <label for="nic">NIC:<span style="color: red;">*</span></label>
                    <input type="text" id="nic" name="nic" value="<?php echo $user['nic'] ?>"
                        placeholder="<?php echo $user['nic'] ?>" required class="input" readonly>
                </div>
                <?php if ($asPolice): ?>
                    <div class="field">
                        <label for="policestation">Police Station:<span style="color: red;">*</span></label>
                        <select type="text" id="policestation" name="policestation"
                            value="<?php echo $user['police_station'] ?>" required class="input">
                            <?php foreach ($policeStations as $station): ?>
                                <option <?php echo ($user['police_station'] === $station['id'] ? 'selected' : '') ?>
                                    value="<?php echo $station['id'] ?>"><?php echo $station['name'] ?>,
                                    <?php echo $station['address'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                <?php endif; ?>
                <small style="margin-bottom: 5px;color:var(--color-gray)">Change password if required!</small>
                <div class="field">
                    <label for="password">New Password: </label>
                    <input type="password" id="password" minlength="6" name="password" class="input">
                </div>
                <p>
                    <!-- <small>Lorem ipsum dolor sit amet, consectetur adipisicing elit.</small> -->
                </p>
                <button class="btn" style="margin-right: auto;margin-top:20px">Create Update Profile Request</a>
        </div>
    </div>
    </div>
</main>

<?php include_once "../../../includes/footer.php" ?>