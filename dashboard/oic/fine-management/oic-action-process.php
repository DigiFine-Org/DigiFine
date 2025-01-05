<?php
require_once '../../../db/connect.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $fine_id = intval($_POST['fine_id']);
    $oic_action = htmlspecialchars($_POST['oic_action']);
    $action_type = $_POST['action_type'] ?? '';

    // Validate inputs
    if (empty($fine_id) || empty($oic_action)) {
        die("Fine ID and OIC Action are required.");
    }

    // Check if the fine has been reported
    $sql = "SELECT is_reported FROM fines WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $fine_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $fine = $result->fetch_assoc();
    $is_reported = $fine['is_reported'];

    if ($is_reported == 1) {
        // Fine has been reported, display the "Go Back" button and prevent further actions
        echo "<p>This fine has already been reported. You cannot perform any actions on it.</p>";
        echo '<a href="index.php" class="btn">Go Back to Fines</a>';
        exit();
    }

    // Proceed with action if fine is not reported
    if ($action_type === 'discard') {
        // Update the fine as discarded and add OIC action
        $sql = "UPDATE fines SET is_discarded = 1, oics_action = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $oic_action, $fine_id);
    } elseif ($action_type === 'fair') {
        // Update only the OIC action, leave is_discarded as 0
        $sql = "UPDATE fines SET oics_action = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $oic_action, $fine_id);
    } else {
        die("Invalid action type.");
    }

    // Execute the query
    if ($stmt->execute()) {
        // Redirect back to fines page with success message
        $message = ($action_type === 'discard')
            ? "Fine discarded successfully with OIC action."
            : "OIC action added successfully.";
        header("Location: /digifine/dashboard/oic/fine-management/index.php?message=" . urlencode($message));
        exit();
    } else {
        // Redirect back with error message
        header("Location: fines.php?message=" . urlencode("Error updating fine."));
        exit();
    }

    $stmt->close();
}
$conn->close();
?>
