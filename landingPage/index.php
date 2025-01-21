<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Digi-Fine | LandingPage </title>
    <link rel="stylesheet" href="landing-page.css">

</head>

<body>
    <?php
    include_once 'navbar.php';
    ?>
    <main>

        <div id="welcome-overlay">

            <img src="../assets/logo-white.svg" alt="DigiFine-Logo" id="welcome-logo">
            <p id="motto" style="color:#1e77ca;">Effortless Payments, Seamless Compliance</p>
            <!-- <h1>Welcome to Our Website</h1> -->
        </div>

        <section class="features">
            <div class="feature">
                <a href="./payFInesOnline.php" style="text-decoration: none; color: inherit;">
                    <div class="icon"><img src="../assets/landingPage/icons/fine.png" alt="list-icon"></div>
                    <h3>Pay Fines Online</h3>
                    <p>Quickly pay your traffic fines online with ease and convenience</p>
                </a>
            </div>
            <div class="feature">
                <a href="../dashboard/gov-fine-list/index.php" style="text-decoration: none; color: inherit;">
                    <div class="icon"><img src="../assets/landingPage/icons/list2.png" alt="list-icon"></div>
                    <h3>Traffic Offence List</h3>
                    <p>View a comprehensive list of traffic offences and corresponding penalties.</p>
                </a>
            </div>

            <div class="feature">
                <a href="#" style="text-decoration: none; color: inherit;">

                    <div class="icon">&#9733;</div>
                    <h3>Safety & Security</h3>
                    <p>Explore tips and guidelines to ensure road safety and personal security.</p>
                </a>
            </div>

            <div class="feature">
                <a href="./tell-igp/tell-igp.php" style="text-decoration: none; color: inherit;">
                    <div class="icon">&#9776;</div>
                    <h3>Tell IGP</h3>
                    <p>Direct communication with Inspector General's office.</p>
                </a>
            </div>
        </section>

        <section class="info-section">
            <div class="info-column">
                <h2>Criminal Facts</h2>
                <div class="tabs">
                    <button>Property Crimes</button>
                    <button>Traffic Violations</button>
                    <button>Cyber Crimes</button>
                    <button>Drug Offences</button>
                </div>
                <div class="fact-content">
                    <div class="fact-icon">&#9733;</div>
                    <p>

                        <br><br>
                        Sri Lanka Police reports focus on improving crime prevention strategies, with recent efforts
                        leading to increased public awareness and successful law enforcement campaigns. Continued
                        efforts are underway to address emerging threats, such as cybercrime and organized crime groups.
                    </p>
                </div>
            </div>
            <div class="info-column">
                <h2>Latest News</h2>
                <div class="news-item">
                    <div class="date">
                        <p>03</p>
                        <span>Jan 2024</span>
                    </div>
                    <div class="news-content">
                        <h3>New Traffic Regulation Policies</h3>
                        <p>The Police Department introduced stricter measures for reckless driving to reduce accidents.
                        </p>
                    </div>
                </div>
                <div class="news-item">
                    <div class="date">
                        <p>06</p>
                        <span>Jan 2024</span>
                    </div>
                    <div class="news-content">
                        <h3>Enhanced Crime Detection Systems</h3>
                        <p>Sri Lanka Police launches advanced surveillance systems to combat urban crime rates.</p>
                    </div>
                </div>
            </div>
        </section>
        <section class="chief-message">
            <h2>A Message from Chief</h2>
            <div class="message-content">
                <img src="../assets/landingPage/igp.webp" alt="Chief Photo" class="chief-photo">
                <p>
                    The Sri Lanka Police remains committed to serving and protecting the public. With improved systems
                    and collaborative efforts, we aim to ensure the safety and security of every citizen. Let’s work
                    together to build a peaceful and lawful society.
                </p>
            </div>
        </section>

        <section class="statistics">
            <div class="stat">
                <h3>Crimes</h3>
                <p>80% <span>Less</span></p>
            </div>
            <div class="stat">
                <h3>Robberies</h3>
                <p>85% <span>Less</span></p>
            </div>
            <div class="stat">
                <h3>Accidents</h3>
                <p>70% <span>Less</span></p>
            </div>
            <div class="stat">
                <h3>Stolen Vehicles</h3>
                <p>60% <span>Less</span></p>
            </div>
        </section>
    </main>

    <?php
    include_once 'footer.php';
    ?>
    <script src="script.js"></script>

</body>

</html>