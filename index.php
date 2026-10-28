<?php
require_once __DIR__ . '/includes/auth.php';
requireLogin();
$pageTitle = 'Dashboard';

// Live stats
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
<div class="card">
    <h2>Welcome, <?= htmlspecialchars($user['username']) ?></h2>
    <p>Manage your freelance clients, projects, billable hours, and invoices.</p>
</div>

<div class="card">
    <h2>Quick Stats</h2>
    <div class="grid">
        <div class="stat"><strong><?= (int)$clients ?></strong> Clients</div>
        <div class="stat"><strong><?= (int)$projects ?></strong> Active Projects</div>
        <div class="stat"><strong><?= number_format($hours, 1) ?></strong> Hours this month</div>
        <div class="stat"><strong><?= (int)$unpaidInvoices ?></strong> Unpaid invoices</div>
    </div>
</div>

<div class="card">
    <h2>Quick Actions</h2>
    <p>
        <a href="client_form.php" class="btn">Add Client</a>
        <a href="project_form.php" class="btn">Add Project</a>
        <a href="hours.php" class="btn">Log Hours</a>
        <a href="invoices.php" class="btn">Invoices</a>
    </p>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?> 