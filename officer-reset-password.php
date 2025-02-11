<?php
$pageConfig = [
    'title' => 'Update Password',
    'styles' => ['./login/login.css'],
    'authRequired' => false
];

session_start();
// if ($_SESSION['message'] ?? null) {

//     $message = $_SESSION['message']; // Store the message
//     unset($_SESSION['message']); // Clear the session message

//     // Include the alert.php file to display the message
//     include '../includes/alerts/failed.php';
// }

include_once "./includes/header.php" ?>
<main>
    <div class="login-container">
        <img src="/digifine/assets/logo.svg" alt="Logo">
        <h2>Set a new password!</h2>
        <form action="change-password-process.php" method="POST">
        <input type="hidden" name="email" value="<?= isset($_GET['email']) ? htmlspecialchars($_GET['email']) : '' ?>">
            <div class="field">
                <label for="">New Password: </label>
                <input type="password" name="new_password" required class="input">
            </div>
            <div class="field">
                <label for="">Confirm Password: </label>
                <input type="password" name="confirm_password" required class="input">
            </div>
            <button type="submit" class="btn">Update Password</button>
            
        </form>
    </div>
</main>
<?php include_once "./includes/footer.php" ?>


<!-- <form action="change-password-process.php" method="POST">
    <input type="hidden" name="email" value="<?= htmlspecialchars($_GET['email']) ?>">
    
    <label>New Password:</label>
    <input type="password" name="new_password" required>

    <label>Confirm Password:</label>
    <input type="password" name="confirm_password" required>

    <button type="submit">Update Password</button>
</form> -->
