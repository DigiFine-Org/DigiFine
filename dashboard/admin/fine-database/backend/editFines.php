<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('db.php'); // Include your database connection file

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['fine_id'])) {
    // Fetch violation details based on the fine_id passed via GET
    $fine_id = (int) $_GET['fine_id'];

    // Prepare the SELECT query to fetch fine data
    $query = "SELECT 
        f.fine_id,
        f.officer_id, 
        f.license_number, 
        d.full_name,
        d.d_address,
        d.license_valid_from,
        d.license_valid_to,
        d.competent_categories,
        f.vehicle_number,
        v.category_id,
        c.category_name,
        f.violation_id, 
        v.violation_name,
        f.description,
        v.price,
        f.issued_on, 
        f.issued_place,
        f.expire_date,  
        f.payment_status
    FROM fines f
    JOIN violations v ON f.violation_id = v.violation_id
    JOIN drivers d ON f.license_number = d.license_number
    join violation_categories c on v.category_id = c.category_id
    WHERE f.fine_id = ?";

    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'i', $fine_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    // Check if tickets are found
    if (mysqli_num_rows($result) > 0) {
        $tickets = mysqli_fetch_all($result, MYSQLI_ASSOC);
        // For testing, let's output the result to check
        // echo "<pre>";
        // print_r($tickets);
        // echo "</pre>";
    } else {
        echo "No tickets found for fine ID: " . $fine_id;
    }

    // Close the statement and the connection
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['fine_id'])) {
    // Handle form submission to update fine
    // Collect form data
    $officer_id = isset($_POST['officer_id']) ? (int)$_POST['officer_id'] : 0; // Convert to integer
    $license_number = isset($_POST['license_number']) ? $_POST['license_number'] : '';
    $vehicle_number = isset($_POST['vehicle_number']) ? $_POST['vehicle_number'] : '';
    $violation_id = isset($_POST['violation_id']) ? (int)$_POST['violation_id'] : 0; // Convert to integer
    $description = isset($_POST['description']) ? $_POST['description'] : '';
    $price = isset($_POST['price']) ? $_POST['price'] : 0;
    $payment_status = isset($_POST['payment_status']) ? $_POST['payment_status'] : '';


    // Debug output
    // var_dump($fine_id, $officer_id, $license_number, $vehicle_number, $violation_id, $description, $price, $payment_status);

    // Ensure all required fields are filled in
    if (empty($officer_id) || empty($license_number) || empty($vehicle_number) || empty($violation_id) || empty($description) || empty($price) || empty($payment_status)) {
        echo json_encode(['success' => false, 'message' => 'Required fields are missing.']);
        exit();
    }

    // Check if the license_number exists in the drivers table
    $checkQuery = "SELECT COUNT(*) as count FROM drivers WHERE license_number = ?";
    $checkStmt = $conn->prepare($checkQuery);
    $checkStmt->bind_param("s", $license_number);
    if (!$checkStmt->execute()) {
        die('License number validation failed: ' . $checkStmt->error);
    }

    $checkResult = $checkStmt->get_result();
    $checkRow = $checkResult->fetch_assoc();
    if ($checkRow['count'] == 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid license number.']);
        exit();
    }

    // Check if the violation ID exists in the violations table
    $checkQuery = "SELECT COUNT(*) as count FROM violations WHERE violation_id = ?";
    $checkStmt = $conn->prepare($checkQuery);
    $checkStmt->bind_param("i", $violation_id);
    if (!$checkStmt->execute()) {
        die('Violation validation failed: ' . $checkStmt->error);
    }

    $checkResult = $checkStmt->get_result();
    $checkRow = $checkResult->fetch_assoc();
    if ($checkRow['count'] == 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid violation ID.']);
        exit();
    }

    // Prepare the UPDATE query
    $query = "UPDATE fines SET officer_id = ?, license_number = ?, vehicle_number = ?, violation_id = ?, description = ?, price = ?, payment_status = ?
              WHERE fine_id = ?";
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        die('Prepare failed: (' . $conn->errno . ') ' . $conn->error);
    }

    $stmt->bind_param("ississs", $officer_id, $license_number, $vehicle_number, $violation_id, $description, $price, $payment_status);
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Fine updated successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error updating fine: ' . $stmt->error]);
    }

    $stmt->close();
}


?>



