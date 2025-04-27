<?php

ini_set('display_errors', 0);
error_reporting(E_ERROR);


require_once "../db/connect.php";

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

function get(string $user_id, string $user_type): array
{
    global $conn;

    $notifications_sql = "SELECT * FROM notifications WHERE reciever_id = ? AND reciever_type = ? ORDER BY created_at DESC";

    $stmt = $conn->prepare($notifications_sql);
    $stmt->bind_param("ss", $user_id, $user_type);
    $stmt->execute();
    $notifications_result = $stmt->get_result();
    $notifications = [];

    while ($row = $notifications_result->fetch_assoc()) {
        $notifications[] = [
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
            "id" => $row['id'],
            "title" => $row['title'],
            "message" => $row['message'],
            "created_at" => $row['created_at'],
            "expires_at" => $row['expires_at'],
            "is_read" => false, 
            "source" => $row['published_by'],
            "type" => "announcement"
        ];
    }

    return [
        "success" => true,
        "data" => array_merge($notifications, $announcements)
    ];
}

function delete(string $user_id, string $user_type)
{
    global $conn;

    $sql = "DELETE FROM notifications WHERE reciever_id = ? AND reciever_type = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $user_id, $user_type);
    $stmt->execute();

    return [
        "success" => true,
        "data" => "deleted"
    ];
}

$method = $_SERVER["REQUEST_METHOD"];
$function_name = strtolower($method);



header("Content-Type: application/json");
if (!function_exists($function_name)) {
    http_response_code(405);
    echo json_encode([
        "success" => false,
        "data" => "Method not allowed"
    ]);
    exit;
}

if (!isset($_SESSION['user'])) {
    echo json_encode([
        "success" => false,
        "data" => "unauthorized"
    ]);
    exit;
}

$user_id = $_SESSION['user']['id'];
$user_type = $_SESSION['user']['role'];

try {
    $res = $function_name($user_id, $user_type);
    http_response_code(200);
    echo json_encode($res);
} catch (\Throwable $th) {
    http_response_code(500);
    echo json_encode([
        "success" => false,
        "data" => $th->getMessage()
    ]);
}