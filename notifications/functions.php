<?php
require_once __DIR__ . "/../db/connect.php";

function notify_user(string $user_type, string $user_id, string $title, string $message, string $source = "system", $expires_at = null)
{
    global $conn;

    $sql = "INSERT INTO notifications (title, message, reciever_id, reciever_type, source, expires_at) VALUES (?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $title, $message, $user_id, $user_type, $source, $expires_at);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        return true;
    }

    return false;
}

function notify_driver(string $driver_id, string $title, string $message, string $source = "system", $expires_at = null)
{
    return notify_user("driver", $driver_id, $title, $message, $source, $expires_at);
}

function notify_officer(string $police_id, string $title, string $message, string $source = "system", $expires_at = null)
{
    return notify_user("officer", $police_id, $title, $message, $source, $expires_at);
}

function notify_oic(string $police_id, string $title, string $message, string $source = "system", $expires_at = null)
{
    return notify_user("oic", $police_id, $title, $message, $source, $expires_at);
}

// function notify_admin(int $id, string $title, string $message, string $source = "system", $expires_at = null)
// {
//     return notify_user("oic", $police_id, $title, $message, $source, $expires_at);
// }

// Create a new announcement

function create_announcement($title, $message, $target_role, $published_by, $expires_at = null)
{
    global $conn;

    $sql = "INSERT INTO announcements (title, message, target_role, published_by, expires_at) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $title, $message, $target_role, $published_by, $expires_at);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        return true;
    }

    return false;
}