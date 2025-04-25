<?php
session_start();
require_once "../../../../db/connect.php";
require "../../../../PHPMailer/mail.php";


if ($_SERVER["REQUEST_METHOD"] != "POST") {
    die("Invalid request!");
}

$fname = htmlspecialchars($_POST['fname']);
$lname = htmlspecialchars($_POST['lname']);
$email = htmlspecialchars($_POST['email']);
$nic = htmlspecialchars($_POST['nic']);
$userid = htmlspecialchars($_POST['userid']);
$province = htmlspecialchars($_POST['province']);
$policestation = htmlspecialchars($_POST['policestation']);
$phoneno = htmlspecialchars($_POST['phoneno']);

// Generate a random temporary password
$temp_password = substr(str_shuffle("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789@#$%"), 0, 10);
$hashed_password = password_hash($temp_password, PASSWORD_DEFAULT);

$sql = "INSERT INTO officers (id, fname, lname, email, phone_no, nic, police_station, password) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Database error: " . $conn->error);
}
$stmt->bind_param("isssssss", $userid, $fname, $lname, $email, $phoneno, $nic, $policestation, $hashed_password);

if ($stmt->execute()) {

    // Generate a unique password reset token (valid for 24 hours)
    $reset_token = bin2hex(random_bytes(32));
    $expire_time = time() + (24 * 60 * 60); //24 hours

    // Store reset token in db
    $token_sql = "INSERT INTO password_resets (email, token, expires_at) VALUES (?, ?, FROM_UNIXTIME(?))";
    $token_stmt = $conn->prepare($token_sql);
    $token_stmt->bind_param("ssi", $email, $reset_token, $expire_time);
    $token_stmt->execute();
    $token_stmt->close();

    // Send email with reset link
    $subject = "Your Digifine Police Account - Action Required";
    $message = "
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; }
                .container {padding: 20px; border: 1px solid #ddd; border-radius: 5px; }
                h2 {color: #333; }
                .details {font-size: 14px; line-height: 1.6; }
                .button { padding: 10px 15px; background: #007bff; color: white; text-decoration: none; border-radius: 5px; }
            </style>
        </head>
        <body>
            <div class='container'>
                <h2>Welcome to Digifine</h2>
                <p class='details'>Dear Officer <strong>$fname $lname</strong>,</p>
                <p class='details'>You have been registered as a police officer on the Digifine system.</p>
                <p class='details'><strong>Your Police ID:</strong> $userid</p>
                <p class='details'><strong>Temporary Password:</strong> $temp_password</p>
                <p class='details'>For security reasons, please reset your password within 24 hours using the link below:</p>
                // <p><a href='http://localhost/digifine/officer-reset-password.php?token=$reset_token' class='button' style='color: white;'>Set New Password</a></p>
            </div>
        </body>
        </html>
";
    send_mail($email, $subject, $message);

    $_SESSION["message"] = "Officer registered successfully! Email sent.";
    header("Location: /digifine/dashboard/admin/officer-management/register-officer/index.php");
    exit();

} else {
    file_put_contents("error_log.txt", "Error inserting officer: " . $conn->error . "\n", FILE_APPEND);
    die("Error inserting officer. Check logs.");
}

$stmt->close();
$conn->close();
?>