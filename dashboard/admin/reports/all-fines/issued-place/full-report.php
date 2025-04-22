<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode($_POST['data'], true);
    $timePeriod = $_POST['time_period'];
?>
    <h2>Full Report - <?= htmlspecialchars($timePeriod) ?></h2>
    <table border="1" cellpadding="5" cellspacing="0">
        <thead>
            <tr>
                <th>Location</th>
                <th>Count</th>
                <th>Total Amount</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($data as $row): ?>
                <tr>
                    <td><?= htmlspecialchars($row['location']) ?></td>
                    <td><?= htmlspecialchars($row['count']) ?></td>
                    <td><?= htmlspecialchars($row['total']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php
} else {
?>
    <script>
        // Read from sessionStorage and submit to PHP
        const data = sessionStorage.getItem("fullPoliceStationData");
        const timePeriod = sessionStorage.getItem("timePeriod");

        if (data) {
            const form = document.createElement("form");
            form.method = "POST";
            form.style.display = "none";

            const inputData = document.createElement("input");
            inputData.name = "data";
            inputData.value = data;

            const inputPeriod = document.createElement("input");
            inputPeriod.name = "time_period";
            inputPeriod.value = timePeriod;

            form.appendChild(inputData);
            form.appendChild(inputPeriod);
            document.body.appendChild(form);
            form.submit();
        } else {
            document.write("No data found.");
        }
    </script>
<?php
}
?>