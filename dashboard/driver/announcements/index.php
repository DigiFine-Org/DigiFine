<?php

$pageConfig = [
    'title' => 'Driver Announcements',
    'styles' => ["../../dashboard.css", "./announcements.css"],
    'scripts' => ["../../dashboard.js"],
    'authRequired' => true
];

session_start();
include_once "../../../includes/header.php";
require_once "../../../db/connect.php";

if ($_SESSION['user']['role'] !== 'driver') {
    die("unauthorized user!");
}

$driver_id = $_SESSION['user']['id'];


// Fetch announcements for drivers
$stmt = $conn->prepare("
    SELECT title, message, published_by, created_at, expires_at 
    FROM announcements 
    WHERE (target_role = 'driver' OR target_role = 'all')
      AND (expires_at IS NULL OR expires_at > NOW())
    ORDER BY created_at DESC
");
$stmt->execute();
$result = $stmt->get_result();

// Display announcements
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Driver Announcements</title>
    <!-- <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            padding: 0;
        }

    </style> -->
</head>

<main>

    <?php include_once "../../includes/navbar.php" ?>
    <div class="dashboard-layout">
        <?php include_once "../../includes/sidebar.php" ?>
        <div class="content">
            <button onclick="history.back()" class="back-btn" style="position: absolute; top: 7px; right: 8px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                    <path fill-rule="evenodd"
                        d="M15 8a.5.5 0 0 1-.5.5H3.707l3.147 3.146a.5.5 0 0 1-.708.708l-4-4a.5.5 0 0 1 0-.708l4-4a.5.5 0 1 1 .708.708L3.707 7.5H14.5a.5.5 0 0 1 .5.5z" />
                </svg>
            </button>
            <h1>Announcements</h1>
            <div class="description-section">
                <div class="english">
                    <h3>Urgent announcements, payment reminders, and policy changes...</h3>
                    <p>View important updates, traffic alerts, and notices from authorities. Stay informed about road
                        closures, new regulations, and other essential information.</p>
                </div>

                <div class="sinhala">
                    <!-- <h3>ඔබගේ රියදුරු බලපත්‍රය හෝ දඩ ගෙවීම් සඳහා බලපාන දැන්වීම්, ගෙවීම් සහ ප්රතිපත්ති
                        වෙනස්කම්...</h2>
                        <p>වැදගත් යාවත්කාලීන කිරීම්, ගමනාගමන දැන්වීම් සහ අනෙකුත් දැන්වීම්. මාර්ග වසාදැමීම්, නව
                            නීතිරීති සහ අනෙකුත් අත්‍යවශ්‍ය තොරතුරු සඳහා යාවත්කාලීන වන්න.</p> -->

                </div>
            </div>
            <div class="content">

                <!-- <div class="home-grid"> -->
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <div class="announcement">
                            <h3><?php echo htmlspecialchars($row['title']); ?></h3>
                            <p><?php echo nl2br(htmlspecialchars($row['message'])); ?></p>
                            <div class="meta">
                                Published By: <?php echo htmlspecialchars($row['published_by']); ?> |
                                Date: <?php echo htmlspecialchars($row['created_at']); ?>
                                <?php if ($row['expires_at']): ?>
                                    | Expires At: <?php echo htmlspecialchars($row['expires_at']); ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>No announcements available.</p>
                <?php endif; ?>

            </div>
        </div>
    </div>
    </div>
</main>



<?php include_once "../../../includes/footer.php"; ?>



</html>