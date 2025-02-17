<?php

$pageConfig = [
    'title' => 'Publish Announcements',
    'styles' => ["../../../dashboard.css", "../announcements.css"],
    'scripts' => ["../../../dashboard.js"],
    'authRequired' => true
];

session_start();
require_once "../../../../db/connect.php";  // Ensure this file contains your database connection setup
include_once "../../../../includes/header.php";

// Ensure the user is logged in and has the role of OIC
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'oic') {
    die("Unauthorized access!");
}

// Fetch the OIC's police station from the `officers` table if not set in the session
if (!isset($_SESSION['user']['police_station'])) {
    $oicId = $_SESSION['user']['id']; // Get the logged-in user's ID
    // Use $conn instead of $pdo since you're using mysqli
    $query = "SELECT police_station FROM officers WHERE id = ? AND is_oic = 1";
    $stmt = $conn->prepare($query);  // Use $conn here for mysqli
    $stmt->bind_param("i", $oicId);  // Bind the parameter for mysqli
    $stmt->execute();
    $result = $stmt->get_result();  // Get the result set

    if ($row = $result->fetch_assoc()) {  // Use fetch_assoc() for mysqli
        $_SESSION['user']['police_station'] = $row['police_station'];
    } else {
        die("Unauthorized: Police station not found or user is not an OIC.");
    }
}

$oicStationNumber = $_SESSION['user']['police_station']; // Retrieve OIC's station number

if ($_SESSION['message'] ?? null) {
    if ($_SESSION['message'] === 'success') {
        $message = "Announcement published successfully!";
        unset($_SESSION['message']); // Clear the session message
        include '../../../../includes/alerts/success.php';
    } else {
        $message = $_SESSION['message']; // Store the message
        unset($_SESSION['message']); // Clear the session message

        // Include the alert.php file to display the message
        include '../../../../includes/alerts/failed.php';
    }
}

?>

<main>
    <?php include_once "../../../includes/navbar.php" ?>
    <div class="dashboard-layout">
        <?php include_once "../../../includes/sidebar.php" ?>
        <div class="content">
            <div class="container">
                <div id="alert-container"></div> <!-- Alert container -->
                <h1>Publish Announcements for station officers</h1>
                <form action="./process.php" method="POST">
                    <div class="field">
                        <label for="">Title</label>
                        <input type="text" name="title" placeholder="Title" required>
                    </div>

                    <div class="field">
                        <label for="">Message</label>
                        <textarea name="message" placeholder="Message" required></textarea>
                    </div>

                    <div class="field">
                        <label for="">Expires At(Optional)</label>
                        <input type="datetime-local" name="expires_at">
                    </div>

                    <div class="field">
                        <button class="btn">Add Announcement</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</main>

<?php include_once "../../../../includes/footer.php"; ?>