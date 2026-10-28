<?php
require_once __DIR__ . '/includes/auth.php';
requireLogin();
$pageTitle = 'Dashboard';

$uid = $_SESSION['user_id'];

$clientCount = $pdo->prepare('SELECT COUNT(*) FROM clients WHERE user_id = ?');
$clientCount->execute([$uid]);
$clients = $clientCount->fetchColumn();

$activeProjects = $pdo->prepare('SELECT COUNT(*) FROM projects WHERE user_id = ? AND status = "active"');
$activeProjects->execute([$uid]);
$projects = $activeProjects->fetchColumn();

$monthStart = date('Y-m-01');
$hoursThisMonth = $pdo->prepare('SELECT COALESCE(SUM(hours),0) FROM time_entries WHERE user_id = ? AND entry_date >= ?');
$hoursThisMonth->execute([$uid, $monthStart]);
$hours = $hoursThisMonth->fetchColumn();

$unpaid = $pdo->prepare('SELECT COUNT(*) FROM invoices WHERE user_id = ? AND status IN ("sent","overdue")');
$unpaid->execute([$uid]);
$unpaidInvoices = $unpaid->fetchColumn();

require __DIR__ . '/includes/header.php';
?>
<div class="card" style="border:none; background:transparent; box-shadow:none; padding:0 0 1rem;">
    <h1 style="font-size:1.8rem; margin-bottom:0.25rem;">Welcome back, <?= htmlspecialchars($user['username']) ?> 👋</h1>
    <p style="color:var(--muted);">Here's what's happening with your freelance business today.</p>
</div>

<div class="grid" style="margin-bottom:1.5rem;">
    <div class="stat">
        <div class="icon" style="background:#dbeafe; color:#1d4ed8;">👥</div>
        <strong><?= (int)$clients ?></strong>
        <span>Clients</span>
    </div>
    <div class="stat">
        <div class="icon" style="background:#dcfce7; color:#16a34a;">📁</div>
        <strong><?= (int)$projects ?></strong>
        <span>Active Projects</span>
    </div>
    <div class="stat">
        <div class="icon" style="background:#f3e8ff; color:#7c3aed;">⏱️</div>
        <strong><?= number_format($hours, 1) ?></strong>
        <span>Hours this month</span>
    </div>
    <div class="stat">
        <div class="icon" style="background:#fee2e2; color:#dc2626;">📄</div>
        <strong><?= (int)$unpaidInvoices ?></strong>
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