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
        <button onclick="history.back()" class="back-btn" style="float: right; margin-top: -10px; margin-right: -10px;">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                <path fill-rule="evenodd"
                    d="M15 8a.5.5 0 0 1-.5.5H3.707l3.147 3.146a.5.5 0 0 1-.708.708l-4-4a.5.5 0 0 1 0-.708l4-4a.5.5 0 1 1 .708.708L3.707 7.5H14.5a.5.5 0 0 1 .5.5z" />
            </svg>
        </button>
        <br>
        <br>
        <a href="/digifine/signup/driver.php" class="link">Register as Driver</a>
        <br>
        <!-- <br>
        <a href="/digifine/signup/police.php" class="link">Register as Police Officer</a> -->
    </div>
</main>
<?php include_once "../includes/footer.php" ?>