<?php

if (isset($_GET['fine_id'])) {
    $fine_id = $_GET['fine_id']; // Retrieve the fine_id from the URL
} else {
    // Redirect back if no fine_id is provided
    header("Location: driver-dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report Unfair Fine</title>
    <link rel="stylesheet" href="../assets/css/report-fine.css">
</head>
<body>
    <div class="form-container">
        <h2>Report Unfair Fine</h2>

        <form action="submit-unfair-fine.php" method="POST">
            <input type="hidden" name="fine_id" value="<?= $fine_id; ?>"> 
            <div class="field">
                <label for="reason">Reason for reporting this fine:</label><br>
                <textarea id="reason" name="reason" rows="5" cols="50" placeholder="Explain why this fine is unfair"></textarea><br><br>
            </div>
            <div class="field">
                <label for="evidence">Upload Evidence (optional):</label><br>
                <input type="file" id="evidence" name="evidence"><br><br>
            </div>
            <button type="submit" class="btn">Submit Report</button>

        </form>
    </div>

</body>
</html>
