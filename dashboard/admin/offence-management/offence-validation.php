<?php
// /dashboard/admin/offence-management/includes/offence-validation.php

function validateOffenceForm(&$values, &$errors, $conn) {
    // Validate offence number
    if (!preg_match('/^\d+$/', $values['offence_number']) || $values['offence_number'] <= 0) {
        $errors['offence_number'] = "Offence number must be a positive integer.";
    }

    // Validate descriptions
    foreach (['description_sinhala', 'description_tamil', 'description_english'] as $desc) {
        if (empty($values[$desc])) {
            $errors[$desc] = "This field is required.";
        } elseif (is_numeric($values[$desc])) {
            $errors[$desc] = "Numbers are not allowed in this field.";
        }
    }

    // Validate points
    if (!preg_match('/^\d+$/', $values['points_deducted']) || $values['points_deducted'] < 0) {
        $errors['points_deducted'] = "Points must be a non-negative integer.";
    }
    
    // Validate fine amount
    if (!preg_match('/^\d+(\.\d{1,2})?$/', $values['fine_amount']) || $values['fine_amount'] < 0) {
        $errors['fine_amount'] = "Fine must be a non-negative number.";
    }
    
    // Check for duplicate offence number
    if (empty($errors)) {
        $stmt = $conn->prepare("SELECT * FROM offences WHERE offence_number = ?");
        $stmt->bind_param("s", $values['offence_number']);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $errors['duplicate'] = "An offence with the same number exists";
        }
        $stmt->close();
    }
    
    return empty($errors);
}

function saveOffence($values, $conn) {
    $stmt = $conn->prepare("INSERT INTO offences (offence_number, description_sinhala, description_tamil, description_english, points_deducted, fine_amount) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssid", $values['offence_number'], $values['description_sinhala'], $values['description_tamil'], $values['description_english'], $values['points_deducted'], $values['fine_amount']);
    
    $result = $stmt->execute();
    $stmt->close();
    
    return $result;
}
?>