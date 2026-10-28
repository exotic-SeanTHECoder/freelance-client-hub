<?php
require_once __DIR__ . '/includes/auth.php';
requireLogin();
$pageTitle = 'Dashboard';

$uid = $_SESSION['user_id'];
$user = currentUser();

try {
    $stmt = $pdo->prepare('SELECT COUNT(*) FROM clients WHERE user_id = ?');
    $stmt->execute([$uid]);
    $clients = (int)$stmt->fetchColumn();
} catch (Exception $e) { $clients = 0; }

try {
    $stmt = $pdo->prepare('SELECT COUNT(*) FROM projects WHERE user_id = ? AND status = ?');
    $stmt->execute([$uid, 'active']);
    $projects = (int)$stmt->fetchColumn();
} catch (Exception $e) { $projects = 0; }

try {
    $monthStart = date('Y-m-01');
    $stmt = $pdo->prepare('SELECT COALESCE(SUM(hours),0) FROM time_entries WHERE user_id = ? AND entry_date >= ?');
    $stmt->execute([$uid, $monthStart]);
    $hours = (float)$stmt->fetchColumn();
} catch (Exception $e) { $hours = 0; }

try {
    $stmt = $pdo->prepare('SELECT COUNT(*) FROM invoices WHERE user_id = ? AND status IN (?, ?)');
    $stmt->execute([$uid, 'sent', 'overdue']);
    $unpaidInvoices = (int)$stmt->fetchColumn();
} catch (Exception $e) { $unpaidInvoices = 0; }

require __DIR__ . '/includes/header.php';
?>
<div class="card" style="border:none; background:transparent; box-shadow:none; padding:0 0 1rem;">
    <h1 style="font-size:1.7rem; margin-bottom:0.25rem;">Welcome back, <?= htmlspecialchars($user['username'] ?? 'User') ?></h1>
    <p style="color:var(--muted);">Here's what's happening with your freelance business today.</p>
</div>

<div class="grid" style="margin-bottom:1.5rem;">
    <div class="stat">
        <div class="icon" style="background:#dbeafe; color:#1d4ed8;">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
        </div>
        <strong><?= $clients ?></strong>
        <span>Clients</span>
    </div>
    <div class="stat">
        <div class="icon" style="background:#dcfce7; color:#16a34a;">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" /></svg>
        </div>
        <strong><?= $projects ?></strong>
        <span>Active Projects</span>
    </div>
    <div class="stat">
        <div class="icon" style="background:#f3e8ff; color:#7c3aed;">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
        </div>
        <strong><?= number_format($hours, 1) ?></strong>
        <span>Hours this month</span>
    </div>
    <div class="stat">
        <div class="icon" style="background:#fee2e2; color:#dc2626;">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
        </div>
        <strong><?= $unpaidInvoices ?></strong>
        <span>Unpaid invoices</span>
    </div>
</div>

<div class="card">
    <h2>Quick Actions</h2>
    <p style="margin-top:0.5rem;">
        <a href="client_form.php" class="btn">+ Add Client</a>
        <a href="project_form.php" class="btn">+ Add Project</a>
        <a href="hours.php" class="btn">Log Hours</a>
        <a href="invoices.php" class="btn">View Invoices</a>
    </p>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?> 