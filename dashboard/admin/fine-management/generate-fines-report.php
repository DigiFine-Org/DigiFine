<?php
require_once "../../../db/connect.php";
require_once "../../../vendor/autoload.php";

use Dompdf\Dompdf;
use Dompdf\Options;

session_start();

if ($_SESSION['user']['role'] !== 'admin') {
    die("Unauthorized access");
}

// Capture filters
$filters = [
    'Fine ID' => $_GET['fine_id'] ?? '',
    'Police ID' => $_GET['police_id'] ?? '',
    'Driver ID' => $_GET['driver_id'] ?? '',
    'Date From' => $_GET['date-from'] ?? '',
    'Date To' => $_GET['date-to'] ?? '',
    'Price From' => $_GET['price-from'] ?? '',
    'Price To' => $_GET['price-to'] ?? '',
    'Offence Type' => $_GET['offence_type'] ?? '',
    'Is Reported' => isset($_GET['is_reported']) ? ($_GET['is_reported'] == '1' ? 'Yes' : 'No') : '',
    'Offence' => $_GET['offence'] ?? '',
    'Fine Status' => $_GET['fine_status'] ?? '',
];

// Generate filter summary
$filterSummary = '<ul>';
foreach ($filters as $key => $value) {
    if (!empty($value)) {
        $filterSummary .= '<li><strong>' . htmlspecialchars($key) . ':</strong> ' . htmlspecialchars($value) . '</li>';
    }
}
$filterSummary .= '</ul>';

// Build query with filters
$whereClauses = [];
$params = [];
$types = '';

if (!empty($_GET['fine_id'])) {
    $whereClauses[] = "id LIKE ?";
    $params[] = "%" . $_GET['fine_id'] . "%";
    $types .= 's';
}

if (!empty($_GET['police_id'])) {
    $whereClauses[] = "police_id LIKE ?";
    $params[] = "%" . $_GET['police_id'] . "%";
    $types .= 's';
}

if (!empty($_GET['driver_id'])) {
    $whereClauses[] = "driver_id LIKE ?";
    $params[] = "%" . $_GET['driver_id'] . "%";
    $types .= 's';
}

if (!empty($_GET['date-from'])) {
    $whereClauses[] = "issued_date >= ?";
    $params[] = $_GET['date-from'];
    $types .= 's';
}

if (!empty($_GET['date-to'])) {
    $whereClauses[] = "issued_date <= ?";
    $params[] = $_GET['date-to'];
    $types .= 's';
}

if (!empty($_GET['price-from'])) {
    $whereClauses[] = "fine_amount >= ?";
    $params[] = $_GET['price-from'];
    $types .= 'd';
}

if (!empty($_GET['price-to'])) {
    $whereClauses[] = "fine_amount <= ?";
    $params[] = $_GET['price-to'];
    $types .= 'd';
}

if (!empty($_GET['offence_type'])) {
    $whereClauses[] = "offence_type = ?";
    $params[] = $_GET['offence_type'];
    $types .= 's';
}

if (isset($_GET['is_reported']) && $_GET['is_reported'] !== '') {
    $whereClauses[] = "is_reported = ?";
    $params[] = $_GET['is_reported'];
    $types .= 's';
}

if (!empty($_GET['offence'])) {
    $whereClauses[] = "offence = ?";
    $params[] = $_GET['offence'];
    $types .= 's';
}

if (!empty($_GET['fine_status'])) {
    $whereClauses[] = "fine_status = ?";
    $params[] = $_GET['fine_status'];
    $types .= 's';
}

$query = "SELECT id, police_id, driver_id, issued_date, offence_type, offence, is_reported, fine_status, fine_amount 
          FROM fines";
if (!empty($whereClauses)) {
    $query .= " WHERE " . implode(' AND ', $whereClauses);
}

$stmt = $conn->prepare($query);
if ($types && $stmt) {
    $stmt->bind_param($types, ...$params);
}

if (!$stmt->execute()) {
    die("Query execution failed.");
}

$result = $stmt->get_result();
$fines = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Generate table rows for the PDF
ob_start(); // Start output buffering
?>
<table class="data-table">
    <thead>
        <tr>
            <th>Fine ID</th>
            <th>Police ID</th>
            <th>Driver ID</th>
            <th>Issued Date</th>
            <th>Offence Type</th>
            <th>Offence</th>
            <th>Is Reported</th>
            <th>Fine Status</th>
            <th>Amount</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($fines)): ?>
            <?php foreach ($fines as $fine): ?>
                <tr>
                    <td><?= htmlspecialchars($fine['id']) ?></td>
                    <td><?= htmlspecialchars($fine['police_id']) ?></td>
                    <td><?= htmlspecialchars($fine['driver_id']) ?></td>
                    <td><?= htmlspecialchars($fine['issued_date']) ?></td>
                    <td><?= htmlspecialchars($fine['offence_type']) ?></td>
                    <td><?= htmlspecialchars($fine['offence']) ?></td>
                    <td><?= $fine['is_reported'] ? 'Yes' : 'No' ?></td>
                    <td><?= htmlspecialchars($fine['fine_status']) ?></td>
                    <td><?= htmlspecialchars($fine['fine_amount']) ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="9">No fines found for the selected filters.</td>
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
        <div class="title">Fines Report</div>
    </div>

    <div class="document-info">
        <strong>Filters Applied:</strong>
        ' . $filterSummary . '
        <br>
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
$dompdf->stream("fines_report_" . date('Ymd_His') . ".pdf", ["Attachment" => 1]);
exit;
?>