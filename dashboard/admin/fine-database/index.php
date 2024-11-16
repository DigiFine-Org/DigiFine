<?php
$pageConfig = [
    'title' => 'Fine Database',
    'styles' => ["../../dashboard.css"],
    'scripts' => ["../../dashboard.js"],
    'authRequired' => true
];

include_once "../../../includes/header.php";
include('backend/getFineData.php');

?>

<script>
    function confirmDelete(fineId) {
        if (confirm("Are you sure you want to delete this fine?")) {
            window.location.href = 'backend/deleteFines.php?fine_id=' + fineId;
        }
    }
</script>

<main>
    <?php include_once "../../includes/navbar.php" ?>

    <div class="dashboard-layout">
        <?php include_once "../../includes/sidebar.php" ?>
        <div class="content">
            <h1 class="table-title">Fine Database</h1>
            <main class="table-responsive">
                <div class="fines-table">
                    <table class="fines-table">
                        <thead>
                            <tr>
                                <th>Select</th>
                                <th>Fine ID</th>
                                <th>Officer ID</th>
                                <th>License Number</th>
                                <th>V ID</th>
                                <th>Issued On</th>
                                <th>Expire Date</th>
                                <th>Fine Amount</th>
                                <th>Status</th>
                                <th>View</th>
                                <th>Delete</th>
                                <th>Edit</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Loop through each fine record and display it in the table
                            while ($fine = mysqli_fetch_assoc($result)) {
                                echo "<tr>";
                                echo "<td><input type='checkbox' /></td>";
                                echo "<td>{$fine['fine_id']}</td>";
                                echo "<td>{$fine['officer_id']}</td>";
                                echo "<td>{$fine['driver_id']}</td>";
                                echo "<td>{$fine['violation_id']}</td>";
                                echo "<td>{$fine['issued_on']}</td>";
                                echo "<td>{$fine['expire_date']}</td>";
                                echo "<td>{$fine['price']}</td>";

                                $statusClass = strtolower($fine['payment_status']); // Convert status to lowercase for class naming
                                echo "<td><span class='status $statusClass'>{$fine['payment_status']}</span></td>";

                                echo "<td><button class='view-button' onclick='window.location.href=\"detailed-view/viewFullFine.php?fine_id={$fine['fine_id']}\"'>Details</button></td>";
                                echo "<td><button class='view-button' onclick='confirmDelete({$fine['fine_id']})'>Delete</button></td>";
                                echo "<td><button class='view-button' onclick='window.location.href=\"backend/editFines.php?fine_id={$fine['fine_id']}\"'>Edit</button></td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </main>
        </div>
    </div>
</main>