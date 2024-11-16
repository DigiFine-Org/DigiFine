<?php
include('../../../db/connect.php');

// Ensure the user is logged in and is a driver
if (isset($_SESSION['user']) && $_SESSION['user']['role'] == 'driver') {
    $id = $_SESSION['user']['id'];  // This is the driver's ID from the session
} else {
    echo json_encode(['success' => false, 'message' => 'You are not logged in as a driver!']);
    exit();
}

// Prepare the query to avoid SQL injection
$query = "
    SELECT 
        f.id, 
        f.officer_id, 
        f.driver_id, 
        f.violation_id, 
        f.issued_on, 
        f.expire_date,  
        f.payment_status,
        f.description,
        f.issued_place,
        o.fine_amount,
        o.offence_description_english,
        /*c.category_name,*/
        CONCAT(d.fname, ' ', d.lname) AS full_name
    FROM fines f
    JOIN offences o ON f.violation_id = o.id
    JOIN drivers d ON f.driver_id = d.id 
    /*JOIN violation_categories c ON v.category_id = c.category_id*/
    WHERE f.driver_id = ?";

// Prepare the statement
$stmt = mysqli_prepare($conn, $query);

if ($stmt === false) {
    // Handle error if statement preparation fails
    die('Error preparing the statement: ' . mysqli_error($conn));
}

// Bind the parameter
mysqli_stmt_bind_param($stmt, 's', $id);

// Execute the statement
mysqli_stmt_execute($stmt);

// Get the result
$result = mysqli_stmt_get_result($stmt);

// Check if tickets are found
if (mysqli_num_rows($result) > 0) {
    $tickets = mysqli_fetch_all($result, MYSQLI_ASSOC);


    // For testing, let's output the result to check
    // echo "<pre>";
    // print_r($tickets);
    // echo "</pre>";


} else {
    echo "No tickets found for driver ID: " . $id;
}

// Close the statement and the connection
mysqli_stmt_close($stmt);
mysqli_close($conn);
