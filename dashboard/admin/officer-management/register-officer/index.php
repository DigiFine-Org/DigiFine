<?php
$pageConfig = [
    'title' => 'Register Police Officer',
    'styles' => ["../../../dashboard.css"],
    'scripts' => ["../../../dashboard.js"],
    'authRequired' => true
];

require_once "../../../../db/connect.php";
include_once "../../../../includes/header.php";
require_once "../../../../constants.php";


if ($_SESSION['user']['role'] !== 'admin') {
    die("unauthorized user!");
}


$provinces = $PROVINCES;

$policeStationStmt = $conn->prepare("SELECT * FROM police_stations");
if (!$policeStationStmt) {
    die("policeStationStmt Error: " . $conn->error);
}

if (!$policeStationStmt->execute()) {
    die("policeStationStmt Error: " . $policeStationStmt->error);
}

$result = $policeStationStmt->get_result();
if (!$result) {
    die("no results!!! " . $conn->error);
}

$policeStations = $result->fetch_all(MYSQLI_ASSOC);

//alerts
if ($_SESSION['message'] ?? null) {
    if ($_SESSION['message'] === 'success') {
        $message = "Officer registered successfully!";
        unset($_SESSION['message']); // Clear the session message
        include '../../../../includes/alerts/success.php';
    } else {
        $message = $_SESSION['message']; // Store the message
        unset($_SESSION['message']); // Clear the session message
        include '../../../../includes/alerts/failed.php';
    }
}

?>

<main>
    <?php include_once "../../../includes/navbar.php" ?>

    <div class="dashboard-layout">
        <?php include_once "../../../includes/sidebar.php" ?>
        <div class="content">
            <button onclick="history.back()" class="back-btn" style="position: absolute; top: 7px; right: 8px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                    <path fill-rule="evenodd"
                        d="M15 8a.5.5 0 0 1-.5.5H3.707l3.147 3.146a.5.5 0 0 1-.708.708l-4-4a.5.5 0 0 1 0-.708l4-4a.5.5 0 1 1 .708.708L3.707 7.5H14.5a.5.5 0 0 1 .5.5z" />
                </svg>
            </button>
            <div class="container-large">

                <h2 style="margin-bottom: 10px;">Register Police Officer</h2>
                <form action="register-officer-process.php" method="POST">
                    <div class="field">
                        <label for="fname">First Name:<span style="color: red;">*</span> </label>
                        <input type="text" id="fname" name="fname" required class="input" placeholder="Imalsha"
                            pattern="[A-Za-z]+" title="First name should only contain letters.">
                    </div>
                    <div class="field">
                        <label for="fname">Last Name:<span style="color: red;">*</span> </label>
                        <input type="text" id="lname" name="lname" required class="input" placeholder="Jathunarachchi"
                            pattern="[A-Za-z]+" title="Last name should only contain letters.">
                    </div>

                    <div class="field">
                        <label for="email">Email:<span style="color: red;">*</span> </label>
                        <input type="email" id="email" name="email" required class="input"
                            placeholder="imaz@example.com">
                    </div>
                    <div class="field">
                        <label for="nic">NIC:<span style="color: red;">*</span> </label>
                        <input type="text" id="nic" name="nic" required class="input"
                            placeholder="123456789V or 123456789012" pattern="^\d{9}[Vv]$|^\d{12}$"
                            title="NIC should be a 9-digit number followed by 'V' or 'v' (e.g., 911042754V), or a 12-digit number (e.g., 197419202757).">
                    </div>
                    <div class="field">
                        <label for="userid">Police ID:<span style="color: red;">*</span> </label>
                        <input type="text" pattern="\d{5}" id="userid" name="userid" class="input" placeholder="12345"
                            title="Police ID must be a 5-digit number" required>
                        <small style="margin-top: 5px;">You can't change this value once entered!</small>
                    </div>
                    <div class="field">
                        <label for="policestation">Province:<span style="color: red;">*</span> </label>
                        <select name="province" id="province" class="input">
                            <?php foreach ($provinces as $provinceId => $provinceName): ?>
                                <option value="<?php echo $provinceId ?>"><?php echo $provinceName . " Province" ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="field">
                        <label for="policestation">Police Station:<span style="color: red;">*</span> </label>
                        <select name="policestation" id="policestation" class="input">
                            <?php foreach ($policeStations as $station): ?>
                                <option value="<?php echo $station['id'] ?>"><?php echo $station['name'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="field">
                        <label for="phoneno">Phone No:<span style="color: red;">*</span> </label>
                        <input type="tel" id="phoneno" name="phoneno" required class="input" placeholder="0766743755"
                            pattern="\d{10}" title="Phone number should be a 10-digit number.">
                    </div>
                    <!-- <div class="field">
                        <label for="password">Password:<span style="color: red;">*</span> </label>
                        <input type="password" id="password" minlength="6" name="password" required class="input">
                    </div>
                    <div class="field">
                        <label for="cpassword">Confirm Passwords:<span style="color: red;">*</span> </label>
                        <input type="password" id="cpassword" name="cpassword" required class="input">
                    </div>
                    <input type="hidden" name="aspolice" value="true"> -->
                    <button type="submit" class="btn">Register Officer</button>
                </form>
            </div>
        </div>
    </div>
</main>

<script>
    const POLICE_STATIONS = <?php echo json_encode($policeStations) ?>;

    const provinceSelecter = document.getElementById('province');
    const policeStationSelecter = document.getElementById('policestation');

    const filterProvinces = (e) => {
        const provinceId = e ? e.target.value : policeStationSelecter.value;
        const currentPoliceStations = POLICE_STATIONS.filter(station => station.province == provinceId);
        policeStationSelecter.innerHTML = '';
        currentPoliceStations.forEach(station => {
            const option = document.createElement('option');
            option.value = station.id;
            option.innerText = station.name;
            policeStationSelecter.appendChild(option);
        });
    }

    provinceSelecter.addEventListener("change", (e) => {
        filterProvinces(e);
    })

    filterProvinces(null);
</script>

<?php include_once "../../../../includes/footer.php"; ?>