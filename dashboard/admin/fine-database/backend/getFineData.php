<?php
include('../../../db/connect.php');
$query = "SELECT f.fine_id, f.officer_id, f.driver_id, f.violation_id, f.issued_on, f.expire_date, 
                  v.violation_name, v.price, f.payment_status
          FROM fines f
          JOIN violations v ON f.violation_id = v.violation_id";

$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}
