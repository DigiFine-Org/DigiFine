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

<main>
    <?php include_once "../../includes/navbar.php" ?>
    <div class="dashboard-layout">
        <?php include_once "../../includes/sidebar.php" ?>
        <div class="content">
            ...
        </div>
    </div>
</main>






?>