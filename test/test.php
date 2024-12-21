<?

    //checking if the form is submit
    if(isset($_POST['submit'])) {
        $fullname = $_POST['fullname'];
        $email = $_POST['email'];
        $subject = $_POST['subject'];
        $message = $_POST['message'];
    }

    $to = 'imalsha.contact@gmail.com';
    $mail_subject = 'Message from website';
    $email_body = "Message from contact us page of the website: <br>";
    $email_body .= "<b>From:</b> {$fullname} <br>";
    $email_body .= "<b>Subject:</b> {$subject} <br>";
    $email_body .= "<b>Message:</b><br>" .nl2br(strip_tags($message));

    

    mail(to, subject, $message)



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
                <label for="fullname">Full Name: </label>
                <input type="text" name="fullname" id="fullname" required>
            </p>
            <p>
                <label for="email">Email: </label>
                <input type="email" name="email" id="email" required>
            </p>
            <p>
                <label for="subject">Subject: </label>
                <input type="text" name="subject" id="subject" required>
            </p>
            <p>
                <label for="message">Message: </label>
                <textarea name="message" id="message" cols="30" rows="10" required></textarea>
            </p>
            <p>
                <button type="submit" name="submit">Send Message</button>
            </p>
        </form>
    </div>
    
</body>
</html>