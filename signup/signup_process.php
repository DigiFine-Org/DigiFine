<?php

require_once '../db/connect.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $fname = trim($_POST["fname"]);
    $lname = trim($_POST["lname"]);
    $email = trim($_POST["email"]);
    $nic = trim($_POST["nic"]);
    $userid = trim($_POST["userid"]);
    $phoneno = trim($_POST["phoneno"]);
    $password = $_POST["password"];
    $cpassword = $_POST["cpassword"];

    // Initialize errors array
    $errors = [];

    // Server-side validation
    if (empty($fname)) {
        $errors['fname'] = "First name is required";
    } elseif (preg_match('/[0-9]/', $fname)) {
        $errors['fname'] = "First name cannot contain numbers";
    } elseif (preg_match('/[^a-zA-Z\s]/', $fname)) {
        $errors['fname'] = "First name can only contain letters";
    }

    if (empty($lname)) {
        $errors['lname'] = "Last name is required";
    } elseif (preg_match('/[0-9]/', $lname)) {
        $errors['lname'] = "Last name cannot contain numbers";
    } elseif (preg_match('/[^a-zA-Z\s]/', $lname)) {
        $errors['lname'] = "Last name can only contain letters";
    }

    if (empty($email)) {
        $errors['email'] = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Invalid email format";
    }

    if (empty($nic)) {
        $errors['nic'] = "NIC is required";
    } elseif (!preg_match('/^[0-9]{9}[VvXx]$/', $nic) && !preg_match('/^[0-9]{12}$/', $nic)) {
        $errors['nic'] = "Invalid NIC format";
    }

    if (empty($userid)) {
        $errors['userid'] = "Driver ID is required";
    } elseif (!preg_match('/^([B][0-9]{7})$/', $userid)) {
        $errors['userid'] = "Invalid Driver ID format";
    }

    if (empty($phoneno)) {
        $errors['phoneno'] = "Phone number is required";
    } elseif (!preg_match('/^\d{10}$/', $phoneno)) {
        $errors['phoneno'] = "Phone number must be 10 digits";
    }

    if (empty($password)) {
        $errors['password'] = "Password is required";
    } elseif (strlen($password) < 6) {
        $errors['password'] = "Password must be at least 6 characters";
    }

    if ($password !== $cpassword) {
        $errors['cpassword'] = "Passwords do not match";
    }

    try {
        $conn = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $password_db);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Check if email already exists
        $stmt = $conn->prepare("SELECT COUNT(*) FROM drivers WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        if ($stmt->fetchColumn() > 0) {
            $errors['email'] = "This email is already registered";
        }

        // Check if NIC already exists
        $stmt = $conn->prepare("SELECT COUNT(*) FROM drivers WHERE nic = :nic");
        $stmt->bindParam(':nic', $nic);
        $stmt->execute();
        if ($stmt->fetchColumn() > 0) {
            $errors['nic'] = "This NIC is already registered";
        }

        // Check if Driver ID already exists
        $stmt = $conn->prepare("SELECT COUNT(*) FROM drivers WHERE userid = :userid");
        $stmt->bindParam(':userid', $userid);
        $stmt->execute();
        if ($stmt->fetchColumn() > 0) {
            $errors['userid'] = "This Driver ID is already registered";
        }

        // If there are validation errors, redirect back to the form with errors
        if (!empty($errors)) {
            $_SESSION['form_errors'] = $errors;
            $_SESSION['form_data'] = $_POST; // Save form data to repopulate fields
            header("Location: driver.php");
            exit();
        }

        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert the new driver
        $stmt = $conn->prepare("INSERT INTO drivers (fname, lname, email, nic, userid, phoneno, password) 
                              VALUES (:fname, :lname, :email, :nic, :userid, :phoneno, :password)");

        $stmt->bindParam(':fname', $fname);
        $stmt->bindParam(':lname', $lname);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':nic', $nic);
        $stmt->bindParam(':userid', $userid);
        $stmt->bindParam(':phoneno', $phoneno);
        $stmt->bindParam(':password', $hashed_password);

        $stmt->execute();

        // Set success message and redirect to login
        $_SESSION['success_message'] = "Registration successful! You can now login.";
        header("Location: /digifine/login/index.php");
        exit();

    } catch (PDOException $e) {
        $_SESSION['error_message'] = "Database error: " . $e->getMessage();
        header("Location: driver.php");
        exit();
    }

    $conn = null;
} else {
    // If not POST request, redirect to registration page
    header("Location: /digifine/signup/driver.php");
    exit();
}




// if ($_SERVER["REQUEST_METHOD"] != "POST") {
//     die("invalid request!");
// }

// require_once '../db/connect.php';

// $fname = $_POST['fname'];
// $lname = $_POST['lname'];
// $email = $_POST['email'];
// $nic = $_POST['nic'];
// $userid = strtolower($_POST['userid']);
// $password = $_POST['password'];
// $cpassword = $_POST['cpassword'];
// $asPolice = $_POST['aspolice'] ?? false;
// $policeStation = $_POST['policestation'] ?? null;
// $phoneNo = $_POST['phoneno'];
// $province = $_POST['province'];

// if ($password !== $cpassword) {
//     die("Passwords do not match!");
// }

// $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// if ($asPolice) {
//     $sql = "INSERT INTO officers (id, fname, lname, email, phone_no, nic, police_station, password) VALUES ('$userid', '$fname', '$lname', '$email', '$phoneNo', '$nic', '$policeStation', '$hashedPassword')";
// } else {
//     $sql = "INSERT INTO drivers (id, fname, lname, email, phone_no, nic, password) VALUES ('$userid', '$fname', '$lname', '$email', '$phoneNo', '$nic', '$hashedPassword')";
// }

// if (!$conn->query($sql)) {
//     die("Error: " . $conn->error);
// }

// header('Location: /digifine/login/index.php');
