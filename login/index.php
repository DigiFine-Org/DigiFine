<?php
$pageConfig = [
    'title' => 'Login',
    'styles' => ['./login.css'],
    'authRequired' => false
];

session_start();
if ($_SESSION['message'] ?? null) {

    $message = $_SESSION['message'];
    unset($_SESSION['message']); 

    include '../includes/alerts/failed.php';
}

include_once "../includes/header.php" ?>
<main>
    <div class="login-container">
        <button onclick="history.back()" class="back-btn" style="float: right; margin-top: -10px; margin-right: -10px;">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                <path fill-rule="evenodd"
                    d="M15 8a.5.5 0 0 1-.5.5H3.707l3.147 3.146a.5.5 0 0 1-.708.708l-4-4a.5.5 0 0 1 0-.708l4-4a.5.5 0 1 1 .708.708L3.707 7.5H14.5a.5.5 0 0 1 .5.5z" />
            </svg>
        </button>
        <img src="/digifine/assets/logo.svg" alt="Logo">
        <h2>Welcome Back!</h2>
        <form action="login_process.php" method="POST">
            <div class="field">
                <label for="userid">Driver Licence ID/Police ID: </label>
                <input type="text" id="policeid" name="userid" required class="input" placeholder="b1234567/44552">
            </div>
            <div class="field">
                <label for="password">Password: </label>
                <input type="password" id="password" name="password" required placeholder="Your Password" class="input">
            </div>
            <button type="submit" class="btn">Login</button>
            <div class="link-wrap">
                <p class="p"><a href="/digifine/signup/index.php" class="link">Sign up for Digifine</a></p>
                <p class="p"><a href="/digifine/forget-password/forgot.php" class="link">Forgotton Password?</a></p>
            </div>
        </form>
    </div>
</main>
<?php include_once "../includes/footer.php" ?>