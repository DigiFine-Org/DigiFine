<?php
session_start();


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <div>
        <form action="sendmail.php" method="POST">
            <div>
                <label for="fullname">Full Name</label>
                <input type="text" name="fullname" id="fullname" required>
            </div>
            <div>
                <label for="">Email</label>
                <input type="email" name="email" id="email" required>
            </div>
            <div>
                <label for="subject">Subject</label>
                <input type="text" name="subject" id="subject" required>
            </div>
            <div>
                <label for="message">Message</label>
                <textarea name="message" id="message" cols="30" rows="10" required></textarea>
            </div>
            <div>
                <button type="submit" name="submit">Submit</button>
            </div>
        </form>

        <script src="https://code.jquery.com/jquery-3.7.1.min.js"
            integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>

            var messageText = "<?= $_SESSION['status'] ?? ''; ?>"
            if(messageText != ''){
                Swal.fire({
                title: "Thankyou",
                text: messageText,
                icon: "success"
            });
            <?php unset($_SESSION['status'])?>
            }
            
        </script>

    </div>
</body>

</html>