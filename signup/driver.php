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
        <button onclick="history.back()" class="back-btn" style="float: right; margin-top: -10px; margin-right: -10px;">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                <path fill-rule="evenodd"
                    d="M15 8a.5.5 0 0 1-.5.5H3.707l3.147 3.146a.5.5 0 0 1-.708.708l-4-4a.5.5 0 0 1 0-.708l4-4a.5.5 0 1 1 .708.708L3.707 7.5H14.5a.5.5 0 0 1 .5.5z" />
            </svg>
        </button>
        <h2>Register as Driver</h2>

        <form action="signup_process.php" method="POST">
            <div class="field">
                <label for="fname">First Name:<span style="color: red;">*</span> </label>
                <input type="text" id="fname" name="fname" required class="input" placeholder="Pubuditha"
                    pattern="[A-Za-z]+" title="First name should only contain letters.">
            </div>

            <div class="field">
                <label for="lname">Last Name:<span style="color: red;">*</span> </label>
                <input type="text" id="lname" name="lname" required class="input" placeholder="Walgampaya"
                    pattern="[A-Za-z]+" title="Last name should only contain letters.">
            </div>

            <div class="field">
                <label for="email">Email:<span style="color: red;">*</span> </label>
                <input type="email" id="email" name="email" required class="input" placeholder="pubuditha@gmail.com">
            </div>

            <div class="field">
                <label for="nic">NIC:<span style="color: red;">*</span> </label>
                <input type="text" id="nic" name="nic" required class="input" placeholder="123456789V or 123456789012"
                    pattern="^\d{9}[Vv]$|^\d{12}$"
                    title="NIC should be a 9-digit number followed by 'V' or 'v' (e.g., 911042754V), or a 12-digit number (e.g., 197419202757).">
            </div>
            <div class="field">
                <label for="userid">Driver ID(Licence ID):<span style="color: red;">*</span> </label>
                <input type="text" pattern="^B[0-9]{7}$" id="userid" name="userid" class="input" required
                    placeholder="B1234567" title="Driver ID must start with 'B' followed by 7 digits">
                <small style="margin-top: 5px;">You can't change this value once entered!</small>
            </div>
            <div class="field">
                <label for="phoneno">Phone No:<span style="color: red;">*</span> </label>
                <input type="tel" id="phoneno" name="phoneno" required class="input" placeholder="0766743755"
                    pattern="\d{10}">
            </div>
            <div class="field">
                <label for="password">Password:<span style="color: red;">*</span> </label>
                <input type="password" id="password" minlength="6" name="password" required class="input"
                    pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&#])[A-Za-z\d@$!%*?&#]{6,}"
                    title="Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character.">
            </div>

            <div class="field">
                <label for="cpassword">Confirm Password:<span style="color: red;">*</span> </label>
                <input type="password" id="cpassword" name="cpassword" required class="input"
                    pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&#])[A-Za-z\d@$!%*?&#]{6,}"
                    title="Password must match the above criteria.">
            </div>


            <button type="submit" class="btn">Sign Up</button>
            <p class="p">Already have an account? <a href="/digifine/login/index.php" class="link">Login here</a></p>
        </form>
    </div>
</main>
<?php include_once "../includes/footer.php" ?>