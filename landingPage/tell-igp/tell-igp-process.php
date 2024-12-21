<?php

session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../PHPMailer/src/Exception.php';
require '../../PHPMailer/src/PHPMailer.php';
require '../../PHPMailer/src/SMTP.php';

if (isset($_POST['submit'])) {

    $district = $_POST['district'];
    $police_station = $_POST['police_station'];
    $complaint = $_POST['complaint'];
    $name = $_POST['name'];
    $address = $_POST['address'];
    $nic = $_POST['nic'];
    $contact_number = $_POST['contact_number'];
    $email = $_POST['email'];
    $complaint_text = $_POST['complaint_text'];
    $complaint_subject = $_POST['complaint_subject'];



    //Create an instance; passing `true` enables exceptions
    $mail = new PHPMailer(true);

    try {
        //Server settings
        // $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->SMTPAuth = true;                                   //Enable SMTP authentication

        $mail->Host = 'smtp.gmail.com';                     //Set the SMTP server to send through
        $mail->Username = 'hmnewsdiary@gmail.com';                     //SMTP username
        $mail->Password = 'eiffazxrvlamszea';                               //SMTP password

        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
        $mail->Port = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        //Recipients
        $mail->setFrom('hmnewsdiary@gmail.com', $fullname);
        $mail->addAddress('hmnewsdiary@gmail.com', $fullname);     //Add a recipient

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
            <h3>District: ' . $district . '</h3>
            <h4>Police Station: ' . $police_station . '</h4>
            <h5>Complaint Category: ' . $complaint . '</h5>
            <p>Name: ' . $name . '</p>       
            <p>Address: ' . $address . '</p>       
            <p>NIC: ' . $nic . '</p>       
            <p>Contact Number: ' . $contact_number . '</p>       
            <p>Email: ' . $email . '</p>       
            <p>Complaint Subject: ' . $complaint_subject . '</p>       
            <p>Complaint: ' . $complaint_text . '</p>       
        ';
        // $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

        // $mail->send();
        // echo 'Message has been sent';

        if ($mail->send()) {
            $_SESSION['status'] = "Thankyou for contact us - Digifine";
            header('Location: ../../index.php');

        } else {
            $_SESSION['status'] = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            header('Location: {$_SERVER["HTTP_REFERER"]}');
            exit(0);
        }

    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }


} else {
    header('Location: ../../index.php');
    exit(0);
}