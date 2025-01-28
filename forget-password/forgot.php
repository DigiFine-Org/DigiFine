<?php
$pageConfig = [
	'title' => 'Login',
	'styles' => ['../login/login.css'],
	'authRequired' => false
];

// if ($_SESSION['message'] ?? null) {

//     $message = $_SESSION['message']; // Store the message
//     unset($_SESSION['message']); // Clear the session message

//     // Include the alert.php file to display the message
//     include '../includes/alerts/failed.php';
// }

session_start();
$error = array();

require "mail.php";
require_once "../db/connect.php";

if (!$conn) {
	die("Couldn't connect to the database.");
}

$mode = "enter_email";
if (isset($_GET['mode'])) {
	$mode = $_GET['mode'];
}

//something is posted
if (count($_POST) > 0) {

	switch ($mode) {
		case 'enter_email':
			// code...
			$email = $_POST['email'];
			//validate email
			if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
				$error[] = "Please enter a valid email";
			} elseif (!valid_email($email)) {
				$error[] = "That email was not found";
			} else {

				$_SESSION['forgot']['email'] = $email;
				send_email($email);
				header("Location: forgot.php?mode=enter_code");
				die;
			}
			break;

		case 'enter_code':
			// code...
			$code = $_POST['code'];
			$result = is_code_correct($code);

			if ($result == "the code is correct") {

				$_SESSION['forgot']['code'] = $code;
				header("Location: forgot.php?mode=enter_password");
				die;
			} else {
				$error[] = $result;
			}
			break;

		case 'enter_password':
			// code...
			$password = $_POST['password'];
			$password2 = $_POST['password2'];

			if ($password !== $password2) {
				$error[] = "Passwords do not match";
			} elseif (!isset($_SESSION['forgot']['email']) || !isset($_SESSION['forgot']['code'])) {
				header("Location: forgot.php");
				die;
			} else {

				save_password($password);
				if (isset($_SESSION['forgot'])) {
					unset($_SESSION['forgot']);
				}

				header("Location: digifine/login/index.php");
				die;
			}
			break;

		default:
			// code...
			break;
	}
}

function send_email($email)
{

	global $conn;

	$expire = time() + (60 * 1);
	$code = rand(10000, 99999);
	$email = addslashes($email);

	$query = "insert into codes (email,code,expire) value ('$email','$code','$expire')";
	mysqli_query($conn, $query);

	// send email here
	send_mail($email, 'Password reset', "Your code is " . $code);
}


// Save the new password in the appropriate table
function save_password($password)
{

	global $conn;

	$password = password_hash($password, PASSWORD_DEFAULT);
	$email = addslashes($_SESSION['forgot']['email']);

	// $query = "update users set password = '$password' where email = '$email' limit 1";
	// mysqli_query($conn, $query);

	// Determine if the email is in drivers or officers
	$userType = get_user_type($email);

	if ($userType === 'driver') {
		$query = "UPDATE drivers SET password = '$password' WHERE email = '$email' LIMIT 1";
	} elseif ($userType === 'officer') {
		$query = "UPDATE officers SET password = '$password' WHERE email = '$email' LIMIT 1";
	} else {
		die("User type not found.");
	}

	mysqli_query($conn, $query);

}


function valid_email($email)
{
	global $conn;

	$email = addslashes($email);

	// $query = "select * from users where email = '$email' limit 1";

	// Check in drivers table
	$query = "SELECT * FROM drivers WHERE email = '$email' LIMIT 1";
	$result = mysqli_query($conn, $query);
	if ($result && mysqli_num_rows($result) > 0) {
		$_SESSION['forgot']['user_type'] = 'driver';
		return true;
	}

	// Check in officers table
	$query = "SELECT * FROM officers WHERE email = '$email' LIMIT 1";
	$result = mysqli_query($conn, $query);
	if ($result && mysqli_num_rows($result) > 0) {
		$_SESSION['forgot']['user_type'] = 'officer';
		return true;
	}

	return false;
}


