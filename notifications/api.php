<?php
require_once __DIR__ . "/../db/connect.php";

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

function get(int $user_id, string $user_type): array
{
    global $conn;

    try {
        // Get user's notifications
        $notifications_sql = "SELECT* FROM notifications WHERE reciever_id = ? AND reciever_type = ? ORDER BY created_at DESC";

        $stmt = $conn->prepare($notifications_sql);
        $stmt->bind_param("is", $user_id, $user_type);
        $stmt->execute();
        $notifications_result = $stmt->get_result();
        $notifications = [];

        while ($row = $notifications_result->fetch_assoc()) {
            $notificaions[] = [
                "id" => $row['id'],
                "title" => $row['title'],
                "message" => $row['message'],
                "created_at" => $row['created_at'],
                "expires_at" => $row['expires_at'],
                "is_read" => $row['is_read'],
                "source" => $row['source'],
                "type" => "notification"
            ];
        }

        // Get announcements relevant to this user
        $announcements_sql = "SELECT * FROM announcements 
                           WHERE (target_role = ? OR target_role = 'all')
                           AND (expires_at IS NULL OR expires_at >= NOW())
                           ORDER BY created_at DESC";

        $stmt = $conn->prepare($announcements_sql);
        $stmt->bind_param("s", $user_type);
        $stmt->execute();
        $announcements_result = $stmt->get_result();
        $announcements = [];

        while ($row = $announcements_result->fetch_assoc()) {
            $announcements[] = [
                "id" => $row[id],
                "title" => $row['title'],
                "message" => $row['message'],
                "created_at" => $row['created_at'],
                "expires_at" => $row['expires_at'],
                "is_read" => false, // Announcements are always marked as unread
                "source" => $row['published_by'],
                "type" => "announcement"
            ];
        }

        return [
            "success" => true,
            "data" => array_merge($notificaions, $announcements)
        ];
    } catch (Exception $e) {
        return [
            "success" => false,
            "data" => $e->getMessage()
        ];
    }
}

function mark_read(int $notification_id)
{
    global $conn;

    try {
        $sql = "UPDATE notifications SET is_read = 1 WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $notification_id);
        $stmt->execute();

        return [
            "success" => true,
            "data" => "marked as read"
        ];
    } catch (Exception $e) {
        return [
            "success" => false,
            "data" => $e->getMessage()
        ];
    }
}

function delete(int $user_id, string $user_type)
{
    global $conn;

    try {
        $sql = "DELETE FROM notifications WHERE reciever_id = ? AND reciever_type = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("is", $user_id, $user_type);
        $stmt->execute();

        return [
            "success" => true,
            "data" => "deleted"
        ];
    } catch (Exception $e) {
        return [
            "success" => false,
            "data" => $e->getMessage()
        ];
    }
}

function get_notification_by_id(int $id, string $type = "notification"): array
{
    global $conn;

    try {
        if ($type === "notificaion") {
            $sql = "SELECT * FROM notifications WHERE id = ? LIMIT 1";
        } else {
            $sql = "SELECT * FROM announcements WHERE id = ? LIMIT 1";
        }

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            throw new Exception("Notification not found");
        }

        $data = $result->fetch_assoc();

        if ($type === "notification") {
            // Mark notification as read
            $update_sql = "UPDATE notifications SET is_read = 1 WHERE id = ?";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bind_param("i", $id);
            $update_stmt->execute();
        }

        return [
            "success" => true,
            "data" => $data
        ];

    } catch (Exception $e) {
        return [
            "success" => false,
            "data" => $e->getMessage()
        ];
    }
}

$method = $_SERVER["REQUEST_METHOD"];
$function_name = strtolower($method);


// Handle GET requests with action parameter (for viewing a specific notification)
if ($method === "GET" && isset($_GET['action']) && $_GET['action'] === "view" && isset($_GET['id'])) {
    header("Content-Type: application/json");

    if (!isset($_SESSION['user'])) {
        echo json_encode([
            "success" => false,
            "data" => "unauthorized"
        ]);
        exit;
    }

    $id = (int) $_GET['id'];
    $type = isset($_GET['type']) ? $_GET['type'] : "notification";

    $res = get_notification_by_id($id, $type);

    http_response_code(200);
    echo json_encode($res);
    exit;
}

// Handle regular API requests
if (function_exists($function_name)) {
    header("Content-Type: application/json");

    if (!isset($_SESSION['user'])) {
        echo json_encode([
            "success" => false,
            "data" => "unauthorized"
        ]);
        exit;
    }

    $user_id = $_SESSION['user']['id'];
    $user_type = $_SESSION['user']['role'];

    $res = $function_name($user_id, $user_type);

    http_response_code(200);
    echo json_encode($res);
}