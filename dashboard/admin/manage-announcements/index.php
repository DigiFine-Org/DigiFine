<?php
$pageConfig = [
    'title' => 'Manage Announcements',
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

// Fetch all announcements
$query = "SELECT * FROM announcements ORDER BY created_at DESC";
$result = $conn->query($query);

if (isset($_SESSION['message'])) { // Ensure the key exists before accessing it
    if ($_SESSION['message'] === 'updated') {
        $message = "Announcement Updated successfully!";
        unset($_SESSION['message']);
        include '../../../includes/alerts/success.php';
    } elseif ($_SESSION['message'] === 'deleted') {
        $message = "Announcement Deleted successfully!";
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
            <!-- <div class="container"> -->
            <h1 class="page-title">Manage Announcements</h1>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Published By</th>
                        <th>Message</th>
                        <th>Target Role</th>
                        <th>Created At</th>
                        <th>Expires At</th>
                        <th>Edit</th>
                        <th>Delete</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($announcement = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $announcement['id']; ?></td>
                            <td><?php echo htmlspecialchars($announcement['title']); ?></td>
                            <td><?php echo htmlspecialchars($announcement['published_by']); ?></td>
                            <td><?php echo htmlspecialchars($announcement['message']); ?></td>
                            <td><?php echo htmlspecialchars($announcement['target_role']); ?></td>
                            <td><?php echo htmlspecialchars($announcement['created_at']); ?></td>
                            <td><?php echo $announcement['expires_at'] ? htmlspecialchars($announcement['expires_at']) : 'N/A'; ?></td>
                            <td><a href="edit.php?id=<?php echo $announcement['id']; ?>" class="btn btn-edit">Edit</a></td>
                            <td><a href="delete.php?id=<?php echo $announcement['id']; ?>" class="btn btn-delete" style="background-color: crimson;">Delete</a></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
    </div>
</main>

<?php include_once "../../../includes/footer.php"; ?>