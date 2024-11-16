<?php

include('../../../../db/connect.php');


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $fine_id = $_POST['fine_id'];
    $reason = $_POST['reason'];
    $evidence = null;

    if (isset($_FILES['evidence']) && $_FILES['evidence']['error'] === 0) {
        
        $evidence = $_FILES['evidence']['name'];
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($evidence);

        
        if ($_FILES['evidence']['size'] > 5000000) { 
            echo "Sorry, your file is too large.";
            exit;
        }

        
        $allowed_file_types = ['image/jpeg', 'image/png', 'application/pdf'];
        if (!in_array($_FILES['evidence']['type'], $allowed_file_types)) {
            echo "Sorry, only JPG, PNG, and PDF files are allowed.";
            exit;
        }

        
        if (!move_uploaded_file($_FILES["evidence"]["tmp_name"], $target_file)) {
            echo "Sorry, there was an error uploading your file.";
            exit;
        }
    }

    
    $stmt = $conn->prepare("INSERT INTO unfair_fines (fine_id, report_reason, evidence, status) VALUES (?, ?, ?, ?)");
    if ($stmt) {
        $status = 'Pending'; 
        $stmt->bind_param("isss", $fine_id, $reason, $evidence, $status); 

        if ($stmt->execute()) {
           
            header("Location: ../index.php?message=Ticket+ID+$fine_id+Reported+Successfully");
            exit(); 
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

