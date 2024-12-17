<?php
session_start();
require_once "../../../db/connect.php";

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'oic') {
    die("Unauthorized access!");
}

$oicStationNumber = $_SESSION['user']['station_number']; // Get OIC's station number

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $message = $_POST['message'];
    $expiresAt = $_POST['expires_at'] ?? null;

    // Target role is automatically "officers in the same station"
    $targetRole = "officer";

    // Insert announcement into the database
    $insertQuery = "INSERT INTO announcements (title, message, target_role, expires_at, police_station) 
                    VALUES (?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($insertQuery);
    $stmt->execute([$title, $message, $targetRole, $expiresAt, $oicStationNumber]);

    $_SESSION['message'] = 'success';
    header("Location: add.php");
    exit();
} else {
    die("Invalid request method!");
}
?>


<main>
    <?php include_once "../../includes/navbar.php" ?>
    <div class="dashboard-layout">
        <?php include_once "../../includes/sidebar.php" ?>
        <div class="content">
            <div class="container">
                <div id="alert-container"></div> <!-- Alert container -->
                <h1>Add Announcement</h1>
                <form action="process.php" method="POST">
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

<?php include_once "../../../includes/footer.php"; ?>