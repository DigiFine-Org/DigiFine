<?php
$pageConfig = [
    'title' => 'Edit Announcements',
    'styles' => ["../../dashboard.css", "./announcements.css"],
    'scripts' => ["../../dashboard.js"],
    'authRequired' => true
];

require_once "../../../db/connect.php";
include_once "../../../includes/header.php";

// session_start();

// Ensure the user is logged in and has the role of OIC
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'oic') {
    die("Unauthorized access!");
}

// Fetch the announcement to edit
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("SELECT title, message, expires_at FROM announcements WHERE id = ? AND published_id = ?");
    $stmt->bind_param("ii", $id, $_SESSION['user']['id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $announcement = $result->fetch_assoc();
    if (!$announcement) {
        die("Announcement not found or unauthorized access.");
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = intval($_POST['id']);
    $title = $_POST['title'];
    $message = $_POST['message'];
    $expires_at = $_POST['expires_at'] ?? null;

    $stmt = $conn->prepare("UPDATE announcements SET title = ?, message = ?, expires_at = ? WHERE id = ? AND published_id = ?");
    $stmt->bind_param("sssii", $title, $message, $expires_at, $id, $_SESSION['user']['id']);
    if ($stmt->execute()) {
        $_SESSION['message'] = 'successfully updated';
        header("Location: index.php");
        exit;
    } else {
        $_SESSION['message'] = 'Failed to update announcement.';
        header("Location: edit.php?id=$id");
        exit;
    }
} else {
    die("Invalid request.");
}

// Display success or failure messages
if ($_SESSION['message'] ?? null) {
    if ($_SESSION['message'] === 'success') {
        $message = "Announcement updated successfully!";
        unset($_SESSION['message']);
        include '../../../includes/alerts/success.php';
    } else {
        $message = $_SESSION['message'];
        unset($_SESSION['message']);
        include '../../../includes/alerts/failed.php';
    }
}
?>

<main class="dashboard-main">
    <?php include_once "../../includes/navbar.php"; ?>
    <div class="dashboard-layout">
        <?php include_once "../../includes/sidebar.php"; ?>
        <div class="content">
            <div class="container">
                <button onclick="history.back()" class="back-btn" style="position: absolute; top: 7px; right: 8px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M15 8a.5.5 0 0 1-.5.5H3.707l3.147 3.146a.5.5 0 0 1-.708.708l-4-4a.5.5 0 0 1 0-.708l4-4a.5.5 0 1 1 .708.708L3.707 7.5H14.5a.5.5 0 0 1 .5.5z" />
                    </svg>
                </button>
                <div id="alert-container"></div>
                <h1>Edit Announcement</h1>
                <form method="POST" action="edit.php" id="editAnnouncementForm">
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
                    <div class="field">
                        <label for="title">Title</label>
                        <input type="text" id="title" name="title" class="input" value="<?php echo htmlspecialchars($announcement['title']); ?>" required>
                    </div>

                    <div class="field">
                        <label for="message">Message</label>
                        <textarea id="message" name="message" class="input" rows="10" style="min-height: 200px; width: 100%;" required>
                            <?php echo htmlspecialchars($announcement['message']); ?></textarea>
                    </div>

                    <div class="field">
                        <label for="expires_at">Expires At (Optional)</label>
                        <input type="datetime-local" id="expires_at" name="expires_at" class="input"
                            value="<?php echo htmlspecialchars($announcement['expires_at'] ?? ''); ?>">
                    </div>

                    <div class="field">
                        <button type="submit" class="btn" id="submitBtn">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>

<?php include_once "../../../includes/footer.php"; ?>