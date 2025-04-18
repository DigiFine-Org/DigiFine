<?php
$pageConfig = [
    'title' => 'Register Police Officer',
    'styles' => ["../../dashboard.css", "styles.css"],
    'scripts' => ["../../dashboard.js"],
    'authRequired' => true
];

require_once "../../../db/connect.php";
include_once "../../../includes/header.php";

?>

<main>
    <?php include_once "../../includes/navbar.php" ?>

    <div class="dashboard-layout">
        <?php include_once "../../includes/sidebar.php" ?>
        <div class="content">
            <div class="container-large">
                <div class="feature-tiles">
                    <a href="/digifine/dashboard/admin/officer-management/register-officer/index.php"
                        class="feature-tile">
                        <div class="tile-full">
                            <div class="tile-content">
                                <h3>Register Police Officer</h3>
                                <p>Register Police Officer</p>
                            </div>
                        </div>
                    </a>
                    <a href="/digifine/dashboard/admin/officer-management/assign-oic/index.php" class="feature-tile">
                        <div class="tile-full">
                            <div class="tile-content">
                                <h3>Assign OIC</h3>
                                <p>Assign OIC</p>
                            </div>
                        </div>
                    </a>
                    <a href="/digifine/dashboard/admin/officer-management/update-officer-details/index.php"
                        class="feature-tile">
                        <div class="tile-full">
                            <div class="tile-content">
                                <h3>Update Police Officer Details</h3>
                                <p>Update Police Officer Details</p>
                            </div>
                        </div>
                    </a>
                    <a href="" class="feature-tile">
                        <div class="tile-full">
                            <div class="tile-content">
                                <h3>Update Police Officer Details</h3>
                                <p>Update Police Officer Details</p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>

        </div>
    </div>
    </div>
</main>


<?php include_once "../../../includes/footer.php"; ?>