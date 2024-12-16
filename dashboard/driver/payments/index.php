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
                            <tr>
                                <td>23000128</td>
                                <td>2024-11-29</td>
                                <td>10:30 AM</td>
                                <td>Rs. 1000.00</td>
                                <td>Successful</td>
                                <td><button class="btn">View</button></td>
                            </tr>
                            <tr>
                                <td>23000129</td>
                                <td>2024-11-28</td>
                                <td>11:45 AM</td>
                                <td>Rs. 1000.00</td>
                                <td>Successful</td>
                                <td><button class="btn">View</button></td>
                            </tr>
                            <tr>
                                <td>23000135</td>
                                <td>2024-11-27</td>
                                <td>02:00 PM</td>
                                <td>Rs. 1000.00</td>
                                <td>Successful</td>
                                <td><button class="btn">View</button></td>
                            </tr>
                            <tr>
                                <td>23000146</td>
                                <td>2024-11-27</td>
                                <td>03:20 PM</td>
                                <td>Rs. 1000.00</td>
                                <td>Successful</td>
                                <td><button class="btn">View</button></td>
                            </tr>
                            <tr>
                                <td>23000356</td>
                                <td>2024-11-26</td>
                                <td>09:10 AM</td>
                                <td>Rs. 1000.00</td>
                                <td>Successful</td>
                                <td><button class="btn">View</button></td>
                            </tr>
                            <tr>
                                <td>23000456</td>
                                <td>2024-11-26</td>
                                <td>12:00 PM</td>
                                <td>Rs. 1000.00</td>
                                <td>Successful</td>
                                <td><button class="btn">View</button></td>
                            </tr>
                            <tr>
                                <td>23003453</td>
                                <td>2024-11-25</td>
                                <td>08:45 AM</td>
                                <td>Rs. 1000.00</td>
                                <td>Successful</td>
                                <td><button class="btn">View</button></td>
                            </tr>
                            <tr>
                                <td>23003652</td>
                                <td>2024-11-25</td>
                                <td>01:15 PM</td>
                                <td>Rs. 1000.00</td>
                                <td>Successful</td>
                                <td><button class="btn">View</button></td>
                            </tr>
                            <tr>
                                <td>23035586</td>
                                <td>2024-11-24</td>
                                <td>03:45 PM</td>
                                <td>Rs. 1000.00</td>
                                <td>Successful</td>
                                <td><button class="btn">View</button></td>
                            </tr>
                            <tr>
                                <td>23026574</td>
                                <td>2024-11-24</td>
                                <td>04:50 PM</td>
                                <td>Rs. 1000.00</td>
                                <td>Successful</td>
                                <td><button class="btn">View</button></td>
                            </tr>
                            <tr>
                                <td>23074539</td>
                                <td>2024-11-23</td>
                                <td>10:10 AM</td>
                                <td>Rs. 1000.00</td>
                                <td>Successful</td>
                                <td><button class="btn">View</button></td>
                            </tr>
                            <tr>
                                <td>23078912</td>
                                <td>2024-11-23</td>
                                <td>02:30 PM</td>
                                <td>Rs. 1000.00</td>
                                <td>Successful</td>
                                <td><button class="btn">View</button></td>
                            </tr>
                            <tr>
                                <td>23010123</td>
                                <td>2024-11-22</td>
                                <td>01:00 PM</td>
                                <td>Rs. 1000.00</td>
                                <td>Successful</td>
                                <td><button class="btn">View</button></td>
                            </tr>
                            <tr>
                                <td>23020234</td>
                                <td>2024-11-22</td>
                                <td>11:25 AM</td>
                                <td>Rs. 1000.00</td>
                                <td>Successful</td>
                                <td><button class="btn">View</button></td>
                            </tr>
                            <tr>
                                <td>23030345</td>
                                <td>2024-11-21</td>
                                <td>09:40 AM</td>
                                <td>Rs. 1000.00</td>
                                <td>Successful</td>
                                <td><button class="btn">View</button></td>
                            </tr>
                            <tr>
                                <td>23040456</td>
                                <td>2024-11-21</td>
                                <td>03:15 PM</td>
                                <td>Rs. 1000.00</td>
                                <td>Successful</td>
                                <td><button class="btn">View</button></td>
                            </tr>
                            <tr>
                                <td>23050567</td>
                                <td>2024-11-20</td>
                                <td>10:50 AM</td>
                                <td>Rs. 1000.00</td>
                                <td>Successful</td>
                                <td><button class="btn">View</button></td>
                            </tr>
                            <tr>
                                <td>23060678</td>
                                <td>2024-11-20</td>
                                <td>04:30 PM</td>
                                <td>Rs. 1000.00</td>
                                <td>Successful</td>
                                <td><button class="btn">View</button></td>
                            </tr>
                            <tr>
                                <td>23070789</td>
                                <td>2024-11-19</td>
                                <td>12:15 PM</td>
                                <td>Rs. 1000.00</td>
                                <td>Successful</td>
                                <td><button class="btn">View</button></td>
                            </tr>
                            <tr>
                                <td>23080890</td>
                                <td>2024-11-19</td>
                                <td>09:05 AM</td>
                                <td>Rs. 1000.00</td>
                                <td>Successful</td>
                                <td><button class="btn">View</button></td>
                            </tr>
                            <tr>
                                <td>23090901</td>
                                <td>2024-11-18</td>
                                <td>11:45 AM</td>
                                <td>Rs. 1000.00</td>
                                <td>Successful</td>
                                <td><button class="btn">View</button></td>
                            </tr>
                            <tr>
                                <td>23101012</td>
                                <td>2024-11-18</td>
                                <td>03:25 PM</td>
                                <td>Rs. 1000.00</td>
                                <td>Successful</td>
                                <td><button class="btn">View</button></td>
                            </tr>
                            <tr>
                                <td>23111123</td>
                                <td>2024-11-17</td>
                                <td>02:40 PM</td>
                                <td>Rs. 1000.00</td>
                                <td>Successful</td>
                                <td><button class="btn">View</button></td>
                            </tr>
                            <tr>
                                <td>23121234</td>
                                <td>2024-11-17</td>
                                <td>10:15 AM</td>
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