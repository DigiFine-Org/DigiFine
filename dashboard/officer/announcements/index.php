<?php
$pageConfig = [
    'title' => 'Announcements',
    'styles' => ["../../dashboard.css", "./announcements.css"],
    'scripts' => ["../../dashboard.js"],
    'authRequired' => true
];

require_once "../../../db/connect.php";
include_once "../../../includes/header.php";

if ($_SESSION['user']['role'] !== 'officer') {
    die("Unauthorized user!");
}

// $userId = $_SESSION['user']['id'];  // Get user ID from session
// $query = "SELECT police_station FROM officers WHERE id = ?";
// $stmt = $conn->prepare($query);
// $stmt->bind_param("i", $userId);
// $stmt->execute();
// $stmt->bind_result($stationNumber);
// $stmt->fetch();

// if (!$stationNumber) {
//     die("Officer's station not found.");
// }

// Fetch announcements for officers from the same station or those targeting all officers
$stmt = $conn->prepare("
    SELECT title, message, published_by, created_at, expires_at, police_station
    FROM announcements 
    WHERE 
        (target_role = 'officer' AND (police_station = ? OR police_station IS NULL)) 
        OR target_role = 'all'
        AND (expires_at IS NULL OR expires_at > NOW())
    ORDER BY created_at DESC
");

if ($stmt === false) {
    // Error preparing the statement
    die('MySQL prepare failed: ' . $conn->error);
}

$stmt->bind_param("i", $stationNumber);  // "i" for integer
$stmt->execute();
$result = $stmt->get_result();

?>

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