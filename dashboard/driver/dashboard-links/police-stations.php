<?php

$pageConfig = [
    'title' => 'Police Stations',
    'styles' => [
        "../../dashboard.css?v=" . time(),
        "../driver-dashboard.css?v=" . time()
    ],
    'scripts' => [
        "../dashboard.js?v=" . time()
    ],
];
include_once "../../../includes/header.php";

?>

<style>
    /* Popup container styling */
    .popup1 {
        display: none; /* Default state */
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        z-index: 1000;
        width: 300px;
        padding: 20px;
        background-color: #fff;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        border-radius: 10px;
        text-align: center;
    }

    .popup1 h2 {
        margin-bottom: 15px;
    }

    .popup1 ul {
        list-style: none;
        padding: 0;
    }

    .popup1 ul li {
        margin: 5px 0;
    }

    .popup1 .close-btn {
        margin-top: 15px;
        padding: 10px 20px;
        background-color: #007BFF;
        color: #fff;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    .popup1 .close-btn:hover {
        background-color: #0056b3;
    }

    /* Overlay styling */
    .popup1-overlay {
        display: none; /* Default state */
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 999;
    }

    .tile {
        cursor: pointer;
    }
</style>

<main>
    <?php include_once "../../includes/navbar.php" ?>
    <div class="dashboard-layout">
        <?php include_once "../../includes/sidebar.php" ?>
        <div class="content">
            <h1>Police Stations</h1>
            <div class="navigation-tile-grid">
                <div class="tile" onclick="showPopup('western')">
                    <span>Western Province Police Stations</span>
                </div>
                <div class="tile" onclick="showPopup('central')">
                    <span>Central Province Police Stations</span>
                </div>
                <div class="tile" onclick="showPopup('uva')">
                    <span>Uva Province Police Stations</span>
                </div>
                <div class="tile" onclick="showPopup('sabaragamuwa')">
                    <span>Sabaragamuwa Province Police Stations</span>
                </div>
                <div class="tile" onclick="showPopup('northWestern')">
                    <span>North Western Province Police Stations</span>
                </div>
                <div class="tile" onclick="showPopup('northCentral')">
                    <span>North Central Province Police Stations</span>
                </div>
                <div class="tile" onclick="showPopup('northern')">
                    <span>Northern Province Police Stations</span>
                </div>
                <div class="tile" onclick="showPopup('eastern')">
                    <span>Eastern Province Police Stations</span>
                </div>
            </div>
        </div>
    </div>
</main>

<div class="popup1-overlay" id="popupOverlay" onclick="closePopup()"></div>

<div class="popup1" id="popup1">
    <h2 id="popupTitle">Cities</h2>
    <ul id="popupContent">
    <div style="width:10px; background-color:red; height:100px">sewe</div>
    </ul>
    <button class="close-btn" onclick="closePopup()">Close</button>
</div>

<script>
    const provinceCities = {
        western: ["Colombo", "Negombo", "Gampaha", "Kalutara", "Panadura"],
        central: ["Kandy", "Nuwara Eliya", "Matale", "No cities available"],
        uva: ["Badulla", "Bandarawela", "Ella", "Mahiyanganaya", "Wellawaya"],
        sabaragamuwa: ["Ratnapura", "Balangoda", "Kegalle", "No cities available"],
        northWestern: ["Kurunegala", "Kuliyapitiya", "Nikaweratiya", "Puttalam", "Chilaw"],
        northCentral: ["Anuradhapura", "Polonnaruwa", "No cities available"],
        northern: ["Jaffna", "Kilinochchi", "Mannar", "Vavuniya", "Mulaitivu"],
        eastern: ["Trincomalee", "Batticaloa", "Ampara", "No cities available"]
    };

    function showPopup(province) {
        const cities = provinceCities[province] || ["No cities available"];
        const popupContent = document.getElementById('popupContent');
        popupContent.innerHTML = ""; // Clear previous content

        cities.forEach(city => {
            const listItem = document.createElement('li');
            listItem.textContent = city;
            popupContent.appendChild(listItem);
        });

        const popupOverlay = document.getElementById('popupOverlay');
        const popup1 = document.getElementById('popup1');

        // Set the display to block to show the popup1 and overlay
        popupOverlay.style.display = 'block';
        popup1.style.display = 'block';
        console.log(popupContent);
    }


    function closePopup() {
        const popupOverlay = document.getElementById('popupOverlay');
        const popup1 = document.getElementById('popup1');

        // Set display to none to hide the popup1 and overlay
        popupOverlay.style.display = 'none';
        popup1.style.display = 'none';
    }
</script>

<?php include_once "../../../includes/footer.php" ?>