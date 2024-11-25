<?php

$pageConfig = [
    'title' => 'Offence Management',
    'styles' => ["../dashboard.css"],
    'scripts' => ["../dashboard.js"],
    'authRequired' => true
];


require_once "../../db/connect.php";

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

include_once "../../includes/header.php";
?>


<main>
    <?php include_once "../includes/navbar.php" ?>
    <div class="dashboard-layout">
        <?php include_once "../includes/sidebar.php" ?>
        <div class="content">
            <div class="container x-large no-border">
            <h1>Offence List</h1>
                <div class="table-container">                  
                    <table>
                        <thead>
                            <tr>
                                <th>offence Number</th>
                                <th>offence Desciption (Sinhala)</th>
                                <th>offence Desciption (Tamil)</th>
                                <th>offence Desciption (English)</th>
                                <th>Points Deducted</th>
                                <th>Fine</th>
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
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include_once "../../includes/footer.php" ?>