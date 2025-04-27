<?php
$pageConfig = [
    'title' => 'Edit Announcement',
    'styles' => ["../../dashboard.css", "../announcements.css"],
    'scripts' => ["../../dashboard.js"],
    'authRequired' => true
];

session_start();
require_once "../../../db/connect.php";
include_once "../../../includes/header.php";

// Ensure the user is logged in and has the role of admin
if ($_SESSION['user']['role'] !== 'admin') {
    die("Unauthorized access!");
}

// Fetch the announcement to edit
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("SELECT * FROM announcements WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $announcement = $result->fetch_assoc();
    if (!$announcement) {
        die("Announcement not found.");
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = intval($_POST['id']);
    $title = $_POST['title'];
    $message = $_POST['message'];
    $target_role = $_POST['target_role'];
    $expires_at = $_POST['expires_at'] ?: null;

    $stmt = $conn->prepare("UPDATE announcements SET title = ?, message = ?, target_role = ?, expires_at = ? WHERE id = ?");
    $stmt->bind_param("ssssi", $title, $message, $target_role, $expires_at, $id);
    if ($stmt->execute()) {
        $_SESSION['message'] = 'updated';
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
?>

<main class="dashboard-main">
    <?php include_once "../../includes/navbar.php"; ?>
    <div class="dashboard-layout">
        <?php include_once "../../includes/sidebar.php"; ?>
        <div class="content">
            <div class="container">
                <h1 class="page-title">Edit Announcement</h1>
                <form method="POST" action="edit.php" class="form-container">
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
                    <div class="field">
                        <label for="title">Title</label>
                        <input type="text" id="title" name="title" class="input" value="<?php echo htmlspecialchars($announcement['title']); ?>" required>
                    </div>

                    <div class="field">
                        <label for="message">Message</label>
                        <textarea id="message" name="message" class="input" rows="10" style="min-height: 200px; width: 100%;" required><?php echo htmlspecialchars($announcement['message']); ?></textarea>
                    </div>

                    <div class="field">
                        <label for="target_role">Target Role</label>
                        <select id="target_role" name="target_role" class="input" required>
                            <option value="all" <?php echo $announcement['target_role'] === 'all' ? 'selected' : ''; ?>>All Users</option>
                            <option value="admin" <?php echo $announcement['target_role'] === 'admin' ? 'selected' : ''; ?>>Admin</option>
                            <option value="oic" <?php echo $announcement['target_role'] === 'oic' ? 'selected' : ''; ?>>OIC</option>
                            <option value="officer" <?php echo $announcement['target_role'] === 'officer' ? 'selected' : ''; ?>>Officer</option>
                            <option value="driver" <?php echo $announcement['target_role'] === 'driver' ? 'selected' : ''; ?>>Driver</option>
                        </select>
                    </div>

                    <div class="field">
                        <label for="expires_at">Expires At (Optional)</label>
                        <input type="datetime-local" id="expires_at" name="expires_at" class="input" value="<?php echo htmlspecialchars($announcement['expires_at'] ?? ''); ?>">
                    </div>

                    <div class="field">
                        <button type="submit" class="btn">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>

<?php include_once "../../../includes/footer.php"; ?>