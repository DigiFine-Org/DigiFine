<?php
$pageConfig = [
    'title' => 'Announcements',
    'styles' => ["../../dashboard.css", "./announcements.css"],
    'scripts' => ["../../dashboard.js"],
    'authRequired' => true
];

require_once "../../../db/connect.php";
include_once "../../../includes/header.php";

if ($_SESSION['user']['role'] !== 'oic') {
    die("Unauthorized user!");
}

// Fetch announcements for drivers
$stmt = $conn->prepare("
    SELECT id, title, message, published_by, published_id, created_at, expires_at 
    FROM announcements 
    WHERE (target_role = 'oic' OR target_role = 'all')
      AND (expires_at IS NULL OR expires_at > NOW())
    ORDER BY created_at DESC
");
$stmt->execute();
$result = $stmt->get_result();

// Fetch announcements published by the logged-in user
$userStmt = $conn->prepare("
    SELECT id, title, message, created_at, expires_at 
    FROM announcements 
    WHERE published_id = ?
    ORDER BY created_at DESC
");
$userStmt->bind_param("i", $_SESSION['user']['id']);
$userStmt->execute();
$userResult = $userStmt->get_result();

if ($_SESSION['message'] ?? null) {
    if ($_SESSION['message'] === 'success') {
        $message = "Announcement Deleted successfully!";
        unset($_SESSION['message']); // Clear the session message
        include '../../../includes/alerts/success.php';
    } else {
        $message = $_SESSION['message']; // Store the message
        unset($_SESSION['message']); // Clear the session message

        // Include the alert.php file to display the message
        include '../../../includes/alerts/failed.php';
    }
}
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
            <h2>All Announcements</h2>
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

            <h2>Your Published Announcements</h2>
            <?php if ($userResult->num_rows > 0): ?>
                <?php while ($row = $userResult->fetch_assoc()): ?>
                    <div class="announcement">
                        <h3><?php echo htmlspecialchars($row['title']); ?></h3>
                        <p><?php echo nl2br(htmlspecialchars($row['message'])); ?></p>
                        <div class="meta">
                            Date: <?php echo htmlspecialchars($row['created_at']); ?>
                            <?php if ($row['expires_at']): ?>
                                | Expires At: <?php echo htmlspecialchars($row['expires_at']); ?>
                            <?php endif; ?>
                        </div>
                        <div class="actions" style="display: flex; gap: 5px; margin-top: 5px;">
                            <a href="edit.php?id=<?php echo $row['id']; ?>" class="btn btn-edit" style="font-size: 12px; padding: 2px 20px;">Edit</a>
                            <a href="delete.php?id=<?php echo $row['id']; ?>" class="btn btn-delete" style="font-size: 12px; padding: 2px 13px; background-color:crimson">Delete</a>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>You have not published any announcements.</p>
            <?php endif; ?>
        </div>
    </div>
</main>

<?php include_once "../../../includes/footer.php"; ?>