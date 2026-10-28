<?php
require_once __DIR__ . '/includes/auth.php';
requireLogin();

$user = currentUser();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard — Freelance Client Hub</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .topbar { background: var(--primary); color: white; padding: 0.75rem 1.5rem; display: flex; justify-content: space-between; align-items: center; }
        .topbar a { color: white; text-decoration: none; }
        .dashboard { max-width: 900px; margin: 2rem auto; padding: 0 1rem; }
        .card { background: var(--card); border: 1px solid var(--border); border-radius: 12px; padding: 1.5rem; margin-bottom: 1.5rem; }
        .card h2 { margin-bottom: 0.75rem; }
        .grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; }
        .stat { text-align: center; }
        .stat strong { display: block; font-size: 1.8rem; color: var(--primary); }
    </style>
</head>
<body>
    <div class="topbar">
        <strong>Freelance Client Hub</strong>
        <div>
            Logged in as <strong><?= htmlspecialchars($user['username']) ?></strong>
            &nbsp;|&nbsp;
            <a href="logout.php">Logout</a>
        </div>
    </div>

    <div class="dashboard">
        <div class="card">
            <h2>Welcome, <?= htmlspecialchars($user['username']) ?></h2>
            <p>Your freelance client &amp; project hub is ready. Next features (clients, projects, hours, invoices) are coming soon.</p>
        </div>

        <div class="card">
            <h2>Quick Stats</h2>
            <div class="grid">
                <div class="stat"><strong>—</strong> Clients</div>
                <div class="stat"><strong>—</strong> Active Projects</div>
                <div class="stat"><strong>—</strong> Hours this month</div>
                <div class="stat"><strong>—</strong> Unpaid invoices</div>
            </div>
        </div>

        <div class="card">
            <h2>Next Steps</h2>
            <ul>
                <li>Add clients</li>
                <li>Create projects and set hourly rates</li>
                <li>Log billable hours</li>
                <li>Generate PDF invoices</li>
            </ul>
        </div>
    </div>
</body>
</html>
