<?php
$pageConfig = [
    'title' => 'Police Stations',
    'styles' => [
        "../../dashboard.css?v=" . time(),
<<<<<<< HEAD
        "./popup.css?v=" . time(),

        "../driver-dashboard.css?v=" . time()
    ],
];
include_once "../../../includes/header.php";
?>

=======
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
>>>>>>> c2e6a350219c3bb0ba715390fc7af16602c190d5

<main>
    <?php include_once "../../includes/navbar.php"; ?>
    <div class="dashboard-layout">
        <?php include_once "../../includes/sidebar.php"; ?>
        <div class="content">
            <h1>Police Stations</h1>
            <div class="navigation-tile-grid">
<<<<<<< HEAD
                <div class="tile" onclick="showPopup(1)">Western Province Police Stations</div>
                <div class="tile" onclick="showPopup(2)">Central Province Police Stations</div>
                <div class="tile" onclick="showPopup(3)">Southern Province Police Stations</div>
                <div class="tile" onclick="showPopup(4)">Uva Province Police Stations</div>
                <div class="tile" onclick="showPopup(5)">Sabaragamuwa Province Police Stations</div>
                <div class="tile" onclick="showPopup(6)">North Western Province Police Stations</div>
                <div class="tile" onclick="showPopup(7)">North Central Province Police Stations</div>
                <div class="tile" onclick="showPopup(8)">Northern Province Police Stations</div>
                <div class="tile" onclick="showPopup(9)">Eastern Province Police Stations</div>
=======
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
>>>>>>> c2e6a350219c3bb0ba715390fc7af16602c190d5
            </div>
        </div>
    </div>
</main>

<<<<<<< HEAD
<div class="popup-new-overlay" id="popupOverlay" onclick="closePopup()"></div>

<div class="popup-new" id="popup-new">
    <h2 id="popupTitle">Police Stations</h2>
    <div class="search-bar">
        <input 
            type="text" 
            id="searchBar" 
            placeholder="Search for a station..." 
            oninput="filterStations()">
    </div>
    <ul id="popupContent"></ul>
=======
<div class="popup1-overlay" id="popupOverlay" onclick="closePopup()"></div>

<div class="popup1" id="popup1">
    <h2 id="popupTitle">Cities</h2>
    <ul id="popupContent">
    <div style="width:10px; background-color:red; height:100px">sewe</div>
    </ul>
>>>>>>> c2e6a350219c3bb0ba715390fc7af16602c190d5
    <button class="close-btn" onclick="closePopup()">Close</button>
</div>

<script>
<<<<<<< HEAD
let policeStations = [];

function showPopup(provinceId) {
    const popupContent = document.getElementById('popupContent');
    popupContent.innerHTML = "<li>Loading...</li>";

    fetch(`./fetch-policeStations.php?province=${provinceId}`)
        .then(response => response.json())
        .then(data => {
            policeStations = data; // Save data for filtering
            popupContent.innerHTML = ""; // Clear loading text
            if (data.error) {
                popupContent.innerHTML = `<li>${data.error}</li>`;
            } else if (data.length === 0) {
                popupContent.innerHTML = "<li>No police stations available</li>";
            } else {
                renderStations(data);
            }
        })
        .catch(error => {
            console.error("Error fetching police stations:", error);
            popupContent.innerHTML = "<li>Error fetching data</li>";
        });

    document.getElementById('popupOverlay').style.display = 'block';
    document.getElementById('popup-new').style.display = 'block';
}

function renderStations(stations) {
    const popupContent = document.getElementById('popupContent');
    popupContent.innerHTML = ""; // Clear content
    stations.forEach(station => {
        const listItem = document.createElement('li');
        listItem.innerHTML = `<strong>${station.name}</strong><br>Tel: ${station.telephone}`;
        popupContent.appendChild(listItem);
    });
}

function filterStations() {
    const searchTerm = document.getElementById('searchBar').value.toLowerCase();
    const filteredStations = policeStations.filter(station =>
        station.name.toLowerCase().includes(searchTerm)
    );
    renderStations(filteredStations);
}

function closePopup() {
    document.getElementById('popupOverlay').style.display = 'none';
    document.getElementById('popup-new').style.display = 'none';
}
</script>

<?php include_once "../../../includes/footer.php"; ?>
=======
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
>>>>>>> c2e6a350219c3bb0ba715390fc7af16602c190d5
