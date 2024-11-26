<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Digi-Fine | LandingPage </title>
    <link rel="stylesheet" href="styles.css">

</head>

<body>
    <?php
    include_once 'navbar.php';
    ?>

    <section class="features">

        <div class="feature">
            <a href="#" style="text-decoration: none; color: inherit;">

                <div class="icon">&#9733;</div>
                <h3>Safety & Security</h3>
                <p>Liquam rhoncus, libero non congue ultricies. Lorem ipsum dolor sit amet.</p>
            </a>
        </div>

        <div class="feature">
            <a href="#" style="text-decoration: none; color: inherit;">
                <div class="icon">&#9776;</div>
                <h3>Crime Statistics</h3>
                <p>Liquam rhoncus, libero non congue ultricies. Lorem ipsum dolor sit amet.</p>
            </a>
        </div>
        <div class="feature">
            <a href="./payFInesOnline.php" style="text-decoration: none; color: inherit;">
                <div class="icon"><img src="../../assets/landingPage/icons/fine.png" alt="list-icon"></div>
                <h3>Pay Fines Online</h3>
                <p>Liquam rhoncus, libero non congue ultricies. Lorem ipsum dolor sit amet.</p>
            </a>
        </div>
        <div class="feature">
            <a href="../../dashboard/gov-fine-list/index.php" style="text-decoration: none; color: inherit;">
                <div class="icon"><img src="../../assets/landingPage/icons/list2.png" alt="list-icon"></div>
                <h3>Traffic Offence List</h3>
                <p>Liquam rhoncus, libero non congue ultricies. Lorem ipsum dolor sit amet.</p>
            </a>
        </div>
    </section>

    <section class="info-section">
        <div class="info-column">
            <h2>Criminal Facts</h2>
            <div class="tabs">
                <button>Dolore Ipsum</button>
                <button>Quis Enim</button>
                <button>Ligula Pharetra</button>
                <button>Itollicil Tua</button>
            </div>
            <div class="fact-content">
                <div class="fact-icon">&#9733;</div>
                <p>
                    Maecenas quis enim non ligula pharetra fringilla vel id eros. Aliqu vitae nunc.
                    <br><br>
                    Dolor nunc vule putateulr ips dol consect. Donec semp eret laciniate ultricie upi disse comete dolo
                    lectus fgilla itollicil tua ludin dolor nec met quam accumsan. Dolore con dime netus lullam utlacus
                    adipiscing ipsum molestie.
                </p>
            </div>
        </div>
        <div class="info-column">
            <h2>Latest News</h2>
            <div class="news-item">
                <div class="date">
                    <p>03</p>
                    <span>Jan 2015</span>
                </div>
                <div class="news-content">
                    <h3>Donec Facilisi Ule</h3>
                    <p>Morbi tempor quam mas sadip iscing ut cucuonge pentesqu nisl elit comte modo velutrics vel conseq.</p>
                </div>
            </div>
            <div class="news-item">
                <div class="date">
                    <p>06</p>
                    <span>Jan 2015</span>
                </div>
                <div class="news-content">
                    <h3>Donec Facilisi Ule</h3>
                    <p>Morbi tempor quam mas sadip iscing ut cucuonge pentesqu nisl elit comte modo velutrics vel conseq.</p>
                </div>
            </div>
        </div>
    </section>
    <section class="chief-message">
        <h2>A Message from Chief</h2>
        <div class="message-content">
            <img src="../../assets/landingPage/igp.webp" alt="Chief Photo" class="chief-photo">
            <p>
                Mes cuml dia sed ineniasinge dolor ipsum commete ipsum comnetus. Dolor ipsum commete ipsum comnetus mes ineniasinge dolor.
                <br><br>
                Dolor nunc vule putateulr ips dol consect. Donec semp eret laciniate ultricie upi disse comete dolo lectus fgilla itollicil tua
                ludin dolor nec met quam accumsan. Dolore con dime netus lullam utlacus adipiscing ipsum molestie euis mod lore estibulum
                vel libero ipsum sit amet sollicitudin ante. Aenean imper diet alique drerit. Nunc interdum ullas molestie euis mod lore estibulum.
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
</body>

</html>