<?php
$pageConfig = [
    'title' => 'Signup | Choice',
    'styles' => ['../login/login.css'],
    'authRequired' => false
];

include_once "../includes/header.php" ?>
<main>
    <div class="login-container">
        <img src="/digifine/assets/logo.svg" alt="Logo">
        <br>
        <br>
        <a href="/digifine/signup/continue/index.php" class="link">Register as Driver</a>
        <br>
        <br>
        <a href="/digifine/signup/continue/index.php?as_police=true" class="link">Register as Police Officer</a>
    </div>
</main>
<?php include_once "../includes/footer.php" ?>