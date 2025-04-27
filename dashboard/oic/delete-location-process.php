<?php
include '../../db/connect.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if (isset($_POST['location_id']) && is_numeric($_POST['location_id'])) {
        $location_id = intval($_POST['location_id']);


        $sql = "DELETE FROM duty_locations WHERE id = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            die("Error preparing statement: " . $conn->error);
        }

        $stmt->bind_param("i", $location_id);


        if ($stmt->execute()) {

            header("Location: /digifine/dashboard/oic/index.php?message=Duty location deleted successfully");
            exit();
        } else {

            die("Error deleting duty location: " . $stmt->error);
        }


        if ($stmt) {
            $stmt->close();
        }
    } else {

        die("Invalid location ID.");
    }
} else {

    die("Invalid request method.");
}


if ($conn) {
    $conn->close();
}
?>