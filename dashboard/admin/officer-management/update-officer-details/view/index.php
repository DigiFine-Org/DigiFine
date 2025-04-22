<?php
$pageConfig = [
    'title' => 'Update Police Officer Details',
    'styles' => ["../../../../dashboard.css"],
    'scripts' => ["../../../../dashboard.js"],
    'authRequired' => true
];

include_once "../../../../../includes/header.php";
require_once "../../../../../db/connect.php";

if ($_SESSION['user']['role'] !== 'admin') {
    die("unauthorized user!");
}

$officerId = $_GET['id'] ?? null;
if (!$officerId) {
    die("No officer ID provided.");
}

// Get officer details
$stmt = $conn->prepare("SELECT * FROM officers WHERE id = ?");
$stmt->bind_param("i", $officerId);
$stmt->execute();
$result = $stmt->get_result();
$officer = $result->fetch_assoc();
$stmt->close();

// Get list of police stations
$stationsResult = $conn->query("SELECT id, name FROM police_stations");
$stations = $stationsResult->fetch_all(MYSQLI_ASSOC);

?>

<main>
    <?php include_once "../../../../includes/navbar.php" ?>

    <div class="dashboard-layout">
        <?php include_once "../../../../includes/sidebar.php" ?>
        <div class="content">
            <button onclick="history.back()" class="back-btn" style="position: absolute; top: 7px; right: 8px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                    <path fill-rule="evenodd"
                        d="M15 8a.5.5 0 0 1-.5.5H3.707l3.147 3.146a.5.5 0 0 1-.708.708l-4-4a.5.5 0 0 1 0-.708l4-4a.5.5 0 1 1 .708.708L3.707 7.5H14.5a.5.5 0 0 1 .5.5z" />
                </svg>
            </button>
            <div class="">
                <h2>Edit Police Officer</h2>
                <form method="POST" action="update.php">
                    <input type="hidden" name="id" value="<?= $officer['id'] ?>">

                    <div class="field">
                        <label>First Name</label>
                        <input type="text" name="fname" value="<?= $officer['fname'] ?>" class="input" required>
                    </div>

                    <div class="field">
                        <label>Last Name</label>
                        <input type="text" name="lname" value="<?= $officer['lname'] ?>" class="input" required>
                    </div>

                    <div class="field">
                        <label>Phone Number</label>
                        <input type="text" name="phone_no" value="<?= $officer['phone_no'] ?>" class="input" required>
                    </div>

                    <div class="field">
                        <label>Email</label>
                        <input type="email" name="email" value="<?= $officer['email'] ?>" class="input" required>
                    </div>

                    <div class="field">
                        <label>NIC</label>
                        <input type="text" name="nic" value="<?= $officer['nic'] ?>" class="input" required>
                    </div>

                    <div class="field">
                        <label>Police Station</label>
                        <select name="police_station" class="input">
                            <?php foreach ($stations as $station): ?>
                                <option value="<?= $station['id'] ?>" <?= ($officer['police_station'] == $station['id']) ? 'selected' : '' ?>>
                                    <?= $station['name'] ?>
                                </option>
                            <?php endforeach ?>
                        </select>
                    </div>

                    <button class="btn" type="submit" style="margin-top: 20px;">Update Officer</button>
                </form>

            </div>
        </div>
    </div>
</main>

<?php include_once "../../../../../includes/footer.php"; ?>