<?php
if (isset($_GET['fine_id'])) {
    include 'db.php';
    $fine_id = mysqli_real_escape_string($conn, $_GET['fine_id']);
    $query = "DELETE FROM fines WHERE fine_id = $fine_id";
    $result = mysqli_query($conn, $query);
    if ($result) {
        echo "<script>
            alert('Fine deleted successfully');
            window.location.href = 'fine-database.php';
        </script>";
    } else {
        echo "<script>
            alert('Error deleting fine: " . mysqli_error($conn) . "');
        </script>";
    }
}
