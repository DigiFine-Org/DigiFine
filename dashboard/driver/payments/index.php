<?php
$pageConfig = [
    'title' => 'Payments',
    'styles' => ["../../dashboard.css"],
    'scripts' => ["../../dashboard.js"],
    'authRequired' => true
];

include_once "../../../includes/header.php";
?>


<main>
    <?php include_once "../../includes/navbar.php" ?>
    <div class="dashboard-layout">
        <?php include_once "../../includes/sidebar.php" ?>
        <div class="content">
            <div class="container x-large no-border">
                <h1>Payments</h1>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Payment ID</th>
                                <th>Payment Date</th>
                                <th>Payment Time</th>
                                <th>Amount Paid</th>
                                <th>Transaction Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>23000125</td>
                                <td>2024-11-29</td>
                                <td>09:15 AM</td>
                                <td>Rs. 1000.00</td>
                                <td>Successful</td>
                                <td><button class="btn">View</button></td>
                            </tr>
                            
                        </tbody>
                    </table>



                </div>

            </div>
        </div>
</main>

<?php include_once "../../../includes/footer.php" ?>