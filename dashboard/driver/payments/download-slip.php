<?php
require_once "../../../db/connect.php";
require_once "../../../vendor/autoload.php";

use Dompdf\Dompdf;
use Dompdf\Options;

session_start();

if ($_SESSION['user']['role'] !== 'driver') {
    die("Unathorized access");
}

$driver_id = $_SESSION['user']['id'];
$fine_id = isset($_GET['fine_id']) ? intval($_GET['fine_id']) : 0;

if (!$fine_id || !$driver_id) {
    die("Invalid request.");
}

$sql = "SELECT f.id, f.police_id, f.driver_id, f.license_plate_number, f.issued_date, 
    f.issued_time, f.offence_type, f.nature_of_offence, f.offence, f.fine_status, 
    f.is_reported, f.is_solved, f.fine_amount, o.description_english AS offence_description,
    f.paid_at
    FROM fines AS f 
    INNER JOIN drivers AS d ON f.driver_id = d.id 
    LEFT JOIN offences AS o ON f.offence = o.offence_number 
    WHERE f.id = ? AND d.id = ? AND f.fine_status = 'paid'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $fine_id, $driver_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("data not found");
}

$fine = $result->fetch_assoc();



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
    .logo {
        height: 35px;
        margin-right: 1rem;
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
    .data-table td {
        padding: 0.75rem;
        vertical-align: top;
        border-bottom: 1px solid #ecf0f1;
    }
    .data-table .label {
        width: 40%;
        font-weight: 500;
        color: #34495e;
        background-color: #f8f9fa;
    }
    .amount {
        color: #27ae60;
        font-weight: 600;
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
    <div class="title">Digifine Payment Receipt</div>
</div>


    <div class="document-info">
        Document ID: FINE-' . $fine['id'] . '<br>
        Generated: ' . date('Y-m-d H:i') . '
    </div>

    <table class="data-table">
        <tr><td class="label">Fine ID</td><td>' . $fine['id'] . '</td></tr>
        tr><td class="label">Driver ID</td><td>' . $fine['driver_id'] . '</td></tr>
        <tr><td class="label">License Plate</td><td>' . $fine['license_plate_number'] . '</td></tr>
        <tr><td class="label">Offense</td><td>' . $fine['offence_description'] . '</td></tr>
        <tr><td class="label">Offense Description</td><td>' . $fine['nature_of_offence'] . '</td></tr>
        <tr><td class="label">Issue Date/Time</td><td>' . $fine['issued_date'] . ' ' . $fine['issued_time'] . '</td></tr>
        <tr><td class="label">Payment Date</td><td>' . $fine['paid_at'] . '</td></tr>
        <tr><td class="label">Amount Paid</td><td class="amount">Rs. ' . number_format($fine['fine_amount'], 2) . '</td></tr>
        <
    </table>

    <div class="footer">
         Digifine • Generated electronically 
    </div>
</div>
';

$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$dompdf = new Dompdf($options);

$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'landscape');
$dompdf->render();

$dompdf->stream("fine_payment_slip_{$fine['id']}.pdf", ["Attachment" => 1]);

