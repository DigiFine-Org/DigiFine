<?php
include '../../db/connect.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Check if location ID is provided and is numeric
    if (isset($_POST['location_id']) && is_numeric($_POST['location_id'])) {
        $location_id = intval($_POST['location_id']);

        // Prepare the DELETE SQL query
        $sql = "DELETE FROM duty_locations WHERE id = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            die("Error preparing statement: " . $conn->error);
        }

        // Bind the location ID to the query
        $stmt->bind_param("i", $location_id);

        // Execute the query
        if ($stmt->execute()) {
            // Redirect to the dashboard with a success message
            header("Location: /digifine/dashboard/oic/index.php?message=Duty location deleted successfully");
            exit();
        } else {
            // Handle error if the query fails
            die("Error deleting duty location: " . $stmt->error);
        }

        // Close the statement
        if ($stmt) {
            $stmt->close();
        }
    } else {
        // Handle invalid or missing location ID
        die("Invalid location ID.");
    }
} else {
    // Handle invalid request method
    die("Invalid request method.");
}

// Close the database connection
if ($conn) {
    $conn->close();
}
?>