<?php
$pageConfig = [
    'title' => 'Driver Fines',
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
            <div class="content">
                <div class="home-grid">
                    <a href="" class="ticket">
                        <h1>TICKET N0: 01</h1>
                        <br>
                        <br>
                        <p>OFFENCE TYPE: Fine</p>
                        <br>
                        <P>OFFENCE: Not Carrying Revenue License</P>
                        <br>
                        <p>DATE: 2024-03-03</p>
                        <br>
                        <p>TIME: 12:53:23</p>
                        <br>
                        <p>STATUS: Pending</p>
                        <br>
                        <div class="wrapper">
                            <button class="btn marginright">View</button>
                            <button class="btn">Pay</button>
                        </div>
                    </a>
                    <a href="" class="ticket">
                        <h1>TICKET N0: 02</h1>
                        <br>
                        <br>
                        <p>OFFENCE TYPE: Fine</p>
                        <br>
                        <P>OFFENCE: Not Carrying Revenue License</P>
                        <br>
                        <p>DATE: 2024-03-03</p>
                        <br>
                        <p>TIME: 12:53:23</p>
                        <br>
                        <p>STATUS: Pending</p>
                        <br>
                        <div class="wrapper">
                            <button class="btn marginright">View</button>
                            <button class="btn">Pay</button>
                        </div>
                    </a>
                    <a href="" class="ticket warn">
                        <h1>TICKET N0: 03</h1>
                        <br>
                        <br>
                        <p>OFFENCE TYPE: Fine</p>
                        <br>
                        <P>OFFENCE: Not Carrying Revenue License</P>
                        <br>
                        <p>DATE: 2024-03-03</p>
                        <br>
                        <p>TIME: 12:53:23</p>
                        <br>
                        <p>STATUS: Pending</p>
                        <br>
                        <div class="wrapper">
                            <button class="btn marginright black">View</button>
                            <button class="btn black">Pay</button>
                        </div>
                    </a>
                    <a href="" class="ticket">
                        <h1>TICKET N0: 04</h1>
                        <br>
                        <br>
                        <p>OFFENCE TYPE: Fine</p>
                        <br>
                        <P>OFFENCE: Not Carrying Revenue License</P>
                        <br>
                        <p>DATE: 2024-03-03</p>
                        <br>
                        <p>TIME: 12:53:23</p>
                        <br>
                        <p>STATUS: Pending</p>
                        <br>
                        <div class="wrapper">
                            <button class="btn marginright">View</button>
                            <button class="btn">Pay</button>
                        </div>
                    </a>
                </div>
            </div>
        </div>
</main>

<?php include_once "../../../includes/footer.php" ?>