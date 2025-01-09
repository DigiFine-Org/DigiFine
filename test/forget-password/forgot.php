<?php
session_start();
$error = array();


require_once "../../db/connect.php";

$mode = "enter_email";
if (isset($_GET['mode'])) {
    $mode = $_GET['mode'];
}

// something is posted
if (count($_POST) > 0) {
    switch ($mode) {
        case 'enter_email':
            // code ..
            $email = $_POST['email'];

            // validate email
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error[] = "Please enter a valid email";
            } else {
                $_SESSION['email'] = $email;
                send_email($email);
                header("Location: forgot.php?mode=enter_code");
                die;
            }
            break;
        case 'enter_code':
            // code ..
            $code = $_POST['code'];
            $result = is_code_correct($code);

            if ($result == "the code is correct!") {
                header("Location: forgot.php?mode=enter_password");
                die;
            } else {
                $error[] = $result;
            }
            break;
        case 'enter_password':
            // code ..
            $password = $_POST['password'];
            $password2 = $_POST['password2'];

            if ($password !== $password2) {
                $error[] = "Password do not match";
            } else {
                save_password($password);
                header("Location: login.php");
                die;
            }
            die;
            break;
        default:
            // code ..
            break;
    }
}

function send_email($email)
{
    // exxpire after 1 mins
    $expire = time() + (60 * 1);
    // random number between
    $code = rand(10000, 99999);
    $email = addslashes($email);

    $query = "insert into codes (email, code, expire) value ('$email', '$code', '$expire')";
    mysqli_query($conn, $query);

    //send email here
    mail($email, 'Digifine: Reset password', 'your code is' . $code);
}

function is_code_correct($code)
{
    $code = addslashes($code);
    $expire = time();
    $email = addslashes($_SESSION['email']);

    $query = "select * from codes where code = '$code' && email = '$email' order by id desc limit 1";
    $result = mysqli_query($conn, $query);
    if ($result) {
        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            if ($row['expire'] > $expire) {
                return "the code is correct!";
            } else {
                return "the code is expired!";
            }
        } else {
            return "the code is incorrect!";
        }
    }

    return "the code is incorrect!";
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot</title>
</head>

<body>
    <?php
    switch ($mode) {
        case 'enter_email':
            // code ..
            ?>
            <form action="forgot.php?mode=enter_email" method="post">
                <h1>Forgot Password</h1>
                <h3>Enter your email below</h3>
                <input type="email" name="email" id="" placeholder="Email">
                <br style="clear: both;">
                <input type="submit" value="Next">
                <div><a href="Login.php">Login</a></div>
                <br><br>
            </form>
            <?php
            break;
        case 'enter_code':
            // code ..
            ?>
            <form action="forgot.php?mode=enter_code" method="post">
                <h1>Forgot Password</h1>
                <h3>Enter code that has enter to your email</h3>
                <input class="textbox" type="text" name="email" id="" placeholder="12345">
                <br style="clear: both;">
                <input type="submit" value="Next" style="float: right;">
                <a href="forgot.php">
                    <input type="button" value="Start Over">
                </a>
                <div><a href="Login.php">Login</a></div>
                <br><br>
            </form>
            <?php
            break;
        case 'enter_password':
            // code ..
            ?>
            <form action="forgot.php?mode=enter_password" method="post">
                <h1>Forgot Password</h1>
                <h3>Enter your new password</h3>
                <input class="textbox" type="text" name="password" id="" placeholder="Password">
                <input class="textbox" type="text" name="password2" id="" placeholder="Retype Password">
                <br style="clear: both;">
                <input type="submit" value="Next" style="float: right;">
                <a href="forgot.php">
                    <input type="button" value="Start Over">
                </a>
                <div><a href="Login.php">Login</a></div>
                <br><br>
            </form>
            <?php
            break;
        default:
            // code ..
            break;
    }

    ?>


</body>

</html>