<?php

// include_once "config.php";

// session_start();

// if (isset($_SESSION['user'])) {
//     header("Location: /digifine/dashboard/index.php");
// } else {
//     header("Location: /digifine/login/index.php");
// }
// Example dynamic data (replace or connect to a database as needed)
$title = "Police Department";
$services = [
    ["title" => "Emergency Assistance", "description" => "Available 24/7 for immediate response."],
    ["title" => "Crime Reporting", "description" => "Report crimes and suspicious activities easily."],
    ["title" => "Community Engagement", "description" => "Collaborating with the community for safety."],
];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <header>
        <div class="header-container">
            <img src="../../assets/logo-white.svg" alt="Logo" class="logo">
            <nav>
                <ul class="nav-list">
                    <li><a href="#home">Home</a></li>
                    <li><a href="#about">About Us</a></li>
                    <li><a href="#services">Services</a></li>
                    <li><a href="#contact">Contact Us</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main>
        <section id="home" class="hero-section">
            <div class="hero-content">
                <h1><br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <?php
                    // echo $title;
                    ?>
                </h1>
                <!-- <p>Your safety is our priority. Explore our services and resources.</p> -->
                <a href="login/index.php" class="btn-primary">Login / Sign up</a>
            </div>
        </section>

        <section id="about" class="info-section">
            <h2>About Us</h2>
            <p>We are dedicated to maintaining law and order, ensuring the safety of our citizens, and building a secure community.</p>
            <!-- <img src="assets/landingPage/about-us.png" alt="Police officers" class="info-image"> -->
        </section>

        <section id="services" class="services-section">
            <h2>Our Services</h2>
            <div class="service-list">
                <?php foreach ($services as $service): ?>
                    <div class="service-item">
                        <h3><?php echo $service['title']; ?></h3>
                        <p><?php echo $service['description']; ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>

        <section id="contact" class="contact-section">
            <h2>Contact Us</h2>
            <form class="contact-form" method="POST" action="submit_form.php">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" required>

                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>

                <label for="message">Message</label>
                <textarea id="message" name="message" rows="4" required></textarea>

                <button type="submit" class="btn-primary">Submit</button>
            </form>
        </section>
    </main>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> <?php echo $title; ?>. All rights reserved.</p>
    </footer>
</body>