<?php
$pageConfig = [
    'title' => 'Reports',
    'styles' => ["../../dashboard.css", "./reports.css"],
    'scripts' => ["../../dashboard.js"],
    'authRequired' => true
];
session_start();
include_once "../../../includes/header.php";
require_once "../../../db/connect.php";

if ($_SESSION['user']['role'] !== 'admin') {
    die("unauthorized user!");
}

if ($_SESSION['message'] ?? null) {
    if ($_SESSION['message'] === 'success') {
        $message = "Announcement published successfully!";
        unset($_SESSION['message']); // Clear the session message
        include '../../../includes/alerts/success.php';
    } else {
        $message = $_SESSION['message']; // Store the message
        unset($_SESSION['message']); // Clear the session message

        // Include the alert.php file to display the message
        include '../../../includes/alerts/failed.php';
    }
}


?>

<main>
    <?php include_once "../../includes/navbar.php" ?>
    <div class="dashboard-layout">
        <?php include_once "../../includes/sidebar.php" ?>

        <body>
            <h1>Fines Analytics</h1>
            <label for="filter">Filter By:</label>
            <select id="filter" onchange="loadData()">
                <option value="lifetime">Lifetime</option>
                <option value="year">Year</option>
                <option value="month">Month</option>
                <option value="week">Week</option>
                <option value="day">Day</option>
            </select>

            <canvas class="chart"></canvas>

            <script>
                // Load data and render the chart
                function loadData() {
                    const filter = document.getElementById('filter').value;

                    // Fetch data from the server
                    fetch(`process.php?filter=${filter}`)
                        .then(response => response.json())
                        .then(data => renderChart(data))
                        .catch(error => console.error('Error:', error));
                }

                // Render the chart using Canvas API
                function renderChart(data) {
                    const canvas = document.getElementById('chart');
                    const ctx = canvas.getContext('2d');

                    // Clear the canvas
                    ctx.clearRect(0, 0, canvas.width, canvas.height);

                    // Set chart dimensions
                    const chartWidth = canvas.width - 50;
                    const chartHeight = canvas.height - 50;
                    const barWidth = chartWidth / data.length;

                    // Find the maximum value for scaling
                    const maxValue = Math.max(...data.map(item => item.count));

                    // Draw axes
                    ctx.beginPath();
                    ctx.moveTo(40, 10);
                    ctx.lineTo(40, chartHeight);
                    ctx.lineTo(chartWidth + 40, chartHeight);
                    ctx.stroke();

                    // Draw bars
                    data.forEach((item, index) => {
                        const barHeight = (item.count / maxValue) * (chartHeight - 20);
                        const x = 40 + index * barWidth;
                        const y = chartHeight - barHeight;

                        // Draw bar
                        ctx.fillStyle = '#4CAF50';
                        ctx.fillRect(x, y, barWidth - 10, barHeight);

                        // Add labels
                        ctx.fillStyle = '#000';
                        ctx.font = '12px Arial';
                        ctx.fillText(item.period, x + 5, chartHeight + 15);
                        ctx.fillText(item.count, x + 5, y - 5);
                    });
                }

                // Load data on page load
                window.onload = loadData;
            </script>
        </body>
</main>

<?php include_once "../../../includes/footer.php"; ?>