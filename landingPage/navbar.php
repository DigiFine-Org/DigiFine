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
                
                <!-- Mobile Menu Toggle -->
                <button class="hamburger" aria-label="Toggle navigation" aria-expanded="false">
                    <span class="hamburger-box">
                        <span class="hamburger-inner"></span>
                    </span>
                </button>
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
    

    <script src="script.js"></script>

    <style>
        /* ===== Header Styles ===== */
:root {
    --primary-color: #1a5f9a;
    --primary-dark: #0d4b7a;
    --primary-light: #2a7bc8;
    --accent-color: #ff9f1c;
    --white: #ffffff;
    --light-gray: #f8f9fa;
    --dark-gray: #212529;
    --text-color: #333;
    --text-light: #6c757d;
    --transition: all 0.3s ease;
    --box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.main-header {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    background-color: var(--white);
    box-shadow: var(--box-shadow);
    z-index: 1000;
    transition: var(--transition);
}

.header-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 2rem;
}

.header-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    height: 80px;
}

.logo-link {
    display: flex;
    align-items: center;
    text-decoration: none;
}

.logo {
    height: 40px;
    width: auto;
    margin-right: 10px;
}

.logo-text {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--primary-dark);
    
}

.desktop-nav .nav-links {
    display: flex;
    list-style: none;
    margin: 0;
    padding: 0;
}

.nav-link {
    padding: 0.8rem 1.2rem;
    font-weight: 500;
    color: var(--text-color);
    text-decoration: none;
    transition: var(--transition);
    position: relative;
}

.nav-link:hover,
.nav-link.active {
    color: var(--primary-color);    
}

.nav-link.active::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 50%;
    transform: translateX(-50%);
    width: 20px;
    height: 3px;
    background-color: var(--accent-color);
    border-radius: 2px;
}

.auth-buttons {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.btn {
    display: inline-block;
    padding: 0.6rem 1.4rem;
    border-radius: 4px;
    font-weight: 500;
    text-align: center;
    transition: var(--transition);
    cursor: pointer;
    border: 2px solid transparent;
}

.btn-login {
    background-color: transparent;
    color: var(--primary-color);
    border-color: var(--primary-color);
}

.btn-login:hover {
    background-color: rgba(26, 95, 154, 0.1);
}

.btn-primary {
    background-color: var(--primary-color);
    color: var(--white);
}

.btn-primary:hover {
    background-color: var(--primary-dark);
    transform: translateY(-2px);
}

/* Mobile Menu Styles */
.hamburger {
    display: none;
    padding: 0.5rem;
    background: none;
    border: none;
    cursor: pointer;
    z-index: 1001;
}

.hamburger-box {
    width: 30px;
    height: 24px;
    display: inline-block;
    position: relative;
}

.hamburger-inner {
    width: 100%;
    height: 3px;
    background-color: var(--primary-color);
    position: absolute;
    left: 0;
    top: 50%;
    transform: translateY(-50%);
    transition: var(--transition);
}

.hamburger-inner::before,
.hamburger-inner::after {
    content: '';
    width: 100%;
    height: 3px;
    background-color: var(--primary-color);
    position: absolute;
    left: 0;
    transition: var(--transition);
}

.hamburger-inner::before {
    top: -8px;
}

.hamburger-inner::after {
    bottom: -8px;
}

.mobile-nav {
    position: fixed;
    top: 80px;
    left: 0;
    width: 100%;
    height: calc(100vh - 80px);
    background-color: var(--white);
    transform: translateX(100%);
    transition: var(--transition);
    z-index: 999;
    overflow-y: auto;
    padding: 2rem;
}

.mobile-nav.active {
    transform: translateX(0);
}

.mobile-links {
    list-style: none;
    padding: 0;
    margin: 0;
}

.mobile-link {
    display: block;
    padding: 1rem 0;
    font-size: 1.1rem;
    color: var(--text-color);
    text-decoration: none;
    border-bottom: 1px solid rgba(0, 0, 0, 0.1);
    transition: var(--transition);
}

.mobile-link:hover,
.mobile-link.active {
    color: var(--primary-color);
    padding-left: 1rem;
}

/* Hero Section */
.hero {
    position: relative;
    height: 100vh;
    min-height: 600px;
    background: url('../assets/landingPage/hero-home.jpg') no-repeat center center/cover;
    display: flex;
    align-items: center;
    margin-top: 80px;
}

.hero-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(to right, rgba(115, 136, 156, 0.9), rgba(73, 130, 179, 0.7));
}

.hero-content {
    position: relative;
    z-index: 1;
    max-width: 1200px;
    width: 100%;
    margin: 0 auto;
    padding: 0 2rem;
    color: var(--white);
}

.hero-title {
    font-size: 3rem;
    font-weight: 700;
    line-height: 1.2;
    margin-bottom: 1.5rem;
    max-width: 800px;
}

.hero-subtitle {
    font-size: 1.5rem;
    font-weight: 300;
    margin-bottom: 2.5rem;
    max-width: 600px;
}

.hero-cta {
    display: flex;
    gap: 1.5rem;
}

.btn-large {
    padding: 1rem 2rem;
    font-size: 1.1rem;
}

.btn-outline {
    background-color: transparent;
    color: var(--white);
    border-color: var(--white);
}

.btn-outline:hover {
    background-color: rgba(255, 255, 255, 0.1);
}

/* Responsive Design */
@media (max-width: 992px) {
    .hero-title {
        font-size: 2.5rem;
    }
    
    .hero-subtitle {
        font-size: 1.3rem;
    }
}

@media (max-width: 768px) {
    .desktop-nav,
    .auth-buttons {
        display: none;
    }
    
    .hamburger {
        display: block;
    }
    
    .hero-title {
        font-size: 2rem;
    }
    
    .hero-subtitle {
        font-size: 1.1rem;
    }
    
    .hero-cta {
        flex-direction: column;
        gap: 1rem;
    }
    
    .btn-large {
        width: 100%;
        max-width: 300px;
    }
}

@media (max-width: 480px) {
    .header-container {
        padding: 0 1rem;
    }
    
    .logo {
        height: 30px;
    }
    
    .logo-text {
        font-size: 1.2rem;
    }
    
    .hero {
        min-height: 500px;
        text-align: center;
    }
    
    .hero-content {
        padding: 0 1rem;
    }
    
    .hero-title {
        font-size: 1.8rem;
    }
    
    .hero-subtitle {
        font-size: 1rem;
    }
    
    .btn-large {
        max-width: 100%;
    }
}
</style>
</body>