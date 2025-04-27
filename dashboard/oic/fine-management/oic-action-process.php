<?php
require_once '../../../db/connect.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $fine_id = intval($_POST['fine_id']);
    $oic_action = htmlspecialchars($_POST['oic_action']);
    $action_type = $_POST['action_type'] ?? '';


    if (empty($fine_id) || empty($oic_action)) {
        die("Fine ID and OIC Action are required.");
    }


    $sql = "SELECT is_reported FROM fines WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $fine_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $fine = $result->fetch_assoc();
    $is_reported = $fine['is_reported'];


    if ($action_type === 'discard') {

        $points_sql = "SELECT o.points_deducted, f.driver_id 
                      FROM fines f
                      INNER JOIN offences o ON f.offence = o.description_english
                      WHERE f.id = ?";
        $points_stmt = $conn->prepare($points_sql);
        $points_stmt->bind_param("i", $fine_id);
        $points_stmt->execute();
        $points_result = $points_stmt->get_result();
        $fine_data = $points_result->fetch_assoc();
        
        if ($fine_data && isset($fine_data['points_deducted']) && $fine_data['points_deducted'] > 0) {

            $driver_id = $fine_data['driver_id'];
            $points_to_restore = $fine_data['points_deducted'];
            
            $update_points_sql = "UPDATE drivers SET points = points + ? WHERE id = ?";
            $update_stmt = $conn->prepare($update_points_sql);
            $update_stmt->bind_param("is", $points_to_restore, $driver_id);
            $update_stmt->execute();
            $update_stmt->close();
        }
        $points_stmt->close();
        

        $sql = "UPDATE fines SET is_discarded = 1, is_solved = 1, oics_action = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $oic_action, $fine_id);
    } elseif ($action_type === 'fair') {

        $sql = "UPDATE fines SET is_solved = 1, oics_action = ? WHERE id = ?"; 
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $oic_action, $fine_id);
    } else {
        die("Invalid action type.");
    }


    if ($stmt->execute()) {

        $message = ($action_type === 'discard')
            ? "Fine discarded successfully with OIC action."
            : "OIC action added successfully.";
        header("Location: /digifine/dashboard/oic/fine-management/index.php?message=" . urlencode($message));
        exit();
    } else {

        header("Location: fines.php?message=" . urlencode("Error updating fine."));
        exit();
    }

    $stmt->close();
}
$conn->close();
?>