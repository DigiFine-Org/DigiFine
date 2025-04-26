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

$policeId = $_GET['officer_id'] ?? '';
if (empty($policeId)) {
    die("Police officer ID is required.");
}

// Fetch data for the revenue table
$url = "http://localhost/digifine/dashboard/oic/reports/officer-reports/revenue-charts/get-fines.php?police_id=" . urlencode($policeId) . "&time_period=" . urlencode($timePeriod);
$response = file_get_contents($url);

if ($response === false) {
    die("Failed to fetch data.");
}

$data = json_decode($response, true);

if (isset($data['error'])) {
    die("Error: " . htmlspecialchars($data['error']));
}

// Generate revenue table rows
$tableRows = '';
$dates = [];
foreach (['paid', 'pending', 'overdue'] as $status) {
    if (isset($data[$status])) {
        foreach ($data[$status] as $row) {
            $date = $row['label'];
            $amount = floatval($row['total_amount']);
            if (!isset($dates[$date])) {
                $dates[$date] = ['paid' => 0, 'pending' => 0, 'overdue' => 0];
            }
            $dates[$date][$status] = $amount;
        }
    }
}

foreach ($dates as $date => $counts) {
    $paid = $counts['paid'] ?? 0;
    $pending = $counts['pending'] ?? 0;
    $overdue = $counts['overdue'] ?? 0;
    $all = $paid + $pending + $overdue;

    $tableRows .= '
        <tr>
            <td>' . htmlspecialchars($date) . '</td>
            <td>' . $paid . '</td>
            <td>' . $pending . '</td>
            <td>' . $overdue . '</td>
            <td>' . $all . '</td>
        </tr>
    ';
}

if (empty($dates)) {
    $tableRows = '
        <tr>
            <td colspan="5">No fines found for the selected filters.</td>
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
        <div class="title">Full Report of Revenue by Fine Status</div>
    </div>

    <div class="document-info">
        Time Period: ' . htmlspecialchars($timePeriod) . '<br>
        Officer ID: ' . htmlspecialchars($policeId) . '<br>
        Generated: ' . date('Y-m-d H:i') . '
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th>Date</th>
                <th>Paid Fines</th>
                <th>Pending Fines</th>
                <th>Overdue Fines</th>
                <th>Total Revenue</th>
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
$dompdf->stream("revenue_report_" . date('Ymd_His') . ".pdf", ["Attachment" => 1]);
exit;
