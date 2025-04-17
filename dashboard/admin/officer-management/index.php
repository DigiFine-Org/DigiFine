<?php
$pageConfig = [
    'title' => 'Register Police Officer',
    'styles' => ["../../dashboard.css"],
    'scripts' => ["../../dashboard.js"],
    'authRequired' => true
];

require_once "../../../db/connect.php";
include_once "../../../includes/header.php";
require_once "../../../constants.php";

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

?>

<main>
    <?php include_once "../../includes/navbar.php" ?>

    <div class="dashboard-layout">
        <?php include_once "../../includes/sidebar.php" ?>
        <div class="content">
            <div class="container-large">
            <h2 style="margin-bottom: 10px;">Register Police Officer</h2>
                <form action="register-officer-process.php" method="POST">
                    <div class="field">
                        <label for="fname">First Name:<span style="color: red;">*</span> </label>
                        <input type="text" id="fname" name="fname" required class="input" placeholder="John">
                    </div>
                    <div class="field">
                        <label for="fname">Last Name:<span style="color: red;">*</span> </label>
                        <input type="text" id="lname" name="lname" required class="input" placeholder="Doe">
                    </div>

                    <div class="field">
                        <label for="email">Email:<span style="color: red;">*</span> </label>
                        <input type="email" id="email" name="email" required class="input" placeholder="johndoe@example.com">
                    </div>
                    <div class="field">
                        <label for="nic">NIC:<span style="color: red;">*</span> </label>
                        <input type="text" id="nic" name="nic" required class="input" placeholder="1122334455V">
                    </div>
                    <div class="field">
                        <label for="userid">Police ID:<span style="color: red;">*</span> </label>
                        <input type="text" pattern="\d{5}" id="userid" name="userid" class="input" placeholder="44332">
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
                            pattern="\d{10}">
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

<?php include_once "../../../includes/footer.php"; ?>