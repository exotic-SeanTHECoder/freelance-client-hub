<?php
if (!isset($pageTitle)) $pageTitle = 'Freelance Client Hub';
$user = currentUser();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?> — Freelance Client Hub</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .topbar { background: var(--primary); color: white; padding: 0.75rem 1.5rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.5rem; }
        .topbar nav a { color: white; text-decoration: none; margin-right: 1.2rem; font-weight: 500; }
        .topbar nav a:hover { text-decoration: underline; }
        .topbar .user { font-size: 0.95rem; }
        .container { max-width: 1100px; margin: 2rem auto; padding: 0 1rem; }
        .card { background: var(--card); border: 1px solid var(--border); border-radius: 12px; padding: 1.5rem; margin-bottom: 1.5rem; }
        .btn { display: inline-block; background: var(--primary); color: white; padding: 0.55rem 1.1rem; border-radius: 6px; text-decoration: none; font-size: 0.95rem; border: none; cursor: pointer; }
        .btn:hover { background: var(--primary-dark); }
        .btn-secondary { background: #64748b; }
        .btn-danger { background: #dc2626; }
        .btn-sm { padding: 0.35rem 0.7rem; font-size: 0.85rem; }
        table { width: 100%; border-collapse: collapse; margin-top: 1rem; }
        th, td { text-align: left; padding: 0.7rem; border-bottom: 1px solid var(--border); }
        th { background: #f1f5f9; font-weight: 600; }
        .form-group { margin-bottom: 1rem; }
        label { display: block; margin-bottom: 0.35rem; font-weight: 500; }
        input, select, textarea { width: 100%; padding: 0.6rem; border: 1px solid var(--border); border-radius: 6px; font-size: 1rem; }
        textarea { min-height: 80px; }
        .error { background: #fee2e2; color: #991b1b; padding: 0.75rem; border-radius: 6px; margin-bottom: 1rem; }
        .success { background: #dcfce7; color: #166534; padding: 0.75rem; border-radius: 6px; margin-bottom: 1rem; }
        .grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 1rem; }
        .stat { text-align: center; background: #f8fafc; padding: 1rem; border-radius: 8px; }
        .stat strong { display: block; font-size: 1.7rem; color: var(--primary); }
        .actions a { margin-right: 0.5rem; }
    </style>
</head>
<body>
    <div class="topbar">
        <nav>
            <a href="index.php">Dashboard</a>
            <a href="clients.php">Clients</a>
            <a href="projects.php">Projects</a>
            <a href="hours.php">Hours</a>
            <a href="invoices.php">Invoices</a>
        </nav>
        <div class="user">
            <?= htmlspecialchars($user['username'] ?? '') ?>
            &nbsp;|&nbsp;
            <a href="logout.php" style="color:white;">Logout</a>
        </div>
    </div>
    <div class="container">
