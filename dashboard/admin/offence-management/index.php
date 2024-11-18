<?php

$pageConfig = [
    'title' => 'Offence Management',
    'styles' => ["../../dashboard.css"],
    'scripts' => ["../../dashboard.js"],
    'authRequired' => true
];


require_once "../../../db/connect.php";

try {
    $sql = "SELECT offence_number, description_sinhala, description_tamil, description_english, points_deducted, fine FROM offences";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Query preparation failed: " . $conn->error);
    }
    $stmt->execute();
    $stmt->bind_result($offence_number, $description_sinhala, $description_tamil, $description_english, $points_deducted, $fine);
    $offences = [];

    while ($stmt->fetch()) {
        $offences[] = [
            'offence_number' => $offence_number,
            'description_sinhala' => $description_sinhala,
            'description_tamil' => $description_tamil,
            'description_english' => $description_english,
            'points_deducted' => $points_deducted,
            'fine' => $fine
        ];
    }

    $stmt->close();
    $conn->close();

} catch (mysqli_sql_exception $e) {
    die("error: " . $e->getMessage());
}

include_once "../../../includes/header.php";
?>   


<main>
    <?php include_once "../../includes/navbar.php" ?>
    <div class="dashboard-layout">
        <?php include_once "../../includes/sidebar.php" ?>
        <div class="content">
            <div class="table-container">
                <h1>Offence List</h1>
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
                    </tbody>
                </table>
            </div>
            <div class="container x-large no-border">

            </div>
        </div>
    </div>
</main>

<?php include_once "../../../includes/footer.php" ?>
