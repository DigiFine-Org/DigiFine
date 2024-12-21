<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';



// Checking if the form is submitted
if (isset($_POST['submit'])) {

    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'imalsha.contact@gmail.com';
    $mail->Password = 'maxq quoe itby rizk';

    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>
</head>
<body>
    <div class="container">
        <form action="test.php" method="post">
            <p>
                <label for="fullname">Full Name:</label>
                <input type="text" name="fullname" id="fullname" required>
            </p>
            <p>
                <label for="email">Email:</label>
                <input type="email" name="email" id="email" required>
            </p>
            <p>
                <label for="subject">Subject:</label>
                <input type="text" name="subject" id="subject" required>
            </p>
            <p>
                <label for="message">Message:</label>
                <textarea name="message" id="message" cols="30" rows="10" required></textarea>
            </p>
            <p>
                <button type="submit" name="submit">Send Message</button>
            </p>
        </form>
    </div>
</body>
</html>
