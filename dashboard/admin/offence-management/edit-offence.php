<?php
$pageConfig = [
    'title' => 'Add Offence',
    'styles' => ["../../dashboard.css"],
    'scripts' => ["../../dashboard.js"],
    'authRequired' => true
];

require_once "../../../db/connect.php";
include_once "../../../includes/header.php";


$offence = null;
if (isset($_GET['offence_number']) && is_numeric($_GET['offence_number'])) {
    $offence_number = $_GET['offence_number'];

    $stmt = $conn->prepare("SELECT *FROM offences WHERE offence_number = ?");
    if($stmt) {
        $stmt->bind_param("i", $offence_number);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result && $result->num_rows > 0) {
            $offence = $result->fetch_assoc();
        }
        $stmt->close();
    }
}

if(!$offence) {
    echo "<script>alert('Offence not found!'); window.location.href = '/digifine/dashboard/admin/offence-management/index.php';</script>";
    exit();
}

?>

<main>
    <?php include_once "../../includes/navbar.php" ?>
    <div class="dashboard-layout">
        <?php include_once "../../includes/sidebar.php" ?>
        <div class="content">
            <div class="container">
                <h1>Edit Offence</h1>
                ...
            </div>
        </div>
    </div>
</main>
