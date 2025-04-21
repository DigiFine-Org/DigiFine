<?php
$pageConfig = [
    'title' => 'Dashboard',
    'styles' => ["../dashboard.css", "./driver-dashboard.css"],
    'scripts' => ["../dashboard.js"],
    'authRequired' => true
];

include_once "../../includes/header.php";
require_once "../../db/connect.php";

if ($_SESSION['user']['role'] !== 'driver') {
    die("unauthorized user!");
}

$driverId = $_SESSION['user']['id'] ?? null;

if (!$driverId) {
    die("Unauthorized access.");
}

// Fetch driver's details (Name and Points)
// This already correctly fetches the driver's points from the database
$sql = "SELECT fname, lname, points FROM drivers WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $driverId);

if (!$stmt->execute()) {
    die("Error fetching driver details: " . $stmt->error);
}

$result = $stmt->get_result();
if ($result->num_rows === 0) {
    die("Driver not found.");
}

$driver = $result->fetch_assoc();
$stmt->close();

// Fetch Active Fines Count - Updated to exclude discarded fines
$sql = "SELECT COUNT(*) AS active_fines FROM fines 
        WHERE driver_id = ? 
        AND (fine_status = 'pending' OR fine_status = 'overdue') 
        AND is_reported = 0 
        AND is_discarded = 0";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $driverId);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$active_fines = $row['active_fines'] ?? 0; 

$stmt->close();

// Fetch Reported Fines Count - Only count fines that are reported but not yet discarded
$sql = "SELECT COUNT(*) AS reported_fines FROM fines 
        WHERE driver_id = ?  
        AND is_reported = 1 
        AND is_discarded = 0";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $driverId);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$reported_fines = $row['reported_fines'] ?? 0; 

$stmt->close();

// Fetch Cleared Fines Count - Include both paid and discarded fines
$sql = "SELECT COUNT(*) AS cleared_fines FROM fines 
        WHERE driver_id = ?  
        AND (fine_status = 'paid')";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $driverId);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$cleared_fines = $row['cleared_fines'] ?? 0; 

$stmt->close();
$conn->close();
?>

