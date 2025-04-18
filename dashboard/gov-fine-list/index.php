<?php

$pageConfig = [
    'title' => 'Gov Fine List',
    'styles' => ["../dashboard.css"],
    'scripts' => ["../dashboard.js"],
    'authRequired' => true
];


require_once "../../db/connect.php";

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

include_once "../../includes/header.php";
?>


<main>
    <?php include_once "../includes/navbar.php" ?>
    <div class="dashboard-layout">
        <?php include_once "../includes/sidebar.php" ?>
        <div class="content">
            <div class="container x-large no-border">
                <h1>Offence List</h1>
            </div>
            <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>offence Number</th>
                                <th>offence Desciption (Sinhala)</th>
                                <th>offence Desciption (Tamil)</th>
                                <th>offence Desciption (English)</th>
                                <th>Points Deducted</th>
                                <th>Fine Amount</th>
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
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
        </div>
    </div>
</main>

<?php include_once "../../includes/footer.php" ?>