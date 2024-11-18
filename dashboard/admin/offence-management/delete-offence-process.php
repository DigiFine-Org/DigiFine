<?php
include '../../../db/connect.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['offence_number']) && is_numeric($_POST['offence_number'])) {
        $offence_number = intval($_POST['offence_number']);

        $sql = "DELETE FROM offences WHERE offence_number = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            die("Error preparing statement: " . $conn->error);
        }

        $stmt->bind_param("i", $offence_number);

        if ($stmt->execute()) {
            header("Location: /digifine/dashboard/admin/offence-management/index.php?message=Offence deleted successfully");
            exit();
        } else {
            die("Error deleting offence: " . $stmt->error);
        }

        if ($stmt) {
            $stmt->close();
        }
    } else {
        die("Invalid offence number.");
    }
} else {
    die("Invalid request method.");
}

if ($conn) {
    $conn->close();
}
?>