<main>
    <?php include_once "../includes/navbar.php" ?>
    <div class="dashboard-layout">
        <?php include_once "../includes/sidebar.php" ?>
        <div class="content">
        <button onclick="history.back()" class="back-btn" style="position: absolute; top: 7px; right: 8px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                    <path fill-rule="evenodd"
                        d="M15 8a.5.5 0 0 1-.5.5H3.707l3.147 3.146a.5.5 0 0 1-.708.708l-4-4a.5.5 0 0 1 0-.708l4-4a.5.5 0 1 1 .708.708L3.707 7.5H14.5a.5.5 0 0 1 .5.5z" />
                </svg>
            </button>           
            <h2>Welcome Driver <?= htmlspecialchars($driver['fname'] . ' ' . $driver['lname']) ?>!</h2>
            <p class="subtitle">A responsible driver is a true road hero.</p>
            
            <!-- Stats Cards -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon" style="background-color: #FFEFB4;">
                        <i class="fas fa-star"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?= htmlspecialchars($driver['points']) ?></h3>
                        <p>Driving Points</p>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon" style="background-color: #CDE4FF;">
                        <i class="fas fa-exclamation-circle"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?= $active_fines; ?></h3>
                        <p>Active Fines</p>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon" style="background-color: #F8C8D8;">
                        <i class="fas fa-flag"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?= $reported_fines; ?></h3>
                        <p>Reported Fines</p>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon" style="background-color: #D5F2EA;">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?= $cleared_fines; ?></h3>
                        <p>Cleared Fines</p>
                    </div>
                </div>
            </div>
            
            <!-- Feature Tiles -->
            <div class="feature-tiles">
            <a href="/digifine/dashboard/driver/dashboard-links/emergency-services.php" class="feature-tile">
            <div class="tile-full">
                        <div class="tile-content">
                            <h3>Contact The Emergency Services</h3>
                            <p>20+ Emergency contacts at your fingertip</p>
                        </div>
                        <div>
                            <img src="../../assets/emergency.jpg" alt="Digi-Fine Logo" class="about-image">   
                        </div>                   
                    </div>                    
                </a>
                
                <a href="/digifine/dashboard/driver/dashboard-links/remaining-points.php" class="feature-tile">
                    <div class="tile-full">
                        <div class="tile-content">
                            <h3>Check Your Remaining Points</h3>
                            <p>Check Your Remaining Points</p>
                        </div>
                        <div>
                            <img src="../../assets/points.jpg" alt="Digi-Fine Logo" class="about-image">   
                        </div>
                    </div>
                </a>

                <a href="/digifine/dashboard/driver/dashboard-links/tips-for-drivers.php" class="feature-tile">
                    <div class="tile-full">
                        <div class="tile-content">
                            <h3>Smart Driving Tips</h3>
                            <p>Drive safe. Drive smart</p>
                        </div>
                        <div>
                            <img src="../../assets/tips.jpg" alt="Digi-Fine Logo" class="about-image">   
                        </div>
                    </div> 
                </a>

                
                
                <a href="/digifine/dashboard/driver/dashboard-links/tell-igp.php" class="feature-tile">
                    <div class="tile-full">
                        <div class="tile-content">
                            <h3>Tell IGP Service</h3>
                            <p>Contact IGP for urgent & unfair time</p>
                        </div>
                        <div>
                            <img src="../../assets/traffic (2).jpg" alt="Digi-Fine Logo" class="about-image">   
                        </div>
                    </div>                
                </a>

                <a href="/digifine/dashboard/driver/dashboard-links/traffic-signs.php" class="feature-tile">
                    <div class="tile-full">
                        <div class="tile-content">
                            <h3>Traffic Signs</h3>
                            <p>Learn more about traffic signs </p>
                        </div>
                        <div>
                            <img src="../../assets/signs.jpg" alt="Digi-Fine Logo" class="about-image">   
                        </div>                    
                    </div>                    
                </a>

                <a href="/digifine/dashboard/driver/dashboard-links/police-stations.php" class="feature-tile">
                    <div class="tile-full">
                        <div class="tile-content">
                            <h3>Police Stations</h3>
                            <p>Contact any Police Stations</p>
                        </div>
                        <div>
                            <img src="../../assets/police-stations.jpg" alt="Digi-Fine Logo" class="about-image">   
                        </div>                    
                    </div>                   
                </a>
            
        </div>
    </div>
</main>



<style>

.tile-full{
    display: flex;
  flex-direction: row; 
  gap: 10px; 
  justify-content:space-between;
}
 .tile-content{
    display:flex;
    flex-direction: column;  

 }

.about-image {
    width: 90px; 
    height:90px;
}
   
.stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
    margin-bottom: 30px;
}

.stat-card {
    background: white;
    border-radius: 12px;
    padding: 20px;
    display: flex;
    align-items: center;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.stat-icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 15px;
    font-size: 20px;
    color: #333;
}
.stat-info h3 {
    margin: 0;
    font-size: 24px;
    color: #333;
}

.stat-info p {
    margin: 5px 0 0;
    color: #666;
    font-size: 14px;
}


/* Responsive adjustments */
@media (max-width: 768px) {
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .about-image, .description {
        order: initial; /* Reset order for mobile */
    }

}


/* Feature Tiles */
.feature-tiles {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
    margin-bottom: 30px;
}

.feature-tile {
    background: white;
    border-radius: 12px;
    padding: 25px;
    text-decoration: none;
    color: inherit;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    transition: transform 0.3s;
}

.feature-tile:hover {
    transform: translateY(-5px);
}



.feature-tile h3 {
    margin: 0 0 10px;
    color: #003366;
}

.feature-tile p {
    margin: 0;
    color: #666;
    line-height: 1.5;
}


/* Responsive */
@media (max-width: 768px) {

    .feature-tiles {
        grid-template-columns: 1fr;
    }
}
</style>


<?php include_once "../../includes/footer.php" ?>