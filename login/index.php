<?php
$pageConfig = [
    'title' => 'Login',
    'styles' => ['./login.css'],
    'authRequired' => false
];

include_once "../includes/header.php" ?>
<main>
    <div class="login-container">
        <img src="/assets/logo.svg" alt="Logo">
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
            <p class="p">Don't have an account? <a href="/digifine/signup/index.php" class="link">Register here</a></p>
            <!-- updated the path -->
        </form>
    </div>
</main>
<?php include_once "../includes/footer.php" ?>