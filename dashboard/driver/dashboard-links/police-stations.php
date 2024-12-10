<?php
$pageConfig = [
    'title' => 'Police Stations',
    'styles' => [
        "../../dashboard.css?v=" . time(),
        "./popup.css?v=" . time(),

        "../driver-dashboard.css?v=" . time()
    ],
];
include_once "../../../includes/header.php";
?>


<main>
    <?php include_once "../../includes/navbar.php"; ?>
    <div class="dashboard-layout">
        <?php include_once "../../includes/sidebar.php"; ?>
        <div class="content">
            <h1>Police Stations</h1>
            <div class="navigation-tile-grid">
                <div class="tile" onclick="showPopup(1)">Western Province Police Stations</div>
                <div class="tile" onclick="showPopup(2)">Central Province Police Stations</div>
                <div class="tile" onclick="showPopup(3)">Southern Province Police Stations</div>
                <div class="tile" onclick="showPopup(4)">Uva Province Police Stations</div>
                <div class="tile" onclick="showPopup(5)">Sabaragamuwa Province Police Stations</div>
                <div class="tile" onclick="showPopup(6)">North Western Province Police Stations</div>
                <div class="tile" onclick="showPopup(7)">North Central Province Police Stations</div>
                <div class="tile" onclick="showPopup(8)">Northern Province Police Stations</div>
                <div class="tile" onclick="showPopup(9)">Eastern Province Police Stations</div>
            </div>
        </div>
    </div>
</main>

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
    <button class="close-btn" onclick="closePopup()">Close</button>
</div>

<script>
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
