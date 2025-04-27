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

<main class="dashboard-main">
    <?php include_once "../../../includes/navbar.php"; ?>
    <div class="dashboard-layout">
        <?php include_once "../../../includes/sidebar.php"; ?>
        <div class="content">
            <div class="container">
                <button onclick="history.back()" class="back-btn" style="position: absolute; top: 7px; right: 8px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        viewBox="0 0 16 16">
                        <path fill-rule="evenodd"
                            d="M15 8a.5.5 0 0 1-.5.5H3.707l3.147 3.146a.5.5 0 0 1-.708.708l-4-4a.5.5 0 0 1 0-.708l4-4a.5.5 0 1 1 .708.708L3.707 7.5H14.5a.5.5 0 0 1 .5.5z" />
                    </svg>
                </button>
                <div id="alert-container"></div> <!-- Alert container -->
                <h1 class="page-title">Publish Announcements for station officers</h1>
                <form action="./process.php" method="POST" id="publishAnnouncementForm">
                    <div class="field">
                        <label for="title">Title</label>
                        <input type="text" name="title" id="title" class="input" placeholder="Title" required>
                    </div>

                    <div class="field">
                        <label for="message">Message</label>
                        <textarea id="message" name="message" class="input" rows="10" style="min-height: 200px; width: 100%;" required></textarea>
                    </div>

                    <div class="field">
                        <label for="expires_at">Expires At (Optional)</label>
                        <input type="datetime-local" name="expires_at" id="expires_at" class="input">
                    </div>

                    <div class="field">
                        <button type="submit" class="btn" id="submitBtn">Add Announcement</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>

<?php include_once "../../../../includes/footer.php"; ?>