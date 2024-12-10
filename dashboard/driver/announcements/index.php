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
            <h1>Announcements</h1>
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