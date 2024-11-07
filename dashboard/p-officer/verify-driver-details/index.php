<?php
$pageConfig = [
    'title' => 'Verify Driver Details',
    'styles' => ["../dashboard.css"],
    'scripts' => ["../dashboard.js"],
    'authRequired' => true
];

include_once "../../includes/header.php";

$result = "";

if (isset($_GET)) {
    $result = $_GET['query'] ?? "";
}
?>

<main>
    <?php include_once "../includes/navbar.php" ?>
    <div class="dashboard-layout">
        <?php include_once "../includes/sidebar.php" ?>
        <div class="content">
            <div class="container">
                <h1>Verify Driver Details</h1>
                <?php if (!$result): ?>
                    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                        <input name="query" required type="search" class="input" placeholder="Enter Driver License ID">
                        <button class="btn">Search</button>
                    </form>
                <?php else: ?>
                    <h3>Driver License</h3>
                    <div class="data-line">
                        <span>FULL NAME:</span>
                        <p>IMALSHA AKALANKA JATHUN ARACHCHI</p>
                    </div>
                    <div class="data-line">
                        <span>LICENSE ID:</span>
                        <p>B5767089</p>
                    </div>
                    <div class="data-line">
                        <span>NIC:</span>
                        <p>200136567826</p>
                    </div>
                    <div class="data-line">
                        <span>PERMANENT PLACE OF RESIDENCE:</span>
                        <p>SAKUNA’S BOARDING, WOODLAND AVENUE, NUGEGODA</p>
                    </div>
                    <div class="data-line">
                        <span>BIRTHDATE:</span>
                        <p>27.12.2001</p>
                    </div>
                    <div class="data-line">
                        <span>DATE OF ISSUE LICENSE ID:</span>
                        <p>24.02.2020</p>
                    </div>
                    <div class="data-line">
                        <span>DATE OF EXPIRY LICENSE ID:</span>
                        <p>24.05.2028</p>
                    </div>
                    <div class="data-line">
                        <span>BLOOD GROUP:</span>
                        <p>A-</p>
                    </div>
                    <div class="data-line">
                        <span>RESTRICTIONS IN CODE FORM:</span>
                        <p>SPECTACLES</p>
                    </div>
                    <hr>
                    <div class="table-container">
                        <table>
                            <thead>
                                <tr>
                                    <th>Categories of Vehicle</th>
                                    <th>D. of Issue per category</th>
                                    <th>D. of expiry per category</th>
                                    <th>Restrictions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Cycle</td>
                                    <td>2024.02.03</td>
                                    <td>2024.02.03</td>
                                    <td>-</td>
                                </tr>
                                <tr>
                                    <td>Van</td>
                                    <td>2024.02.03</td>
                                    <td>2024.02.03</td>
                                    <td>-</td>
                                </tr>
                                <tr>
                                    <td>Cycle</td>
                                    <td>2024.02.03</td>
                                    <td>2024.02.03</td>
                                    <td>-</td>
                                </tr>
                                <tr>
                                    <td>Van</td>
                                    <td>2024.02.03</td>
                                    <td>2024.02.03</td>
                                    <td>-</td>
                                </tr>
                                <tr>
                                    <td>Cycle</td>
                                    <td>2024.02.03</td>
                                    <td>2024.02.03</td>
                                    <td>-</td>
                                </tr>
                                <tr>
                                    <td>Van</td>
                                    <td>2024.02.03</td>
                                    <td>2024.02.03</td>
                                    <td>-</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                <?php endif ?>
            </div>
        </div>
    </div>
</main>

<?php include_once "../../includes/footer.php" ?>