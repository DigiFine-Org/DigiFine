<?php
$pageConfig = [
    'title' => 'Announcements',
    'styles' => ["../../dashboard.css", "./announcements.css"],
    'scripts' => ["../../dashboard.js"],
    'authRequired' => true
];

require_once "../../../db/connect.php";
include_once "../../../includes/header.php";


$userId = $_SESSION['user']['id'] ?? null;  // Get user ID from session
if (!$userId) {
    die("User ID not found in session.");
}

$query = "SELECT police_station FROM officers WHERE id = ?";
$stmt = $conn->prepare($query);
if ($stmt === false) {
    die('MySQL prepare failed for officers query: ' . $conn->error);
}
$stmt->bind_param("i", $userId);
if (!$stmt->execute()) {
    die('Execute failed for officers query: ' . $stmt->error);
}
$stmt->bind_result($stationNumber);
$stmt->fetch();
$stmt->close();

if (!$stationNumber) {
    die("Officer's station not found. Station number is empty.");
}

// Fetch announcements for officers from the same station or those targeting all officers
$stmt = $conn->prepare("
    SELECT title, message, published_by, created_at, expires_at, police_station
    FROM announcements 
    WHERE 
        ((target_role = 'officer' AND (police_station = ? OR police_station IS NULL)) 
        OR (target_role = 'all'))
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
            <button onclick="history.back()" class="back-btn" style="position: absolute; top: 7px; right: 8px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                    <path fill-rule="evenodd"
                        d="M15 8a.5.5 0 0 1-.5.5H3.707l3.147 3.146a.5.5 0 0 1-.708.708l-4-4a.5.5 0 0 1 0-.708l4-4a.5.5 0 1 1 .708.708L3.707 7.5H14.5a.5.5 0 0 1 .5.5z" />
                </svg>
            </button>
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