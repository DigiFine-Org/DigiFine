<?php
$pageConfig = [
    'title' => 'Reported Unfair Fines',
    'styles' => ["../../dashboard.css"],
    'scripts' => ["../../dashboard.js"],
    'authRequired' => true
];

include_once "../../../includes/header.php";
require_once "../../../db/connect.php";

$unfairFines = [];
$stmt = $conn->prepare("SELECT unfair_fine_id, fine_id, driver_id, report_reason, report_date, status, evidence FROM unfair_fines WHERE status = 'Pending'");
if (!$stmt->execute()) {
    die("Query error!!!");
}

$result = $stmt->get_result();
$unfairFines = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<main>
    <?php include_once "../../includes/navbar.php" ?>

    <div class="dashboard-layout">
        <?php include_once "../../includes/sidebar.php" ?>
        <div class="content">
            <div class="container x-large no-border">
                <div class="table-container">
                    <?php if (empty($unfairFines)): ?>
                        <p>No pending unfair fines at the moment.</p>
                    <?php else: ?>
                        <table>
                            <thead>
                                <tr>
                                    <th>Report ID</th>
                                    <th>Fine ID</th>
                                    <th>Driver ID</th>
                                    <th>Reason</th>
                                    <th>Report Date</th>
                                    <th>Evidence</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($unfairFines as $row): ?>
                                    <tr>
                                        <td><?= $row['unfair_fine_id']; ?></td>
                                        <td><?= $row['fine_id']; ?></td>
                                        <td><?= $row['driver_id']; ?></td>
                                        <td><?= htmlspecialchars($row['report_reason']); ?></td>
                                        <td><?= $row['report_date']; ?></td>
                                        <td>
                                            <?php if ($row['evidence']): ?>
                                                <a href="uploads/<?= htmlspecialchars($row['evidence']); ?>" target="_blank">View Evidence</a>
                                            <?php else: ?>
                                                No Evidence
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <form action="update-unfair-status.php" method="POST" style="display:inline;">
                                                <input type="hidden" name="unfair_fine_id" value="<?= $row['unfair_fine_id']; ?>">
                                                <button type="submit" name="status" value="Unfair">Mark as Unfair</button>
                                                <button type="submit" name="status" value="Fair">Mark as Fair</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</main>