<?php
$pageConfig = [
    'title' => 'Signup as Driver',
    'styles' => ['../login/login.css'],
    'authRequired' => false
];

include_once "../includes/header.php";

?>
<main>
    <div class="login-container">
        <img src="/digifine/assets/logo.svg" alt="Logo">
        <h2>Register as Driver</h2>
        <form action="signup_process.php" method="POST">
            <div class="field">
                <label for="fname">First Name:<span style="color: red;">*</span> </label>
                <input type="text" id="fname" name="fname" required class="input" placeholder="John">
            </div>

            <div class="field">
                <label for="fname">Last Name:<span style="color: red;">*</span> </label>
                <input type="text" id="lname" name="lname" required class="input" placeholder="Doe">
            </div>

            <div class="field">
                <label for="email">Email:<span style="color: red;">*</span> </label>
                <input type="email" id="email" name="email" required class="input" placeholder="johndoe@example.com">
            </div>

            <div class="field">
                <label for="nic">NIC:<span style="color: red;">*</span> </label>
                <input type="text" id="nic" name="nic" required class="input" placeholder="1122334455V">
            </div>
            <div class="field">
                <label for="userid">Driver ID(Licence ID):<span style="color: red;">*</span> </label>
                <input type="text" pattern="^([B][0-9]{7}|[0-9]{12})$" id="userid" name="userid" class="input"
                    placeholder="B1234567">
                <small style="margin-top: 5px;">You can't change this value once entered!</small>
            </div>
            <div class="field">
                <label for="phoneno">Phone No:<span style="color: red;">*</span> </label>
                <input type="tel" id="phoneno" name="phoneno" required class="input" placeholder="0766743755"
                    pattern="\d{10}">
            </div>
            <div class="field">
                <label for="password">Password:<span style="color: red;">*</span> </label>
                <input type="password" id="password" minlength="6" name="password" required class="input">
            </div>

            <div class="field">
                <label for="cpassword">Confirm Password:<span style="color: red;">*</span> </label>
                <input type="password" id="cpassword" name="cpassword" required class="input">
            </div>

            <button type="submit" class="btn">Sign Up</button>
            <p class="p">Already have an account? <a href="/digifine/login/index.php" class="link">Login here</a></p>
        </form>
    </div>
</main>
<?php include_once "../includes/footer.php" ?>