// Determine user type based on email
function get_user_type($email)
{
	global $conn;

	$email = addslashes($email);

	// Check in drivers table
	$query = "SELECT * FROM drivers WHERE email = '$email' LIMIT 1";
	$result = mysqli_query($conn, $query);
	if ($result && mysqli_num_rows($result) > 0) {
		return 'driver';
	}

	// Check in officers table
	$query = "SELECT * FROM officers WHERE email = '$email' LIMIT 1";
	$result = mysqli_query($conn, $query);
	if ($result && mysqli_num_rows($result) > 0) {
		return 'officer';
	}

	return null;
}

function is_code_correct($code)
{
	global $conn;

	$code = addslashes($code);
	$expire = time();
	$email = addslashes($_SESSION['forgot']['email']);

	$query = "select * from codes where code = '$code' && email = '$email' order by id desc limit 1";
	$result = mysqli_query($conn, $query);
	if ($result) {
		if (mysqli_num_rows($result) > 0) {
			$row = mysqli_fetch_assoc($result);
			if ($row['expire'] > $expire) {

				return "the code is correct";
			} else {
				return "the code is expired";
			}
		} else {
			return "the code is incorrect";
		}
	}

	return "the code is incorrect";
}




include_once "../includes/header.php" ?>
<main>

	<?php
	switch ($mode) {
		case 'enter_email':
			// code...
			?>

			<div class="login-container">
				<img src="/digifine/assets/logo.svg" alt="Logo">
				<h2>Find your password</h2>
				<form action="forgot.php?mode=enter_email" method="POST">
					<div class="field">
						<label for="">Email Address: </label>
						<span style="font-size: 12px;color:red;">
							<?php
							foreach ($error as $err) {
								// code...
								echo $err . "<br>";
							}
							?>
						</span>
						<input type="email" id="" name="email" required class="input" placeholder="Enter your email">
						<br style="clear: both;">
					</div>
					<!-- <button type="submit" class="btn">Reset Password</button> -->
					<input type="submit" value="Reset Password" class="btn">
					<div class="link-wrap">
						<p class="p"><a href="/digifine/login/index.php" class="link">Back to login</a></p>
					</div>
					<!-- updated the path -->
				</form>
			</div>
			<?php
			break;

		case 'enter_code':
			// code...
			?>

			<div class="login-container">
				<img src="/digifine/assets/logo.svg" alt="Logo">
				<h2>Enter Code</h2>
				<form action="forgot.php?mode=enter_code" method="POST">
					<div class="field">
						<label for="">Enter the code that sent to your email: </label>
						<span style="font-size: 12px;color:red;">
							<?php
							foreach ($error as $err) {
								// code...
								echo $err . "<br>";
							}
							?>
						</span>
						<input type="text" id="" name="code" required class="input" placeholder="12345">
						<br style="clear: both;">
					</div>
					<!-- <button type="submit" class="btn">Submit</button> -->
					<input type="submit" class="btn" value="Submit">
					<div class="link-wrap">
						<p class="p"><a href="/digifine/login/index.php" class="link">Back to login</a></p>
					</div>
					<!-- updated the path -->
				</form>
			</div>
			<?php
			break;

		case 'enter_password':
			// code...
			?>

			<div class="login-container">
				<img src="/digifine/assets/logo.svg" alt="Logo">
				<h2>Enter Code</h2>
				<form action="forgot.php?mode=enter_password" method="POST">
					<div class="field">
						<label for="" style="margin-bottom: 8px;">Enter your new password: </label>
						<span style="font-size: 12px;color:red;">
							<?php
							foreach ($error as $err) {
								// code...
								echo $err . "<br>";
							}
							?>
						</span>
						<input type="password" id="" name="password" required class="input" placeholder="password">
						<label for="" style="margin-top:7px;">Retype your new password: </label>
						<input type="password" id="" name="password2" required class="input" placeholder="Retype Password">
						<br style="clear: both;">
					</div>
					<!-- <button type="submit" class="btn">Submit</button> -->
					<input type="submit" class="btn" value="Submit">
					<div class="link-wrap">
						<p class="p"><a href="../../digifine/login/index.php" class="link">Back to login</a></p>
					</div>
					<!-- updated the path -->
				</form>
			</div>
			<?php
			break;

		default:
			// code...
			break;
	}


	?>

</main>
<?php include_once "../includes/footer.php" ?>