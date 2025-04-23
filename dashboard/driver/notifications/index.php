<?php
$pageConfig = [
    'title' => 'Notifications',
    'styles' => ["../../dashboard.css", "./notifications.css"],
    'scripts' => ["../../dashboard.js", "../../../notifications/script.js"],
    'authRequired' => true
];

session_start();
include_once "../../../includes/header.php";
require_once "../../../db/connect.php";

if ($_SESSION['user']['role'] !== 'driver') {
    die("unauthorized user!");
}

$driver_id = $_SESSION['user']['id'];
// Add this near the top of your index.php after getting $driver_id
$debug_query = "SELECT * FROM notifications WHERE reciever_id = ? AND reciever_type = 'driver' ORDER BY created_at DESC";
$debug_stmt = $conn->prepare($debug_query);

if ($debug_stmt === false) {
    echo "<div style='background-color: #ffdddd; padding: 10px; margin: 10px;'>";
    echo "<h3>Debug - SQL Prepare Error:</h3>";
    echo "Error: " . $conn->error;
    echo "</div>";
} else {
    $debug_stmt->bind_param("s", $driver_id);
    $debug_stmt->execute();
    $debug_result = $debug_stmt->get_result();

    echo "<div style='background-color: #ddffdd; padding: 10px; margin: 10px;'>";
    echo "<h3>Debug - Notifications in DB:</h3>";
    
    if ($debug_result->num_rows === 0) {
        echo "<p>No notifications found for driver ID: " . htmlspecialchars($driver_id) . "</p>";
    } else {
        while ($row = $debug_result->fetch_assoc()) {
            echo "<pre>" . print_r($row, true) . "</pre>";
        }
    }
    
    echo "</div>";
    $debug_stmt->close();
}

// Also check if the connection is valid
echo "<div style='background-color: #ddddff; padding: 10px; margin: 10px;'>";
echo "<h3>Debug - Connection Status:</h3>";
if ($conn->ping()) {
    echo "Database connection is working.";
} else {
    echo "Database connection failed: " . $conn->error;
}
echo "</div>";

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
            <h1>Notifications</h1>
            <div class="description-section">
                <div class="english">
                    <h3>View Your Important Notifications</h3>
                    <p>Stay informed about fine updates, payment reminders, and important traffic announcements.</p>
                </div>
                <div class="sinhala">
                    <h3>ඔබගේ වැදගත් දැනුම්දීම් බලන්න</h3>
                    <p>දඩ යාවත්කාලීන කිරීම්, ගෙවීම් සිහිකැඳවීම් සහ වැදගත් රථවාහන ප්‍රකාශන පිළිබඳව දැනුවත්ව සිටින්න.</p>
                </div>
            </div>

            <div id="notifications-container">
                <!-- Notifications will be loaded here via JavaScript -->
                <div class="loading">Loading notifications...</div>
            </div>

            
        </div>
    </div>
</main>

<?php include_once "../../../includes/footer.php"; ?>




<script>
    const container = document.getElementById("notifications-container");

    notification_listeners.add_listener((items) => {
        container.innerHTML = ""; // Clear container

        if (items.length === 0) {
            container.innerHTML = `
                <div class="no-notifications">
                    <p>You have no notifications at this time.</p>
                </div>
            `;
            return;
        }

        // Sort items by date (newest first)
        items.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));

        items.forEach((item) => {
            const notification = document.createElement("div");
            notification.classList.add("notification");

            if (item.is_read) {
                notification.classList.add("read");
            }

            notification.innerHTML = `
                <a href="/digifine/dashboard/driver/notifications/view.php?id=${item.id}&type=${item.type}">
                    <h3>${item.title}</h3>
                    <p class="message">${item.message.length > 100 ? item.message.substring(0, 100) + '...' : item.message}</p>
                    <div class="meta">
                        <span class="date">${new Date(item.created_at).toLocaleString()}</span>
                        <span class="source">From: ${item.source}</span>
                        <span class="read-more">Read More</span>
                    </div>
                </a>
            `;

            container.appendChild(notification);
        });

        // Add clear notifications button if there are personal notifications
        const personalNotifications = items.filter(item => item.type === "notification");
        if (personalNotifications.length > 0) {
            const clearButton = document.createElement("button");
            clearButton.classList.add("btn", "secondary");
            clearButton.innerText = "Clear All Notifications";

            clearButton.addEventListener("click", async () => {
                const hasUnread = personalNotifications.some(item => !item.is_read);

                if (hasUnread) {
                    const confirm = window.confirm("Are you sure you want to clear all notifications? There are unread notifications.");
                    if (!confirm) return;
                }

                await delete_notifications();
            });

            container.appendChild(clearButton);
        }
    });

    // Initialize notifications
    init_notifications();
</script>