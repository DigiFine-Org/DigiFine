<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Digi Fine</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <?php
    include_once 'navbar.php';
    ?>

    <main>
        <section class="about">
            <h2>About Us</h2>
            <p>
                Welcome to <strong>Digi-Fine</strong>, an innovative platform dedicated to transforming traffic management in Sri Lanka.
                Our mission is to simplify the process of issuing, managing, and paying traffic fines while promoting accountability and transparency.
            </p>
            <p>
                Digi-Fine leverages cutting-edge technology to create a seamless experience for police officers, drivers, and administrators.
                By digitalizing the entire process, we aim to reduce paperwork, save time, and ensure accurate record-keeping.
            </p>
            <h3>What We Offer:</h3>
            <ul>
                <li>Efficiency for officers</li>
                <li>Convenience for drivers</li>
                <li>Robust tools for administrators</li>
            </ul>

            <a href="login/index.php" class="btn-primary">Login / Sign up</a>
        </section>
        <section>
            <h2>Our Team</h2>
            <div class="team">
                <div class="team-member">
                    <img src="../../assets/landingPage/team/ceo.jpg" alt="CEO" class="team-image">
                    <h3>John Doe</h3>
                    <p>CEO</p>
                </div>
                <div class="team-member">
                    <img src="../../assets/landingPage/team/cto.jpg" alt="CTO" class="team-image">
                    <h3>Jane Doe</h3>
                    <p>CTO</p>
                </div>
                <div class="team-member">
                    <img src="../../assets/landingPage/team/cfo.jpg" alt="CFO" class="team-image">
                    <h3>James Doe</h3>
                    <p>CFO</p>
                </div>
            </div>
        </section>

        <section>
            <div>
                <h2>Get Sarterd</h2>
                <a href="login/index.php" class="btn-primary">Login / Sign up</a>
            </div>
        </section>
    </main>

    <footer>
        <p>&copy; 2024 Digi-Fine. All rights reserved.</p>
    </footer>
</body>

</html>