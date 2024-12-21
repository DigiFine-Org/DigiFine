<?php

session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

if (isset($_POST['submit'])) {

    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];



    //Create an instance; passing `true` enables exceptions
    $mail = new PHPMailer(true);

    try {
        //Server settings
        // $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->SMTPAuth = true;                                   //Enable SMTP authentication

        $mail->Host = 'smtp.gmail.com';                     //Set the SMTP server to send through
        $mail->Username = 'imalsha.contact@gmail.com';                     //SMTP username
        $mail->Password = '';                               //SMTP password

        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
        $mail->Port = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        //Recipients
        $mail->setFrom('imalsha.contact@gmail.com', 'Imalsha Jathunarachchi');
        $mail->addAddress('imalsha.contact@gmail.com', 'Imalsha Jathunarachchi');     //Add a recipient

        // $mail->addAddress('ellen@example.com');               //Name is optional
        // $mail->addReplyTo('info@example.com', 'Information');
        // $mail->addCC('cc@example.com');
        // $mail->addBCC('bcc@example.com');

        //Attachments
        // $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
        // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = 'Tell IGP';
        $mail->Body = '
            <h4>Full Name: ' . $fullname . '</h4>
            <h4>Email: ' . $email . '</h4>
            <h4>Subject: ' . $subject . '</h4>
            <h4>Message: ' . $message . '</h4>
        
        ';
        // $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

        // $mail->send();
        // echo 'Message has been sent';

        if ($mail->send()) {
            $_SESSION['status'] = "Thankyou for contact us - Digifine";
            
        } else {
            $_SESSION['status'] = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            header('Location: {$_SERVER["HTTP_REFERER"]}');
            exit(0);
        }

    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }


} else {
    header('Location: contact.php');
    exit(0);
}