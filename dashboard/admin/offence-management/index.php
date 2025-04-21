<?php

$pageConfig = [
    'title' => 'Offence Management',
    'styles' => ["../../dashboard.css"],
    'scripts' => ["../../dashboard.js"],
    'authRequired' => true
];


require_once "../../../db/connect.php";

try {
    $sql = "SELECT offence_number, description_sinhala, description_tamil, description_english, points_deducted, fine_amount FROM offences";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Query preparation failed: " . $conn->error);
    }
    $stmt->execute();
    $stmt->bind_result($offence_number, $description_sinhala, $description_tamil, $description_english, $points_deducted, $fine_amount);
    $offences = [];

    while ($stmt->fetch()) {
        $offences[] = [
            'offence_number' => $offence_number,
            'description_sinhala' => $description_sinhala,
            'description_tamil' => $description_tamil,
            'description_english' => $description_english,
            'points_deducted' => $points_deducted,
            'fine_amount' => $fine_amount
        ];
    }

    $stmt->close();
    $conn->close();

} catch (mysqli_sql_exception $e) {
    die("error: " . $e->getMessage());
}

include_once "../../../includes/header.php";

if ($_SESSION['user']['role'] !== 'admin') {
    die("unauthorized user!");
}
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
            <div class="table-container">
                <h2>Offence List</h2>
                <form action="/digifine/dashboard/admin/offence-management/add-offence.php" method="get">
                    <input type="submit" class="btn margintop marginbottom" value="Add Offence">
                </form>
                <table>
                    <thead>
                        <tr>
                            <th>offence Number</th>
                            <th>offence Desciption (Sinhala)</th>
                            <th>offence Desciption (Tamil)</th>
                            <th>offence Desciption (English)</th>
                            <th>Points Deducted</th>
                            <th>Fine</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($offences as $offence): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($offence['offence_number']); ?></td>
                                <td><?php echo htmlspecialchars($offence['description_sinhala']); ?></td>
                                <td><?php echo htmlspecialchars($offence['description_tamil']); ?></td>
                                <td><?php echo htmlspecialchars($offence['description_english']); ?></td>
                                <td><?php echo htmlspecialchars($offence['points_deducted']); ?></td>
                                <td><?php echo "Rs. " . number_format($offence['fine_amount'], 2); ?></td>
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
                    </tbody>
                </table>
            </div>
            <div class="container x-large no-border">

            </div>
        </div>
    </div>
</main>

<?php include_once "../../../includes/footer.php" ?>