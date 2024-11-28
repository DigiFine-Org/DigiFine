<?php
// Line 1: Start of the PHP script

// Line 2-21: Database Connection
$host = "localhost";
$username = "root";
$password = "";
$dbname = "test_db";

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully<br>";

// Line 22-41: Session Management
session_start();

if (!isset($_SESSION['user'])) {
    $_SESSION['user'] = "guest";
}

echo "Current user: " . $_SESSION['user'] . "<br>";

// Line 42-61: Function Definitions
function greetUser($name) {
    return "Hello, " . htmlspecialchars($name) . "!<br>";
}

function calculateSum($a, $b) {
    return $a + $b;
}

// Line 62-101: Form Processing
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? 'Anonymous';
    echo greetUser($name);

    $num1 = $_POST['num1'] ?? 0;
    $num2 = $_POST['num2'] ?? 0;
    echo "The sum is: " . calculateSum($num1, $num2) . "<br>";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dummy PHP File</title>
</head>
<body>
    <!-- Line 102-121: Form for Input -->
    <form action="" method="POST">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name"><br><br>

        <label for="num1">Number 1:</label>
        <input type="number" id="num1" name="num1"><br><br>

        <label for="num2">Number 2:</label>
        <input type="number" id="num2" name="num2"><br><br>

        <input type="submit" value="Submit">
    </form>

    <hr>

<?php
// Line 122-161: Sample Data and Loop
$sampleData = [
    ["id" => 1, "name" => "Alice", "email" => "alice@example.com"],
    ["id" => 2, "name" => "Bob", "email" => "bob@example.com"],
    ["id" => 3, "name" => "Charlie", "email" => "charlie@example.com"]
];

echo "<h3>User Data:</h3>";
echo "<ul>";
foreach ($sampleData as $user) {
    echo "<li>" . $user['name'] . " (" . $user['email'] . ")</li>";
}
echo "</ul>";
?>

    <hr>

<?php
// Line 162-221: CRUD Operations
// Create
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['newUser'])) {
    $newUser = $_POST['newUser'];
    $sql = "INSERT INTO users (name) VALUES ('$newUser')";
    if ($conn->query($sql) === TRUE) {
        echo "New user added successfully<br>";
    } else {
        echo "Error: " . $conn->error . "<br>";
    }
}

// Read
$sql = "SELECT id, name FROM users";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<h3>Users from Database:</h3>";
    while ($row = $result->fetch_assoc()) {
        echo "ID: " . $row['id'] . " - Name: " . $row['name'] . "<br>";
    }
} else {
    echo "No users found<br>";
}

// Update
if (isset($_POST['updateUser'])) {
    $userId = $_POST['userId'];
    $newName = $_POST['newName'];
    $sql = "UPDATE users SET name='$newName' WHERE id=$userId";
    if ($conn->query($sql) === TRUE) {
        echo "User updated successfully<br>";
    } else {
        echo "Error: " . $conn->error . "<br>";
    }
}

// Delete
if (isset($_POST['deleteUser'])) {
    $userId = $_POST['userId'];
    $sql = "DELETE FROM users WHERE id=$userId";
    if ($conn->query($sql) === TRUE) {
        echo "User deleted successfully<br>";
    } else {
        echo "Error: " . $conn->error . "<br>";
    }
}
?>

    <!-- Line 222-241: CRUD Form -->
    <h3>Manage Users</h3>
    <form action="" method="POST">
        <label for="newUser">Add User:</label>
        <input type="text" id="newUser" name="newUser">
        <input type="submit" value="Add"><br><br>

        <label for="userId">User ID:</label>
        <input type="number" id="userId" name="userId"><br>

        <label for="newName">New Name:</label>
        <input type="text" id="newName" name="newName">
        <input type="submit" name="updateUser" value="Update"><br><br>

        <input type="submit" name="deleteUser" value="Delete">
    </form>

    <hr>

<?php
// Line 242-361: Functions and Utility
function logAction($message) {
    file_put_contents("log.txt", $message . "\n", FILE_APPEND);
}

logAction("Page accessed by user: " . $_SESSION['user']);

// Pagination Placeholder
function paginate($totalItems, $itemsPerPage, $currentPage) {
    $totalPages = ceil($totalItems / $itemsPerPage);
    echo "Page $currentPage of $totalPages<br>";
}

// Placeholder call
paginate(100, 10, 1);
?>

</body>
</html>

<?php
// Line 362-399: Final Cleanup
$conn->close();
echo "Connection closed.<br>";
?>