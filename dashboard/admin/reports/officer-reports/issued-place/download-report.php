<?php
require_once "../../../../../db/connect.php";
require_once "../../../../../vendor/autoload.php";

use Dompdf\Dompdf;
use Dompdf\Options;

session_start();

if ($_SESSION['user']['role'] !== 'admin') {
    die("Unauthorized access");
}

$timePeriod = $_GET['time_period'] ?? '';
$policeId = $_GET['officer_id'] ?? '';

if (empty($timePeriod) || empty($policeId)) {
    die("Time period and officer ID are required.");
}

// Fetch data from the same source used by full-issued-place-table.php
$url = "http://localhost/digifine/dashboard/admin/reports/officer-reports/issued-place/get-fines.php?police_id=" . urlencode($policeId) . "&time_period=" . urlencode($timePeriod);
$response = file_get_contents($url);

if ($response === false) {
    die("Failed to fetch data.");
}

$data = json_decode($response, true);

if (isset($data['error'])) {
    die("Error: " . htmlspecialchars($data['error']));
}

// Generate table rows for the PDF
ob_start(); // Start output buffering
?>
<table class="data-table">
    <thead>
        <tr>
            <th>Rank</th>
            <th>Location</th>
            <th>Fine Count</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($data)): ?>
            <?php $rank = 1; ?>
            <?php foreach ($data as $row): ?>
                <tr>
                    <td><?= $rank++ ?></td>
                    <td><?= htmlspecialchars($row['label']) ?></td>
                    <td><?= htmlspecialchars($row['count']) ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="3">No fines found for the selected filters.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>
<?php
$tableHtml = ob_get_clean(); // Capture the table HTML

// PDF content
$html = '
<style>
    body { 
        font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
        margin: 0;
        color: #2c3e50;
        font-size: 14px;
        line-height: 1.5;
    }
    .container {
        max-width: 100%;
        padding: 10mm 0;
    }
    .header {
        display: flex;
        align-items: center;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid #007bff;
    }
    .title {
        font-size: 1.25rem;
        font-weight: 600;
        color: #007bff;
        letter-spacing: -0.5px;
    }
    .document-info {
        text-align: right;
        margin-bottom: 1.5rem;
        font-size: 0.9em;
        color: #7f8c8d;
    }
    .data-table {
        width: 100%;
        border-collapse: collapse;
        margin: 1rem 0;
    }
    .data-table td, .data-table th {
        padding: 0.75rem;
        vertical-align: top;
        border: 1px solid #ecf0f1;
    }
    .data-table th {
        background-color: #f8f9fa;
        font-weight: 600;
        color: #34495e;
    }
    .footer {
        margin-top: 2rem;
        padding-top: 1rem;
        border-top: 2px solid #007bff;
        font-size: 0.85em;
        color: #7f8c8d;
        text-align: center;
    }
</style>

<div class="container">
    <div class="header">
        <div class="title">Full Report of Fines by Issued Place</div>
    </div>

    <div class="document-info">
        Time Period: ' . htmlspecialchars($timePeriod) . '<br>
        Generated: ' . date('Y-m-d H:i') . '
    </div>

    ' . $tableHtml . '

    <div class="footer">
         Generated electronically • Digifine
    </div>
</div>
';

// Initialize DOMPDF
$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$dompdf = new Dompdf($options);

$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// Download PDF
$dompdf->stream("issued_place_report_" . date('Ymd_His') . ".pdf", ["Attachment" => 1]);
exit;
