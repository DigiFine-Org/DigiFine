<?php
$pageConfig = [
    'title' => 'Driving Tips For Sri Lankan Roads',
    'styles' => ["../../dashboard.css", "./driver-dashboard.css"],
    'scripts' => ["../../dashboard.js"],
    'authRequired' => true
];

include_once "../../../includes/header.php";
require_once "../../../db/connect.php";

if ($_SESSION['user']['role'] !== 'driver') {
    die("unauthorized user!");
}

$driverId = $_SESSION['user']['id'] ?? null;

if (!$driverId) {
    die("Unauthorized access.");
}
?>

<main>
    <?php include_once "../../includes/navbar.php" ?>
    <div class="dashboard-layout">
        <?php include_once "../../includes/sidebar.php" ?>
        <div class="content">
            <button onclick="history.back()" class="back-btn" style="position: absolute; top: 7px; right: 8px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                    <path fill-rule="evenodd"
                        d="M15 8a.5.5 0 0 1-.5.5H3.707l3.147 3.146a.5.5 0 0 1-.708.708l-4-4a.5.5 0 0 1 0-.708l4-4a.5.5 0 1 1 .708.708L3.707 7.5H14.5a.5.5 0 0 1 .5.5z" />
                </svg>
            </button>
            <h2 class="page-title">12 Essential Driving Tips for Sri Lankan Roads</h2>
            <p class="page-subtitle">Master the unique challenges of driving in Sri Lanka with our expert advice</p>

            <div class="intro-box">
                <h3>Driving in Sri Lanka: What You Need to Know</h3>
                <p>Sri Lanka presents unique driving challenges with its bustling urban centers, narrow rural roads, and
                    diverse traffic conditions. According to the Sri Lanka Transport Board, over 200,000 new drivers
                    join our roads each year, but many struggle to adapt to real-world driving conditions.</p>
                <p>These expert tips will help you navigate Sri Lankan roads safely and confidently, whether you're a
                    new driver or looking to improve your skills.</p>
            </div>

            <div class="tips-row">
                <div class="card">
                    <div class="card-badge">1</div>
                    <div class="card-content">
                        <h4>Master Sri Lanka's Roundabout Rules</h4>
                        <p>Sri Lankan roundabouts can be challenging for new drivers. Always give way to vehicles
                            already in the roundabout and signal your intentions clearly. In Colombo's busy roundabouts
                            like Lipton Circus or Bambalapitiya Junction, be extra vigilant and maintain proper lane
                            discipline.</p>
                    </div>
                </div>

                <div class="card">
                    <div class="card-badge">2</div>
                    <div class="card-content">
                        <h4>Prepare for Monsoon Driving</h4>
                        <p>Sri Lanka's monsoon seasons bring heavy rainfall that creates hazardous driving conditions.
                            Ensure your vehicle has good quality tires with sufficient tread depth, functioning
                            windshield wipers, and working headlights. Reduce your speed during heavy rain and avoid
                            driving through flooded areas, particularly in low-lying regions of Colombo and coastal
                            areas.</p>
                    </div>
                </div>

                <div class="card">
                    <div class="card-badge">3</div>
                    <div class="card-content">
                        <h4>Navigate Urban Traffic Effectively</h4>
                        <p>In busy cities like Colombo, Kandy, and Galle, traffic congestion is common during peak
                            hours. Plan your journeys to avoid rush hours (7:30-9:30 AM and 4:30-7:00 PM). Use
                            navigation apps like PickMe or Google Maps that provide real-time traffic updates specific
                            to Sri Lankan roads.</p>
                    </div>
                </div>

                <div class="card">
                    <div class="card-badge">4</div>
                    <div class="card-content">
                        <h4>Be Cautious on Mountain Roads</h4>
                        <p>When driving in hill country areas like Nuwara Eliya, Ella, or Haputale, be prepared for
                            narrow, winding roads with limited visibility. Use your horn gently when approaching blind
                            corners, maintain a lower gear when descending steep roads, and be aware of potential
                            landslides during rainy seasons.</p>
                    </div>
                </div>
            </div>

            <div class="section-divider">
                <h3>Safe Driving Practices</h3>
                <p>Following these essential practices will help you navigate both urban and rural roads with
                    confidence.</p>
            </div>

            <div class="tips-row">
                <div class="card">
                    <div class="card-badge">5</div>
                    <div class="card-content">
                        <h4>Understand Local Driving Etiquette</h4>
                        <p>Sri Lankan drivers often use headlight flashes and horn signals to communicate. A quick flash
                            may indicate "I'm letting you go" or "I'm coming through" depending on context. Learn these
                            unwritten rules to better integrate with local traffic flow and reduce stress while driving.
                        </p>
                    </div>
                </div>

                <div class="card">
                    <div class="card-badge">6</div>
                    <div class="card-content">
                        <h4>Know Your Documents</h4>
                        <p>Always carry your valid Sri Lankan driving license, vehicle registration book (revenue
                            license), insurance certificate, and emission test certificate. Police checkpoints are
                            common, especially on weekends and holidays, and failure to produce these documents can
                            result in fines.</p>
                    </div>
                </div>

                <div class="card">
                    <div class="card-badge">7</div>
                    <div class="card-content">
                        <h4>Be Mindful of Three-wheelers and Motorcycles</h4>
                        <p>Three-wheelers (tuk-tuks) and motorcycles make up a significant portion of Sri Lankan traffic
                            and often maneuver unpredictably. Always check your blind spots, especially when changing
                            lanes or turning at intersections in urban areas.</p>
                    </div>
                </div>

                <div class="card">
                    <div class="card-badge">8</div>
                    <div class="card-content">
                        <h4>Navigate Highway Driving</h4>
                        <p>Sri Lanka's expressways (E01, E02, E03, E04) have specific rules including strict speed
                            limits (100 km/h for cars, 70 km/h for vans and small trucks). Always keep to the left
                            except when overtaking, and be aware that stopping on expressways is prohibited except in
                            emergencies.</p>
                    </div>
                </div>
            </div>

            <div class="section-divider">
                <h3>Special Considerations</h3>
                <p>These additional tips will help you handle unique situations on Sri Lankan roads.</p>
            </div>

            <div class="tips-row">
                <div class="card">
                    <div class="card-badge">9</div>
                    <div class="card-content">
                        <h4>Watch for Wildlife</h4>
                        <p>When driving through areas near national parks or wildlife sanctuaries (Yala, Udawalawe,
                            Wilpattu), be alert for animals crossing the road, especially during dawn and dusk.
                            Elephants, monkeys, and wild boar can suddenly appear on rural roads.</p>
                    </div>
                </div>

                <div class="card">
                    <div class="card-badge">10</div>
                    <div class="card-content">
                        <h4>Follow Fuel-Efficient Driving Practices</h4>
                        <p>With fluctuating fuel prices in Sri Lanka, adopt fuel-efficient driving habits such as
                            maintaining steady speeds, avoiding aggressive acceleration, and proper tire inflation. This
                            is particularly important when planning long journeys along the coastal routes or to the
                            central highlands.</p>
                    </div>
                </div>

                <div class="card">
                    <div class="card-badge">11</div>
                    <div class="card-content">
                        <h4>Know Emergency Numbers</h4>
                        <p>Save important emergency contacts: Police Emergency - 119, Ambulance Service - 110, Breakdown
                            assistance from Automobile Association - 112. In case of accidents, contact the nearest
                            police station immediately and take photographs of the scene for insurance purposes.</p>
                    </div>
                </div>

                <div class="card">
                    <div class="card-badge">12</div>
                    <div class="card-content">
                        <h4>Understand Parking Regulations</h4>
                        <p>In major cities, particularly Colombo, parking is regulated with designated zones and payment
                            requirements. Watch for "no parking" signs and be aware that some areas implement a
                            park-and-ride system. Using registered parking facilities can help avoid fines or vehicle
                            clamping.</p>
                    </div>
                </div>
            </div>

            <div class="final-note">
                <h3>Stay Safe on Sri Lankan Roads</h3>
                <p>Remember that defensive driving is key to navigating Sri Lanka's diverse road conditions. Always
                    prioritize safety over speed, and be respectful of all road users.</p>
                <p>For more detailed driving guides and resources, visit the Driver Resources section in your dashboard.
                </p>
            </div>
        </div>
    </div>
