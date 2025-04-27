<?php
$pageConfig = [
    'title' => 'View Notification',
    'styles' => ["../../dashboard.css", "./notifications.css"],
    'scripts' => ["../../dashboard.js", "./oic-notification-scripts.js"],
    'authRequired' => true
];

session_start();
include_once "../../../includes/header.php";
require_once "../../../db/connect.php";

if ($_SESSION['user']['role'] !== 'oic') {
    die("unauthorized user!");
}
$oic_id = $_SESSION['user']['id'];

// Get notification ID and type from URL
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$type = isset($_GET['type']) ? htmlspecialchars($_GET['type']) : 'notification';

if (!$id) {
    header("Location: ./");
    exit;
}

// Fetch notification details
if ($type === 'announcement') {
    $sql = "SELECT * FROM announcements WHERE id = ? LIMIT 1";
} else {
    $sql = "SELECT * FROM notifications WHERE id = ? AND reciever_id = ? AND reciever_type = 'oic' LIMIT 1";
}

$stmt = $conn->prepare($sql);
if ($type === 'announcement') {
    $stmt->bind_param("i", $id);
} else {
    $stmt->bind_param("is", $id, $oic_id);

    // Mark as read
    $update_sql = "UPDATE notifications SET is_read = 1 WHERE id = ? AND reciever_id = ? AND reciever_type = 'oic'";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("is", $id, $oic_id);
    $update_stmt->execute();
}

$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<script>alert('Notification not found.'); window.location.href = './';</script>";
    exit;
}

$notification = $result->fetch_assoc();
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

            <div class="notification-detail">
                <h1><?php echo htmlspecialchars($notification['title']); ?></h1>

                <div class="meta-info">
                    <?php if ($type === 'announcement'): ?>
                        <p class="source">From: <?php echo htmlspecialchars($notification['published_by']); ?></p>
                    <?php else: ?>
                        <p class="source">From: <?php echo htmlspecialchars($notification['source']); ?></p>
                    <?php endif; ?>

                    <p class="date">Date: <?php echo date('Y-m-d h:i A', strtotime($notification['created_at'])); ?></p>

                    <?php if (!empty($notification['expires_at'])): ?>
                        <p class="expiry">Expires:
                            <?php echo date('Y-m-d h:i A', strtotime($notification['expires_at'])); ?>
                        </p>
                    <?php endif; ?>
                </div>

                <div class="message-content">
                    <p><?php echo nl2br(htmlspecialchars($notification['message'])); ?></p>
                </div>
            </div>
        </div>
    </div>
</main>