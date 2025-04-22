<?php
$pageConfig = [
    'title' => 'Payments',
    'styles' => ["../../dashboard.css"],
    'scripts' => ["../../dashboard.js"],
    'authRequired' => true
];

include_once "../../../includes/header.php";
require_once "../../../db/connect.php";

if ($_SESSION['user']['role'] !== 'driver') {
    die("unathorized author!");
}

$driver_id = $_SESSION['user']['id'];


$payments = [];
$stmt = $conn->prepare("SELECT id AS fine_id, fine_amount, paid_at FROM fines WHERE driver_id = ? AND fine_status = 'paid' ORDER BY paid_at DESC");
$stmt->bind_param("s", $driver_id);

if ($stmt->execute()) {
    $result = $stmt->get_result();
    $payments = $result->fetch_all(MYSQLI_ASSOC);
} else {
    die("Query failed!");
}
$stmt->close();
?>


<main>
    <?php include_once "../../includes/navbar.php" ?>
    <div class="dashboard-layout">
        <?php include_once "../../includes/sidebar.php" ?>
        <div class="content">
            <button onclick="history.back()" class="back-btn" style="position: absolute; top: 7px; right: 8px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                    <path fill-rule="evenodd"
                        d="M15 8a.5.5 0 0 1-.5.5H3.707l3.147 3.146a.5.5 0 0 1-.708.708l-4-4a.5.5 0 0 1 0-.708l4-4a.5.5 0 1 1 .708.708L3.707 7.5H14.5a.5.5 0 0 1 .5.5z" />
                </svg>
            </button>
            <h1>Payments</h1>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Fine ID</th>
                            <th>Payment Date/Time</th>
                            <th>Amount Paid</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($payments) === 0): ?>
                            <tr>
                                <td colspan="4">No payments found.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($payments as $payment): ?>
                                <tr>
                                    <td><?= htmlspecialchars($payment['fine_id']) ?></td>
                                    <td><?= htmlspecialchars($payment['paid_at']) ?></td>
                                    <td><?= htmlspecialchars(number_format($payment['fine_amount'], 2)) ?>LKR</td>
                                    <td><a href="view.php?fine_id=<?= $payment['fine_id'] ?>"><button
                                                class="btn">View</button></a></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
</main>

<?php include_once "../../../includes/footer.php" ?>