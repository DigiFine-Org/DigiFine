<?php
require_once "../../../../../db/connect.php";
require_once "../../../../../vendor/autoload.php";

use Dompdf\Dompdf;
use Dompdf\Options;

session_start();

if ($_SESSION['user']['role'] !== 'oic') {
    die("Unauthorized access");
}

$timePeriod = $_GET['time_period'] ?? '';
if (empty($timePeriod)) {
    die("Time period is required.");
}

$policeStationId = $_SESSION['police_station_id'] ?? null;
if (!$policeStationId) {
    die("Police station ID not found in session.");
}

// Fetch data for the offence type revenue table
$url = "http://localhost/digifine/dashboard/oic/reports/offence/offence-type-revenue/get-offences-revenue.php?police_station=" . urlencode($policeStationId) . "&time_period=" . urlencode($timePeriod);
$response = file_get_contents($url);

if ($response === false) {
    die("Failed to fetch data.");
}

$data = json_decode($response, true);

if (isset($data['error'])) {
    die("Error: " . htmlspecialchars($data['error']));
}

// Generate offence type revenue table rows
$tableRows = '';
if (!empty($data)) {
    $rank = 1;
    foreach ($data as $row) {
        $tableRows .= '
            <tr>
                <td>' . $rank++ . '</td>
                <td>' . htmlspecialchars($row['offence_number']) . '</td>
                <td>' . htmlspecialchars($row['label']) . '</td>
                <td>' . htmlspecialchars($row['count']) . '</td>
            </tr>
        ';
    }
} else {
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
        <div class="title">Full Report of Fines by Offence Type</div>
    </div>

    <div class="document-info">
        Time Period: ' . htmlspecialchars($timePeriod) . '<br>
        Police Station: ' . htmlspecialchars($policeStationId) . '<br>
        Generated: ' . date('Y-m-d H:i') . '
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th>Rank</th>
                <th>Offence ID</th>
                <th>Offence Name</th>
                <th>Total Amount</th>
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
$dompdf->stream("offence_type_revenue_report_" . date('Ymd_His') . ".pdf", ["Attachment" => 1]);
exit;