<!-- HTML form to display and update the violation -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Fine</title>
    <link rel="stylesheet" href="/Digi-fine/assets/css/issueFine.css">
    <script>
        function fetchDriverData() {
            const licenseNumber = document.getElementById("license_number").value;

            if (licenseNumber) {
                // Send an AJAX request to fetch driver data from the backend
                fetch(`../issueFines/get-driver-data.php?license_number=${licenseNumber}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Populate the fields with fetched data
                            document.getElementById("full_name").value = data.full_name;
                            document.getElementById("d_address").value = data.d_address;
                            document.getElementById("license_valid_from").value = data.license_valid_from;
                            document.getElementById("license_valid_to").value = data.license_valid_to;
                            document.getElementById("competent_categories").innerHTML = data.competent_categories;
                        } else {
                            alert("Driver data not found!");
                        }
                    })
                    .catch(error => console.error("Error fetching driver data:", error));
            } else {
                alert("Please enter a license number.");
            }
        }

        function fetchViolationData() {
            const violationId = document.getElementById("violation_id").value;

            if (violationId) {
                // Send an AJAX request to fetch violation data from the backend
                fetch(`../issueFines/get-violation-data.php?violation_id=${violationId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Populate the fields with fetched data
                            document.getElementById("violation_name").value = data.violation_name;
                            document.getElementById("price").value = data.price;
                        } else {
                            alert("Violation data not found!");
                        }
                    })
                    .catch(error => console.error("Error fetching violation data:", error));
            } else {
                alert("Please enter a violation ID.");
            }
        }
    </script>
</head>

<body>
    <div>
        <?php if (!empty($tickets)): ?>
            <?php $ticket = $tickets[0]; ?>
            <h1>Edit Traffic Fine</h1>
            <form method="POST" action="editFines.php">


                <label for="officer_id">Officer ID</label>
                <input type="text" id="officer_id" name="officer_id" id="officer_id" value="<?php echo $ticket['officer_id']; ?>" required>


                <label for="license_number">Driving License Number</label>
                <input type="text" name="license_number" id="license_number" value="<?php echo $ticket['license_number']; ?>" required>
                <button type="button" onclick="fetchDriverData()">Fetch Driver Details</button>
                <br>
                <label for="full_name">Full Name</label>
                <input type="text" id="full_name" name="full_name" value="<?php echo $ticket['full_name']; ?>" required>

                <label for="d_address">Address</label>
                <input type="text" id="d_address" name="d_address" value="<?php echo $ticket['d_address']; ?>" required>

                <label for="license_valid_from">License Valid From</label>
                <input type="date" id="license_valid_from" name="license_valid_from" value="<?php echo $ticket['license_valid_from']; ?>" readonly>

                <label for="license_valid_to">License Valid To</label>
                <input type="date" id="license_valid_to" name="license_valid_to" value="<?php echo $ticket['license_valid_to']; ?>" readonly>

                <label for="competent_categories">Competent Drive Categories</label>
                <input type="text" id="competent_categories" name="competent_categories" value="<?php echo $ticket['competent_categories']; ?>" readonly>

                <label for="vehicle_number">Vehicle Number</label>
                <input type="text" id="vehicle_number" name="vehicle_number" value="<?php echo $ticket['vehicle_number']; ?>" required>
                <br>

                <label for="issued_place">Issued Place</label>
                <input type="text" id="issued_place" name="issued_place" value="<?php echo $ticket['issued_place']; ?>" required>
                <!-- <label for="category">Nature of Offence (Category)</label> -->
                <label for="categoryDropdown">Nature of Offence (Category)</label>
                <input type="text" id="category" name="category" value="<?php echo $ticket['category_name']; ?>" required>
                <br>

                <!-- New section for violation -->
                <label for="violation_id">Violation ID</label>
                <input type="text" name="violation_id" id="violation_id" value="<?php echo $ticket['violation_id']; ?>" required>
                <button type="button" onclick="fetchViolationData()">Fetch Violation Details</button>
                <br>

                <label for="violation_name">Violation Name</label>
                <input type="text" name="violation_name" id="violation_name" value="<?php echo $ticket['violation_name']; ?>" required>
                <br>

                <label for="description">Description</label>
                <input type="text" name="description" id="description" value="<?php echo $ticket['description']; ?>" required>
                <br>
                <label for="price">Price</label>
                <input type="number" step="0.01" name="price" id="price" value="<?php echo $ticket['price']; ?>" required>

                <label for="payment_status">Payment Status</label>
                <select name="payment_status" id="payment_status" required>
                    <option value="paid" <?php if ($ticket['payment_status'] == 'paid') echo 'selected'; ?>>Paid</option>
                    <option value="pending" <?php if ($ticket['payment_status'] == 'pending') echo 'selected'; ?>>Pending</option>
                    <option value="late" <?php if ($ticket['payment_status'] == 'late') echo 'selected'; ?>>Late</option>
                </select>
                <!-- Remaining form fields -->
                <button type="submit">Edit Fine</button>
            </form>
        <?php else: ?>
            <p>No tickets found for fine ID: <?= htmlspecialchars($fine_id); ?></p>
        <?php endif; ?>
    </div>


</body>

</html>