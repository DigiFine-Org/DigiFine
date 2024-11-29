<?php
$pageConfig = [
    'title' => 'Duty Submissions',
    'styles' => ["../../dashboard.css"],
    'scripts' => ["../../dashboard.js"],
    'authRequired' => true
];

include_once "../../../includes/header.php";
if ($_SESSION['user']['role'] !== 'oic') {
    die("unauthorized user!");
}

$result = "";

if (isset($_GET)) {
    $result = $_GET['query'] ?? "";
}
?>

<main>
    <?php include_once "../../includes/navbar.php" ?>
    <div class="dashboard-layout">
        <?php include_once "../../includes/sidebar.php" ?>
        <div class="content">
            <div class="container x-large no-border">
                <h1>Duty Submissions</h1>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Submission Number</th>
                                <th>Police ID</th>
                                <th>Officer Name</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>440891</td>
                                <td>15364</td>
                                <td>Pubuditha Walgampaya</td>
                                <td><button class="btn">View</button></td>
                            </tr>
                            <tr>
                                <td>44082</td>
                                <td>12675</td>
                                <td>Wendt Edmund</td>
                                <td><button class="btn">View</button></td>
                            </tr>
                            <tr>
                                <td>44083</td>
                                <td>14567</td>
                                <td>Thihansa Sanjunie</td>
                                <td><button class="btn">View</button></td>
                            </tr>
                            <tr>
                                <td>44084</td>
                                <td>18934</td>
                                <td>John Manuel</td>
                                <td><button class="btn">View</button></td>
                            </tr>
                            <tr>
                                <td>44085</td>
                                <td>17256</td>
                                <td>Nikila Silva</td>
                                <td><button class="btn">View</button></td>
                            </tr>
                            <tr>
                                <td>44086</td>
                                <td>16321</td>
                                <td>Nadun Madusanka</td>
                                <td><button class="btn">View</button></td>
                            </tr>
                            <tr>
                                <td>44087</td>
                                <td>13452</td>
                                <td>Abdhul Basith</td>
                                <td><button class="btn">View</button></td>
                            </tr>
                            <tr>
                                <td>44088</td>
                                <td>14678</td>
                                <td>Nimsara Wickramathanthri</td>
                                <td><button class="btn">View</button></td>
                            </tr>
                            <tr>
                                <td>44089</td>
                                <td>17892</td>
                                <td>Chamath Abeysinghe</td>
                                <td><button class="btn">View</button></td>
                            </tr>
                            <tr>
                                <td>440810</td>
                                <td>12543</td>
                                <td>Heshan Fernando</td>
                                <td><button class="btn">View</button></td>
                            </tr>
                            <tr>
                                <td>440811</td>
                                <td>13987</td>
                                <td>Dinuka Perera</td>
                                <td><button class="btn">View</button></td>
                            </tr>
                            <tr>
                                <td>440812</td>
                                <td>14236</td>
                                <td>Ravindu Wijesinghe</td>
                                <td><button class="btn">View</button></td>
                            </tr>
                            <tr>
                                <td>440813</td>
                                <td>16897</td>
                                <td>Samadhi Peiris</td>
                                <td><button class="btn">View</button></td>
                            </tr>
                            <tr>
                                <td>440814</td>
                                <td>17562</td>
                                <td>Mahesh Samarasinghe</td>
                                <td><button class="btn">View</button></td>
                            </tr>
                            <tr>
                                <td>440815</td>
                                <td>15834</td>
                                <td>Ruwan Lakmal</td>
                                <td><button class="btn">View</button></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include_once "../../../includes/footer.php" ?>