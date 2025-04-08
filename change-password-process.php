<?php
require_once "./db/connect.php";

// Check if form data is received
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    die("Invalid request method!");
}



// Get email and passwords
$email = htmlspecialchars($_POST['email']);
$new_password = $_POST['new_password'];
$confirm_password = $_POST['confirm_password'];

if ($new_password !== $confirm_password) {
    die("Passwords do not match!");
}

var_dump($email);
exit;


// Hash the new password
$hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

// // Make sure email exists before updating
$checkEmailSql = "SELECT id FROM officers WHERE email = ?";
$stmt = $conn->prepare($checkEmailSql);
if (!$stmt) {
    die("Error preparing statement: " . $conn->error);
}
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("No officer found with this email.");
}
$stmt->close();



// Update the password and mark the officer as active
$updateSql = "UPDATE officers SET password = ?, is_active = 1 WHERE email = ?";
$stmt = $conn->prepare($updateSql);
$stmt->bind_param("ss", $hashed_password, $email);

if ($stmt->execute()) {
    echo "Password updated successfully! You can now log in.";
    //header("Location: /digifine/login/index.php");
} else {
    die("Error updating password: " . $conn->error);
}

$stmt->close();
$conn->close();

// PASSWORD_RESET_REQUEST_TABLE:[TOKEN:PRIMARY KEY, EMAIL]
// Fetch email by token
// update password related to fetched email
// delete token from table
?>