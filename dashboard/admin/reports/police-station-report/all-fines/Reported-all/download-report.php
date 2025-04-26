<?php
require_once "../../../../../../db/connect.php";
require_once "../../../../../../vendor/autoload.php";

use Dompdf\Dompdf;
use Dompdf\Options;

session_start();

if ($_SESSION['user']['role'] !== 'admin') {
    die("Unauthorized access");
}

$timePeriod = $_GET['time_period'] ?? '';
$policeStationId = $_GET['station_id'] ?? null;

if (empty($timePeriod)) {
    die("Time period is required.");
}
if (empty($policeStationId)) {
    die("Police station ID is required.");
}

// Fetch data from the same source used by full-reported-all-table.php
$url = "http://localhost/digifine/dashboard/admin/reports/police-station-report/all-fines/Reported-all/get-fines.php?police_station=" . urlencode($policeStationId) . "&time_period=" . urlencode($timePeriod);
$response = file_get_contents($url);

if ($response === false) {
    die("Failed to fetch data.");
}

$data = json_decode($response, true);

if (isset($data['error'])) {
    die("Error: " . htmlspecialchars($data['error']));
}

// Generate table rows for the PDF
$tableRows = '';
$dates = [];
foreach (['all', 'reported'] as $status) {
    foreach ($data[$status] as $row) {
        $dates[$row['label']][$status] = $row['count'];
    }
}

foreach ($dates as $date => $counts) {
    $all = $counts['all'] ?? 0;
    $reported = $counts['reported'] ?? 0;
    $unreported = $all - $reported;

    $tableRows .= '
        <tr>
            <td>' . htmlspecialchars($date) . '</td>
            <td>' . $all . '</td>
            <td>' . $reported . '</td>
            <td>' . $unreported . '</td>
        </tr>
    ';
}

if (empty($dates)) {
    $tableRows = '
        <tr>
            <td colspan="4">No fines found for the selected filters.</td>
        </tr>
    ';
}

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
        text-align: center;
        margin-bottom: 10px;
        padding-bottom: 10px;
        border-bottom: 2px solid #007bff;
    }
    .title {
        font-size: 18px;
        font-weight: bold;
        color: #007bff;
    }
    .document-info {
        text-align: right;
        margin-bottom: 10px;
        font-size: 12px;
        color: #7f8c8d;
    }
    .data-table {
        width: 100%;
        border-collapse: collapse;
        margin: 10px 0;
    }
    .data-table td, .data-table th {
        padding: 8px;
        text-align: left;
        border: 1px solid #ddd;
    }
    .data-table th {
        background-color: #f8f9fa;
        font-weight: bold;
        color: #34495e;
    }
    .footer {
        margin-top: 20px;
        text-align: center;
        font-size: 12px;
        color: #7f8c8d;
        border-top: 1px solid #ddd;
        padding-top: 10px;
    }
</style>

<div class="container">
    <div class="header">
        <div class="title">Full Report of Fines by Reported Status</div>
    </div>

    <div class="document-info">
        Time Period: ' . htmlspecialchars($timePeriod) . '<br>
        Police Station: ' . htmlspecialchars($policeStationId) . '<br>
        Generated: ' . date('Y-m-d H:i') . '
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th>Date</th>
                <th>All Fines</th>
                <th>Reported Fines</th>
                <th>Unreported Fines</th>
            </tr>
        </thead>
        <tbody>
            ' . $tableRows . '
        </tbody>
    </table>

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
$dompdf->stream("reported_status_report_" . date('Ymd_His') . ".pdf", ["Attachment" => 1]);
exit;
