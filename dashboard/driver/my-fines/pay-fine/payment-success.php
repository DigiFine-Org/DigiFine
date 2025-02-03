<?php
session_start();
$pageConfig = [
    'title' => 'Payment Success',
    'styles' => ["../../../dashboard.css"],
    'authRequired' => true
];

require_once "../../../../db/connect.php";
include_once "../../../../includes/header.php";

// Jan 29 Update 16:22

// Ensure the user is a driver
if($_SESSION['user']['role'] !== 'driver') {
    die("Unathorized user!");
}

$driver_id = $_SESSION['user']['id'] ?? null;

// Retrieve and sanitize the order ID
$fine_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;


if ($fine_id <=0 || !$driver_id) {
    die("Invalid payment reference or unathorized access.");
}

$sql = "SELECT fine_status FROM fines WHERE id = ? AND driver_id = ?";
$stmt = $conn->prepare($sql);
if(!$stmt) {
    die("Query preparation failed: " . $conn->error);
}



$stmt->bind_param("ii", $fine_id, $driver_id);
// var_dump($stmt);
// exit();
if (!$stmt->execute()) {
    
    die("Error executing query: " . $stmt->error);
}



$result = $stmt->get_result();
if ($result->num_rows === 0) {
    die("No matching fine found or unathorized access.");
}

$fine = $result->fetch_assoc();

// CHeck fine is already paid
if($fine['fine_status'] === 'paid') {
    $message = "Your payment has already been recorded.";
} else {
    // Update fine statud in db
    $updateSql = "UPDATE fines SET fine_status = 'paid' WHERE id = ?";
    $updateStmt = $conn->prepare($updateSql);
    if(!$updateStmt) {
        die("Error preparing update statement: " .$conn->error);
    }

    $updateStmt->bind_param("i", $fine_id);
    if(!$updateStmt->execute()) {
        die("Error updating fine status: " .$updateStmt->error);
    }

    $message = "Your payment was successfully processed. Thank you for paying the fine.";
}

$stmt->close();
// $updateStmt->close();
$conn->close();


// Jan 29,30 Update 16:22

?>
<main>
    <?php include_once "../../../includes/navbar.php"; ?>
    <div class="dashboard-layout">
        <?php include_once "../../../includes/sidebar.php" ?>

        <div class="content">
            <div class="container large">
                <h1>Payment Successful</h1>
                <p style="margin-bottom: 10px;">Your payment was successfully processed. Thank you for paying the fine.</p>
                <a href="/digifine/dashboard/driver/my-fines/" class="btn">Back to Fines</a>
            </div>
        </div>
    </div>
</main>
<?php include_once "../../../../includes/footer.php"; ?>