<?php
require_once __DIR__ . '/includes/auth.php';
requireLogin();
$pageTitle = 'Invoices';

// Generate invoice from uninvoiced hours of a project
if (isset($_GET['generate']) && verifyCsrfToken($_GET['csrf'] ?? null)) {
    $projectId = (int)$_GET['generate'];
    $stmt = $pdo->prepare('SELECT * FROM projects WHERE id = ? AND user_id = ?');
    $stmt->execute([$projectId, $_SESSION['user_id']]);
    $project = $stmt->fetch();

    if ($project) {
        $stmt = $pdo->prepare('SELECT * FROM time_entries WHERE project_id = ? AND user_id = ? AND invoiced = 0');
        $stmt->execute([$projectId, $_SESSION['user_id']]);
        $entries = $stmt->fetchAll();

        if ($entries) {
            $total = 0;
            foreach ($entries as $e) {
                $total += $e['hours'] * $project['hourly_rate'];
            }

            $invoiceNumber = 'INV-' . date('Ymd') . '-' . strtoupper(substr(bin2hex(random_bytes(3)), 0, 4));
            $stmt = $pdo->prepare('INSERT INTO invoices (user_id, project_id, invoice_number, issue_date, due_date, total_amount, status) VALUES (?,?,?,?,?,?,?)');
            $due = date('Y-m-d', strtotime('+14 days'));
            $stmt->execute([$_SESSION['user_id'], $projectId, $invoiceNumber, date('Y-m-d'), $due, $total, 'draft']);
            $invoiceId = $pdo->lastInsertId();

            // Mark entries as invoiced and add line items
            foreach ($entries as $e) {
                $pdo->prepare('UPDATE time_entries SET invoiced = 1 WHERE id = ?')->execute([$e['id']]);
                $amount = $e['hours'] * $project['hourly_rate'];
                $pdo->prepare('INSERT INTO invoice_items (invoice_id, description, quantity, unit_price, amount) VALUES (?,?,?,?,?)')
                    ->execute([$invoiceId, $e['description'] . ' (' . $e['entry_date'] . ')', $e['hours'], $project['hourly_rate'], $amount]);
            }

            header('Location: invoices.php?created=1');
            exit;
        }
    }
}

// Update status
if (isset($_GET['status']) && isset($_GET['id']) && verifyCsrfToken($_GET['csrf'] ?? null)) {
    $allowed = ['draft','sent','paid','overdue','cancelled'];
    $newStatus = $_GET['status'];
    if (in_array($newStatus, $allowed)) {
        $stmt = $pdo->prepare('UPDATE invoices SET status = ? WHERE id = ? AND user_id = ?');
        $stmt->execute([$newStatus, (int)$_GET['id'], $_SESSION['user_id']]);
    }
    header('Location: invoices.php');
    exit;
}

$stmt = $pdo->prepare('
    SELECT i.*, p.name AS project_name, c.name AS client_name
    FROM invoices i
    JOIN projects p ON i.project_id = p.id
    JOIN clients c ON p.client_id = c.id
    WHERE i.user_id = ?
    ORDER BY i.issue_date DESC
');
$stmt->execute([$_SESSION['user_id']]);
$invoices = $stmt->fetchAll();

// Projects with uninvoiced hours
$stmt = $pdo->prepare('
    SELECT p.id, p.name, COUNT(t.id) AS uninvoiced
    FROM projects p
    JOIN time_entries t ON t.project_id = p.id AND t.invoiced = 0
    WHERE p.user_id = ?
    GROUP BY p.id
');
$stmt->execute([$_SESSION['user_id']]);
$readyProjects = $stmt->fetchAll();

require __DIR__ . '/includes/header.php';
?>
<div class="card">
    <h2>Generate Invoice</h2>
    <?php if (empty($readyProjects)): ?>
        <p>No uninvoiced hours available.</p>
    <?php else: ?>
        <?php foreach ($readyProjects as $rp): ?>
            <p>
                <strong><?= htmlspecialchars($rp['name']) ?></strong> — <?= $rp['uninvoiced'] ?> uninvoiced entries
                <a href="invoices.php?generate=<?= $rp['id'] ?>&csrf=<?= generateCsrfToken() ?>" class="btn btn-sm" onclick="return confirm('Generate invoice for this project?')">Generate</a>
            </p>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<div class="card">
    <h2>Invoices</h2>
    <?php if (isset($_GET['created'])): ?><div class="success">Invoice created.</div><?php endif; ?>

    <?php if (empty($invoices)): ?>
        <p>No invoices yet.</p>
    <?php else: ?>
        <table>
            <thead><tr><th>Number</th><th>Client / Project</th><th>Date</th><th>Total</th><th>Status</th><th>Actions</th></tr></thead>
            <tbody>
            <?php foreach ($invoices as $inv): ?>
                <tr>
                    <td><?= htmlspecialchars($inv['invoice_number']) ?></td>
                    <td><?= htmlspecialchars($inv['client_name']) ?> — <?= htmlspecialchars($inv['project_name']) ?></td>
                    <td><?= htmlspecialchars($inv['issue_date']) ?></td>
                    <td>$<?= number_format($inv['total_amount'], 2) ?></td>
                    <td><?= ucfirst($inv['status']) ?></td>
                    <td class="actions">
                        <a href="invoice_pdf.php?id=<?= $inv['id'] ?>" class="btn btn-sm" target="_blank">PDF</a>
                        <?php if ($inv['status'] !== 'paid'): ?>
                            <a href="invoices.php?id=<?= $inv['id'] ?>&status=sent&csrf=<?= generateCsrfToken() ?>" class="btn btn-sm btn-secondary">Mark Sent</a>
                            <a href="invoices.php?id=<?= $inv['id'] ?>&status=paid&csrf=<?= generateCsrfToken() ?>" class="btn btn-sm">Mark Paid</a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?> 