</main>

<style>
    .page-title {
        color: var(--color-dark-blue);
        font-size: 2rem;
        margin-bottom: 10px;
        text-align: center;
    }

    .page-subtitle {
        color: #555;
        margin-bottom: 30px;
        text-align: center;
        font-style: italic;
    }

    .intro-box {
        background-color: #f0f8ff;
        border-left: 5px solid var(--color-light-blue);
        padding: 20px;
        margin-bottom: 40px;
        border-radius: 5px;
    }

    .intro-box h3 {
        color: var(--color-dark-blue);
        margin-bottom: 15px;
    }

    .intro-box p {
        margin-bottom: 10px;
        line-height: 1.5;
    }

    .tips-row {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
        margin-bottom: 40px;
    }

    .card {
        background: white;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        position: relative;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border-top: 4px solid var(--color-light-blue);
        height: 100%;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
    }

    .card-badge {
        position: absolute;
        top: 10px;
        left: 10px;
        background-color: var(--color-light-blue);
        color: white;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
    }

    .card-content {
        padding: 20px;
        padding-top: 35px;
    }

    .card-content h4 {
        color: var(--color-dark-blue);
        margin-bottom: 15px;
        font-size: 1.1rem;
    }

    .card-content p {
        color: #444;
        line-height: 1.5;
    }

    .section-divider {
        margin: 30px 0;
        padding: 15px 0;
        border-bottom: 1px solid #e0e0e0;
    }

    .section-divider h3 {
        color: var(--color-dark-blue);
        margin-bottom: 10px;
    }

    .final-note {
        background-color: #f9f9f9;
        padding: 25px;
        border-radius: 8px;
        margin-top: 20px;
        margin-bottom: 30px;
        text-align: center;
    }

    .final-note h3 {
        color: var(--color-dark-blue);
        margin-bottom: 15px;
    }

    @media (max-width: 1200px) {
        .tips-row {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 768px) {
        .tips-row {
            grid-template-columns: 1fr;
        }

        .page-title {
            font-size: 1.5rem;
        }
    }
</style>

<?php include_once "../../../includes/footer.php" ?>