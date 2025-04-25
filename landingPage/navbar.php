<head>
    <link rel="stylesheet" href="navbar.css">
</head>

<body>

    <?php
    $current_page = basename($_SERVER['PHP_SELF']);
    ?>

    <!-- Professional Header -->
    <header class="main-header">
        <div class="header-container">
            <div class="header-content">
                <a href="/digifine/landingPage/index.php" class="logo-link">
                    <img src="../assets/landingPage/hero-home.png" alt="DigiFine Logo" class="logo">
                    <span class="logo-text">DigiFine</span>
                </a>

                <!-- Desktop Navigation -->
                <nav class="desktop-nav">
                    <ul class="nav-links">
                        <li><a href="/digifine/landingPage/index.php" class="nav-link <?= $current_page == 'index.php' ? 'active' : '' ?>">Home</a></li>
                        <li><a href="/digifine/landingPage/aboutUs.php" class="nav-link <?= $current_page == 'aboutUs.php' ? 'active' : '' ?>">About Us</a></li>
                        <li><a href="/digifine/landingPage/services.php" class="nav-link <?= $current_page == 'services.php' ? 'active' : '' ?>">Services</a></li>
                        <li><a href="/digifine/landingPage/contacts.php" class="nav-link <?= $current_page == 'contacts.php' ? 'active' : '' ?>">Contact</a></li>
                    </ul>
                </nav>

                <!-- Auth Buttons -->
                <div class="auth-buttons">
                    <a href="/digifine/login/index.php" class="btn btn-login">Login</a>
                    <a href="/digifine/signup/index.php" class="btn btn-primary">Register</a>
                </div>

                <button class="hamburger" aria-label="Toggle navigation">&#9776;</button>
            </div>

            <!-- Mobile Navigation -->
            <nav class="mobile-nav">
                <ul class="mobile-links">
                    <li><a href="/digifine/landingPage/index.php" class="mobile-link active">Home</a></li>
                    <li><a href="/digifine/landingPage/aboutUs.php" class="mobile-link">About Us</a></li>
                    <li><a href="/digifine/landingPage/services.php" class="mobile-link">Services</a></li>
                    <li><a href="/digifine/landingPage/contacts.php" class="mobile-link">Contact</a></li>
                    <li><a href="/digifine/login/index.php" class="mobile-link">Login</a></li>
                    <li><a href="/digifine/signup/index.php" class="mobile-link">Register</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Hero Section -->

    <script>
        const hamburger = document.querySelector('.hamburger');
        const mobileNav = document.querySelector('.mobile-nav');

        hamburger.addEventListener('click', () => {
            mobileNav.classList.toggle('active');
        });
    </script>

</body>