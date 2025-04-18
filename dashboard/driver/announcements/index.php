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
            <div class="description-section">
                <div class="english">
                    <h3>Urgent announcements, payment reminders, and policy changes...</h3>
                    <p>View important updates, traffic alerts, and notices from authorities. Stay informed about road closures, new regulations, and other essential information.</p>
                </div>

                <div class="sinhala">
                    <h3 >ඔබගේ රියදුරු බලපත්‍රය හෝ දඩ ගෙවීම් බලපාන ජරුරතම දැන්වීම්, ගෙවීම් අභිචේතන සහ ප්රතිපත්ති වෙනස්කම්...</h2>
                    <p>වැදගත් යාවත්කාලීන කිරීම්, ගමනාගමන ඇඟවීම් සහ අධිකාරින්ගේ දැන්වීම් බලන්න. පාර වසාදැමීම්, නව නීතිරීති සහ අනෙකුත් අත්යවශ්ය තොරතුරු සඳහා යාවත්කාලීනව රැඳෙන්න.</p>
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

<style>
    
.description-section {
  margin: 20px 0;
  padding: 12px;
  background: #f8f9fa;
  border-radius: 8px;
  border-left: 4px solid #003366;
  
}
.description-section h3 {
  color: #003366;
  margin-bottom: 10px;
}
.english, .sinhala {
  margin-bottom: 20px;
}

.description-section p {
  line-height: 1.6;
}


  </style>

<?php include_once "../../../includes/footer.php"; ?>



</html>