<?php
require_once __DIR__ . '/includes/auth.php';
requireLogin();

$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare('
    SELECT i.*, p.name AS project_name, p.hourly_rate, c.name AS client_name, c.company, c.address, c.email
    FROM invoices i
    JOIN projects p ON i.project_id = p.id
    JOIN clients c ON p.client_id = c.id
    WHERE i.id = ? AND i.user_id = ?
');
$stmt->execute([$id, $_SESSION['user_id']]);
$invoice = $stmt->fetch();

if (!$invoice) {
    die('Invoice not found');
}

$stmt = $pdo->prepare('SELECT * FROM invoice_items WHERE invoice_id = ?');
$stmt->execute([$id]);
$items = $stmt->fetchAll();

// Simple PDF using pure PHP (minimal FPDF-compatible output)
// For production you can drop in the full FPDF library. This generates a basic PDF.
header('Content-Type: application/pdf');
header('Content-Disposition: inline; filename="' . $invoice['invoice_number'] . '.pdf"');

// Minimal PDF generator (sufficient for simple invoices)
function pdfText($text) {
    return str_replace(['\\', '(', ')'], ['\\\\', '\\(', '\\)'], $text);
}

$lines = [];
$lines[] = "INVOICE " . $invoice['invoice_number'];
$lines[] = "Issue Date: " . $invoice['issue_date'];
$lines[] = "Due Date: " . ($invoice['due_date'] ?? 'N/A');
$lines[] = "";
$lines[] = "Bill To:";
$lines[] = $invoice['client_name'] . ($invoice['company'] ? ' (' . $invoice['company'] . ')' : '');
$lines[] = $invoice['address'] ?? '';
$lines[] = $invoice['email'] ?? '';
$lines[] = "";
$lines[] = "Project: " . $invoice['project_name'];
$lines[] = "";
$lines[] = "Description                                          Qty      Rate       Amount";
$lines[] = str_repeat('-', 70);

foreach ($items as $item) {
    $desc = substr($item['description'], 0, 45);
    $lines[] = sprintf("%-45s %8s %10s %12s",
        $desc,
        number_format($item['quantity'], 2),
        '$' . number_format($item['unit_price'], 2),
        '$' . number_format($item['amount'], 2)
    );
}
$lines[] = str_repeat('-', 70);
$lines[] = sprintf("%-45s %8s %10s %12s", 'TOTAL', '', '', '$' . number_format($invoice['total_amount'], 2));
$lines[] = "";
$lines[] = "Status: " . ucfirst($invoice['status']);
$lines[] = "Thank you for your business!";

// Build a very simple PDF
$pdf = "%PDF-1.4\n";
$objects = [];

// Object 1: Catalog
$objects[] = "1 0 obj\n<< /Type /Catalog /Pages 2 0 R >>\nendobj\n";

// Object 2: Pages
$objects[] = "2 0 obj\n<< /Type /Pages /Kids [3 0 R] /Count 1 >>\nendobj\n";

// Content stream
$content = "BT\n/F1 12 Tf\n50 750 Td\n";
$y = 0;
foreach ($lines as $line) {
    $content .= "(" . pdfText($line) . ") Tj\n0 -16 Td\n";
}
$content .= "ET";
$contentLen = strlen($content);

// Object 3: Page
$objects[] = "3 0 obj\n<< /Type /Page /Parent 2 0 R /MediaBox [0 0 612 792] /Contents 4 0 R /Resources << /Font << /F1 5 0 R >> >> >>\nendobj\n";

// Object 4: Content
$objects[] = "4 0 obj\n<< /Length $contentLen >>\nstream\n$content\nendstream\nendobj\n";

// Object 5: Font
$objects[] = "5 0 obj\n<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica >>\nendobj\n";

$pdf .= implode('', $objects);

// Cross-reference table
$xrefPos = strlen($pdf);
$pdf .= "xref\n0 6\n0000000000 65535 f \n";
$offset = 9; // after %PDF-1.4\n
foreach ($objects as $obj) {
    $pdf .= sprintf("%010d 00000 n \n", $offset);
    $offset += strlen($obj);
}

$pdf .= "trailer\n<< /Size 6 /Root 1 0 R >>\nstartxref\n$xrefPos\n%%EOF";

echo $pdf;
exit;
