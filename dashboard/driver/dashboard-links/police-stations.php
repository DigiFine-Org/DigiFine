<?php
$pageConfig = [
    'title' => 'Police Stations',
    'styles' => ["../../dashboard.css","popup.css","../driver-dashboard.css"]];
include_once "../../../includes/header.php";
?>


<main>
    <?php include_once "../../includes/navbar.php"; ?>
    <div class="dashboard-layout">
        <?php include_once "../../includes/sidebar.php"; ?>
        <div class="content">
            <button onclick="history.back()" class="back-btn" style="position: absolute; top: 7px; right: 8px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                    <path fill-rule="evenodd"
                        d="M15 8a.5.5 0 0 1-.5.5H3.707l3.147 3.146a.5.5 0 0 1-.708.708l-4-4a.5.5 0 0 1 0-.708l4-4a.5.5 0 1 1 .708.708L3.707 7.5H14.5a.5.5 0 0 1 .5.5z" />
                </svg>
            </button>
            <h1>Police Stations</h1>
            <div class="description-section">
                <div class="english">
                    <h3>Find Police Stations in Your Province</h2>
                        <p>This directory provides contact information for all police stations across Sri Lanka's
                            provinces.
                            Click on your province to view the complete list of police stations with their telephone
                            numbers
                            and locations. You can search for specific stations using the search box in the popup
                            window.</p>
                </div>

                <div class="sinhala">
                    <h3>ඔබගේ පළාතේ පොලිස් ස්ථාන සොයා ගන්න</h2>
                        <p>මෙම නාමාවලිය ශ්‍රී ලංකාවේ සියලුම පළාත්වල පොලිස් ස්ථානවල සම්බන්ධතා තොරතුරු ලබා දෙයි.
                            ඔබගේ පළාත තෝරන්න එම පළාතේ පොලිස් ස්ථානවල දුරකථන අංක සහ ස්ථාන පිළිබඳ සම්පූර්ණ ලැයිස්තුව
                            බැලීමට. පොප් අප් වින්ඩෝවේ ඇති සෙවුම් කොටුව භාවිතයෙන් නිශ්චිත පොලිස් ස්ථාන සොයා ගත හැකිය.</p>
                </div>
            </div>
            <div class="navigation-tileNew-grid">
                <div class="tile-container" onclick="showPopup(1)">
                    <div class="tileNew" style="background-image: url('../../../assets/Western_Province.png')"></div>
                    <div class="province-name">Western Province Police Stations</div>
                </div>
                <div class="tile-container" onclick="showPopup(2)">
                    <div class="tileNew" style="background-image: url('../../../assets/Central_Province.png')"></div>
                    <div class="province-name">Central Province Police Stations</div>
                </div>
                <div class="tile-container" onclick="showPopup(3)">
                    <div class="tileNew" style="background-image: url('../../../assets/Southern_Province.png')"></div>
                    <div class="province-name">Southern Province Police Stations</div>
                </div>
                <div class="tile-container" onclick="showPopup(4)">
                    <div class="tileNew" style="background-image: url('../../../assets/Uva_Province.png')"></div>
                    <div class="province-name">Uva Province Police Stations</div>
                </div>
                <div class="tile-container" onclick="showPopup(5)">
                    <div class="tileNew" style="background-image: url('../../../assets/Sabaragamuwa_Province.png')">
                    </div>
                    <div class="province-name">Sabaragamuwa Province Police Stations</div>
                </div>
                <div class="tile-container" onclick="showPopup(6)">
                    <div class="tileNew" style="background-image: url('../../../assets/North_Western_Province.png')">
                    </div>
                    <div class="province-name">North Western Province Police Stations</div>
                </div>
                <div class="tile-container" onclick="showPopup(7)">
                    <div class="tileNew" style="background-image: url('../../../assets/North_Central_Province.png')">
                    </div>
                    <div class="province-name">North Central Province Police Stations</div>
                </div>
                <div class="tile-container" onclick="showPopup(8)">
                    <div class="tileNew" style="background-image: url('../../../assets/Northern_Province..png')"></div>
                    <div class="province-name">Northern Province Police Stations</div>
                </div>
                <div class="tile-container" onclick="showPopup(9)">
                    <div class="tileNew" style="background-image: url('../../../assets/Eastern_Province_Flag.png')">
                    </div>
                    <div class="province-name">Eastern Province Police Stations</div>
                </div>
            </div>

        </div>
    </div>

</main>



<div class="popup-new-overlay" id="popupOverlay" onclick="closePopup()"></div>

<div class="popup-new" id="popup-new">
    <h2 id="popupTitle">Police Stations</h2>
    <div class="search-bar">
        <input type="text" id="searchBar" placeholder="Search for a station..." oninput="filterStations()">
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