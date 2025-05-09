<?php
$pageConfig = [
    'title' => 'Emergency Services',
    'styles' => ["../../dashboard.css",],
    'scripts' => ["../../dashboard.js"],
    'authRequired' => true
];

include_once "../../../includes/header.php";
require_once "../../../db/connect.php";


if ($_SESSION['user']['role'] !== 'driver') {
    die("unauthorized user!");
}

$driverId = $_SESSION['user']['id'] ?? null;

if (!$driverId) {
    die("Unauthorized access.");
}
?>

<main>
    <?php include_once "../../includes/navbar.php" ?>
    <div class="dashboard-layout">
        <?php include_once "../../includes/sidebar.php" ?>
        <div class="content">
            <button onclick="history.back()" class="back-btn" style="position: absolute; top: 7px; right: 8px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                    <path fill-rule="evenodd"
                        d="M15 8a.5.5 0 0 1-.5.5H3.707l3.147 3.146a.5.5 0 0 1-.708.708l-4-4a.5.5 0 0 1 0-.708l4-4a.5.5 0 1 1 .708.708L3.707 7.5H14.5a.5.5 0 0 1 .5.5z" />
                </svg>
            </button>
            <h2>Emergency Numbers</h2>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Title </th>
                            <th>Emergency Number</th>
                        </tr>
                    </thead>
                    <tbody>

                        <tr>
                            <td>Police Emergency Hotline</td>
                            <td>118/ 119</td>
                        </tr>
                        <tr>
                            <td>Accident Service-General Hospital-Colombo</td>
                            <td>011-2691111</td>
                        </tr>
                        <tr>
                            <td>Tourist Police</td>
                            <td>011-2421052</td>
                        </tr>
                        <tr>
                            <td>Government Information Center</td>
                            <td>1919</td>
                        </tr>
                        <tr>
                            <td>Report Crimes</td>
                            <td>011-2691500</td>
                        </tr>
                        <tr>
                            <td>National mental health helpline</td>
                            <td>1926</td>
                        </tr>
                        <tr>
                            <td>CCC Line for Counseling Support</td>
                            <td>1333</td>
                        </tr>
                        <tr>
                            <td>Shanthi margam for mental health support</td>
                            <td>0717639898</td>
                        </tr>
                        <tr>
                            <td>Women in Need for counseling and legal aid</td>
                            <td>0114718585</td>
                        </tr>
                        <tr>
                            <td>Ministry of Child Development and Women's Affairs</td>
                            <td>1938</td>
                        </tr>
                        <tr>
                            <td>Sri Lanka Police Child and Woman Bureau</td>
                            <td>011 282 6444/ 011 276 8076</td>
                        </tr>
                        <tr>
                            <td>Family Planning Association</td>
                            <td>011 255 5455 </td>
                        </tr>
                        <tr>
                            <td>Ma-Sevana - Sarvodaya</td>
                            <td>011 265 5577 </td>
                        </tr>
                        <tr>
                            <td>Legal Aid Commission of Sri Lanka</td>
                            <td>0115 335 329</td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>
</main>

<?php include_once "../../../includes/footer.php" ?>