<?php
$pageConfig = [
    'title' => 'Generate E-ticket',
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
                <h1>Generate E-Ticket</h1>
                <form action="">
                    <div class="field">
                        <label for="">Driver License ID:</label>
                        <input type="text" class="input" placeholder="B5767089" required>
                    </div>
                    <div class="field">
                        <label for="">Vehicle License Number:</label>
                        <input type="text" class="input" placeholder="CAD-6264" required>
                    </div>
                    <div class="field">
                        <label for="">Date of Offense:</label>
                        <input type="date" class="input" required>
                    </div>
                    <div class="field">
                        <label for="">Time:</label>
                        <input type="time" class="input" required>
                    </div>
                    <div class="field">
                        <label for="">Law Type:</label>
                        <select type="time" class="input" required>
                            <option value="">Fine</option>
                            <option value="">Court</option>
                        </select>
                    </div>
                    <div class="field" required>
                        <label for="">Nature of Offense:</label>
                        <textarea type="time" class="input"></textarea>
                    </div>
                    <div class="field">
                        <label for="">Evidence:</label>
                        <input type="image" class="input" required>
                    </div>
                    <button class="btn">Generate</button>
                </form>
            </div>
        </div>
    </div>
</main>

<?php include_once "../../includes/footer.php" ?>