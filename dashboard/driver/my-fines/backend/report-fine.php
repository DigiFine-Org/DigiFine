<?php
$pageConfig = [
    'title' => 'Report Unfair Fine',
    'styles' => ["../../../../dashboard.css", "../report-fine.css"],
    'scripts' => ["../../../../dashboard.js"],
    'authRequired' => true
];

if (isset($_GET['fine_id'])) {
    $fine_id = $_GET['fine_id']; 
    $driver_id = $_GET['driver_id']; 
} else {
    // Redirect back if no fine_id is provided
    header("Location: index.php");
    exit();
}
?>
<main>
    <div class="form-container">
        <h2>Report Unfair Fine</h2>

        <form action="submit-unfair-fine.php" method="POST">
            <input type="hidden" name="fine_id" value="<?= $fine_id; ?>"> 
            <input type="hidden" name="driver_id" value="<?= $driver_id; ?>"> 
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

</main>

