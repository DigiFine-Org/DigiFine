<?php
$pageConfig = [
    'title' => 'Submit Duty',
    'styles' => ["../dashboard.css"],
    'scripts' => ["../dashboard.js"],
    'authRequired' => true
];

include_once "../../includes/header.php";
?>

<main>
    <?php include_once "../includes/navbar.php" ?>
    <div class="dashboard-layout">
        <?php include_once "../includes/sidebar.php" ?>
        <div class="content">
            <div class="container">
                <h1>Submit Duty</h1>
                <form action="">
                    <p><b>Police Officer Details</b></p>
                    <div class="field">
                        <label for="">Police ID:</label>
                        <input type="text" class="input" placeholder="23123" required disabled>
                    </div>
                    <div class="field">
                        <label for="">Police Officer Name:</label>
                        <input type="text" class="input" placeholder="I.A.J. Arachchi" required disabled>
                    </div>
                    <p><b>Duty Information</b></p>
                    <div class="field">
                        <label for="">Patrol Location :</label>
                        <input type="" class="input" required>
                    </div>
                    <div class="field">
                        <label for="">Patrol Time(Start):</label>
                        <input type="time" class="input" required>
                    </div>
                    <div class="field">
                        <label for="">Patrol Time(End):</label>
                        <input type="time" class="input" required>
                    </div>
                    <div class="field" required>
                        <label for="">Patrol Information:</label>
                        <textarea type="time" class="input"></textarea>
                    </div>
                    <button class="btn">Submit</button>
                </form>
            </div>
        </div>
    </div>
</main>

<?php include_once "../../includes/footer.php" ?>