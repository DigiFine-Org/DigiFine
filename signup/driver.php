<?php
$pageConfig = [
    'title' => 'Signup as Driver',
    'styles' => ['../login/login.css'],
    'authRequired' => false
];

include_once "../includes/header.php";


// Check for form errors from previous submission
$errors = isset($_SESSION['form_errors']) ? $_SESSION['form_errors'] : [];
$formData = isset($_SESSION['form_data']) ? $_SESSION['form_data'] : [];

// Clear session data after retrieving it
unset($_SESSION['form_errors']);
unset($_SESSION['form_data']);

// Function to display field value if available
function getFieldValue($field)
{
    global $formData;
    return isset($formData[$field]) ? htmlspecialchars($formData[$field]) : '';
}

// Function to display error message for a field
function getErrorMessage($field)
{
    global $errors;
    if (isset($errors[$field])) {
        return '<span class="error-message" style="display:block;">' . $errors[$field] . '</span>';
    }
    return '';
}
?>
<main>
    <div class="login-container">
        <img src="/digifine/assets/logo.svg" alt="Logo">
        <h2>Register as Driver</h2>

        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="alert alert-danger">
                <?php echo $_SESSION['error_message'];
                unset($_SESSION['error_message']); ?>
            </div>
        <?php endif; ?>

        <form action="signup_process.php" method="POST">
            <div class="field">
                <label for="fname">First Name:<span style="color: red;">*</span> </label>
                <input type="text" id="fname" name="fname" required
                    class="input <?php echo isset($errors['fname']) ? 'input-error' : ''; ?>" placeholder="Pubuditha"
                    value="<?php echo getFieldValue('fname'); ?>">
                <?php echo getErrorMessage('fname'); ?>
                <span id="fname-error" class="error-message"></span>
            </div>

            <div class="field">
                <label for="lname">Last Name:<span style="color: red;">*</span> </label>
                <input type="text" id="lname" name="lname" required
                    class="input <?php echo isset($errors['lname']) ? 'input-error' : ''; ?>" placeholder="Walgampaya"
                    value="<?php echo getFieldValue('lname'); ?>">
                <?php echo getErrorMessage('lname'); ?>
                <span id="lname-error" class="error-message"></span>
            </div>

            <div class="field">
                <label for="email">Email:<span style="color: red;">*</span> </label>
                <input type="email" id="email" name="email" required
                    class="input <?php echo isset($errors['email']) ? 'input-error' : ''; ?>"
                    placeholder="pubuditha@gmail.com" value="<?php echo getFieldValue('email'); ?>">
                <?php echo getErrorMessage('email'); ?>
                <span id="email-error" class="error-message"></span>
            </div>

            <div class="field">
                <label for="nic">NIC:<span style="color: red;">*</span> </label>
                <input type="text" id="nic" name="nic" required
                    class="input <?php echo isset($errors['nic']) ? 'input-error' : ''; ?>" placeholder="1122334455V"
                    value="<?php echo getFieldValue('nic'); ?>">
                <?php echo getErrorMessage('nic'); ?>
                <span id="nic-error" class="error-message"></span>
            </div>

            <div class="field">
                <label for="userid">Driver ID(Licence ID):<span style="color: red;">*</span> </label>
                <input type="text" pattern="^([B][0-9]{7}|[0-9]{12})$" id="userid" name="userid"
                    class="input <?php echo isset($errors['userid']) ? 'input-error' : ''; ?>" placeholder="B1234567"
                    value="<?php echo getFieldValue('userid'); ?>">
                <?php echo getErrorMessage('userid'); ?>
                <span id="userid-error" class="error-message"></span>
                <small style="margin-top: 5px;">You can't change this value once entered!</small>
            </div>

            <div class="field">
                <label for="phoneno">Phone No:<span style="color: red;">*</span> </label>
                <input type="tel" id="phoneno" name="phoneno" required
                    class="input <?php echo isset($errors['phoneno']) ? 'input-error' : ''; ?>" placeholder="0766743755"
                    pattern="\d{10}" value="<?php echo getFieldValue('phoneno'); ?>">
                <?php echo getErrorMessage('phoneno'); ?>
                <span id="phoneno-error" class="error-message"></span>
            </div>

            <div class="field">
                <label for="password">Password:<span style="color: red;">*</span> </label>
                <input type="password" id="password" minlength="6" name="password" required
                    class="input <?php echo isset($errors['password']) ? 'input-error' : ''; ?>">
                <?php echo getErrorMessage('password'); ?>
                <span id="password-error" class="error-message"></span>
            </div>

            <div class="field">
                <label for="cpassword">Confirm Password:<span style="color: red;">*</span> </label>
                <input type="password" id="cpassword" name="cpassword" required
                    class="input <?php echo isset($errors['cpassword']) ? 'input-error' : ''; ?>">
                <?php echo getErrorMessage('cpassword'); ?>
                <span id="cpassword-error" class="error-message"></span>
            </div>

            <button type="submit" class="btn">Sign Up</button>
            <p class="p">Already have an account? <a href="/digifine/login/index.php" class="link">Login here</a></p>
        </form>
    </div>
</main>

<!-- Include validation script -->
<script src="validation.js"></script>




<?php
// $pageConfig = [
//     'title' => 'Signup as Driver',
//     'styles' => ['../login/login.css'],
//     'authRequired' => false
// ];

// include_once "../includes/header.php";

?>
<!-- <main>
    <div class="login-container">
        <img src="/digifine/assets/logo.svg" alt="Logo">
        <h2>Register as Driver</h2>
        <form action="signup_process.php" method="POST">
            <div class="field">
                <label for="fname">First Name:<span style="color: red;">*</span> </label>
                <input type="text" id="fname" name="fname" required class="input" placeholder="Pubuditha">
            </div>

            <div class="field">
                <label for="fname">Last Name:<span style="color: red;">*</span> </label>
                <input type="text" id="lname" name="lname" required class="input" placeholder="Walgampaya">
            </div>

            <div class="field">
                <label for="email">Email:<span style="color: red;">*</span> </label>
                <input type="email" id="email" name="email" required class="input" placeholder="pubuditha@gmail.com">
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
</main> -->
<?php include_once "../includes/footer.php" ?>