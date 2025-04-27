<?php
session_start();
require_once "../../../db/connect.php";

$pageConfig = [
    'title' => 'Add Offence',
    'styles' => [
        "../../dashboard.css",
        "offence.css"
    ],
    'scripts' => ["../../dashboard.js"],
    'authRequired' => true
];

include_once "../../../includes/header.php";

if ($_SESSION['user']['role'] !== 'admin') {
    die("Unauthorized user!");
}


$errors = [];
$values = [
    'offence_number' => '',
    'description_sinhala' => '',
    'description_tamil' => '',
    'description_english' => '',
    'points_deducted' => '',
    'fine_amount' => ''
];


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    foreach ($values as $key => $_) {
        $values[$key] = htmlspecialchars(trim($_POST[$key] ?? ''));
    }

  
    if (!preg_match('/^\d+$/', $values['offence_number']) || $values['offence_number'] <= 0) {
        $errors['offence_number'] = "Offence number must be a positive integer.";
    }

    foreach (['description_sinhala', 'description_tamil', 'description_english'] as $desc) {
        if (empty($values[$desc])) {
            $errors[$desc] = "This field is required.";
        } elseif (is_numeric($values[$desc])) {
            $errors[$desc] = "Numbers are not allowed in this field.";
        }
    }

    if (!preg_match('/^\d+$/', $values['points_deducted']) || $values['points_deducted'] < 0) {
        $errors['points_deducted'] = "Points must be a non-negative integer.";
    }
    
    if (!preg_match('/^\d+(\.\d{1,2})?$/', $values['fine_amount']) || $values['fine_amount'] <= 0) {
        $errors['fine_amount'] = "Fine must be a non-negative number.";
    }
    

    if (empty($errors)) {
        $stmt = $conn->prepare("SELECT * FROM offences WHERE offence_number = ? ");
        $stmt->bind_param("s", $values['offence_number']);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $errors['duplicate'] = "An offence with the same number exists";
        }
        $stmt->close();
    }


    if (empty($errors)) {
        $stmt = $conn->prepare("INSERT INTO offences (offence_number, description_sinhala, description_tamil, description_english, points_deducted, fine_amount) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssid", $values['offence_number'], $values['description_sinhala'], $values['description_tamil'], $values['description_english'], $values['points_deducted'], $values['fine_amount']);

        if ($stmt->execute()) {
            $_SESSION['success_message'] = "Offence added successfully";
            header("Location: /digifine/dashboard/admin/offence-management/index.php");
            exit();
        } else {
            $errors['database'] = "Database error: " . $stmt->error;
        }

        $stmt->close();
    }
}
?>

<main>
    <?php include_once "../../includes/navbar.php" ?>
    <div class="dashboard-layout">
        <?php include_once "../../includes/sidebar.php" ?>
        <div class="content">
            <div class="container">
            <button onclick="history.back()" class="back-btn" style="position: absolute; top: 7px; right: 8px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        viewBox="0 0 16 16">
                        <path fill-rule="evenodd"
                            d="M15 8a.5.5 0 0 1-.5.5H3.707l3.147 3.146a.5.5 0 0 1-.708.708l-4-4a.5.5 0 0 1 0-.708l4-4a.5.5 0 1 1 .708.708L3.707 7.5H14.5a.5.5 0 0 1 .5.5z" />
                    </svg>
                </button>
                <h1>Add Offence</h1>

                <?php if (!empty($errors)): ?>
                    <div class="error-messages" >
                        <?php foreach ($errors as $error): ?>
                            <p><?= $error ?></p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <form action="" method="post" novalidate>
                    <div class="field">
                        <label>Offence Number</label>
                        <input type="text" name="offence_number" class="input" value="<?= $values['offence_number'] ?>" required>
                        
                    </div>

                    <div class="field">
                        <label>Offence Description (Sinhala)</label>
                        <input type="text" name="description_sinhala" class="input" value="<?= $values['description_sinhala'] ?>" required>
                        
                    </div>

                    <div class="field">
                        <label>Offence Description (Tamil)</label>
                        <input type="text" name="description_tamil" class="input" value="<?= $values['description_tamil'] ?>" required>
                        <?php if (isset($errors['description_tamil'])): ?>
                            <small class="error" style="color: red;"><?= $errors['description_tamil'] ?></small>
                        <?php endif; ?>
                    </div>

                    <div class="field">
                        <label>Offence Description (English)</label>
                        <input type="text" name="description_english" class="input" value="<?= $values['description_english'] ?>" required>
                        <?php if (isset($errors['description_english'])): ?>
                            <small class="error" style="color: red;"><?= $errors['description_english'] ?></small>
                        <?php endif; ?>
                    </div>

                    <div class="field">
                        <label>Points Deducted</label>
                        <input type="text" name="points_deducted" class="input" value="<?= $values['points_deducted'] ?>" required>
                        <?php if (isset($errors['points_deducted'])): ?>
                            <small class="error" style="color: red;"><?= $errors['points_deducted'] ?></small>
                        <?php endif; ?>
                    </div>

                    <div class="field">
                        <label>Fine Amount (RS)</label>
                        <input type="text" name="fine_amount" class="input" value="<?= $values['fine_amount'] ?>" required>
                        
                    </div>

                    <button class="btn" type="submit">Add</button>
                </form>
            </div>
        </div>
    </div>
</main>

<?php include_once "../../../includes/footer.php" ?>