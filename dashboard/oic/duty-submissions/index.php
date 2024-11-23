<?php
$pageConfig = [
    'title' => 'Duty Submissions',
    'styles' => ["../../dashboard.css"],
    'scripts' => ["../../dashboard.js"],
    'authRequired' => true
];

include_once "../../../includes/header.php";

$result = "";

if (isset($_GET)) {
    $result = $_GET['query'] ?? "";
}
?>

<main>
    <?php include_once "../../includes/navbar.php" ?>
    <div class="dashboard-layout">
        <?php include_once "../../includes/sidebar.php" ?>
        <div class="content">
            <h1>Duty Submissions</h1>
            <table>
                    <thead>
                        <tr>
                            <th>Submission Number</th>
                            <th>Police ID</th>
                            <th>Officer Name</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <!-- <tbody>
                        <?php foreach ($offences as $offence): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($offence['offence_number']); ?></td>
                                <td><?php echo htmlspecialchars($offence['description_sinhala']); ?></td>
                                <td><?php echo htmlspecialchars($offence['description_tamil']); ?></td>
                                <td><?php echo htmlspecialchars($offence['description_english']); ?></td>
                                <td><?php echo htmlspecialchars($offence['points_deducted']); ?></td>
                                <td><?php echo "Rs. " . number_format($offence['fine'], 2); ?></td>
                                <td>
                                    <a href="edit-offence.php?offence_number=<?php echo urlencode($offence['offence_number']); ?>"
                                        class="btn marginbottom">Edit</a>
                                    <form action="delete-offence-process.php" method="POST" style="display:inline;">
                                        <input type="hidden" name="offence_number"
                                            value="<?php echo htmlspecialchars($offence['offence_number']); ?>">
                                        <button type="submit" class="deletebtn">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody> -->
        </div>
    </div>
</main>

<?php include_once "../../../includes/footer.php" ?>