<?php
if (!isset($pageTitle)) $pageTitle = 'Freelance Client Hub';
$user = currentUser();
$current = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?> — Freelance Client Hub</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="topbar">
        <div class="brand">
            💼 Freelance Client Hub
        </div>
        <nav>
            <a href="index.php" class="<?= $current === 'index.php' ? 'active' : '' ?>">Dashboard</a>
            <a href="clients.php" class="<?= str_starts_with($current, 'client') ? 'active' : '' ?>">Clients</a>
            <a href="projects.php" class="<?= str_starts_with($current, 'project') ? 'active' : '' ?>">Projects</a>
            <a href="hours.php" class="<?= $current === 'hours.php' ? 'active' : '' ?>">Hours</a>
            <a href="invoices.php" class="<?= str_starts_with($current, 'invoice') ? 'active' : '' ?>">Invoices</a>
        </nav>
        <div class="user">
            <span>👤 <?= htmlspecialchars($user['username'] ?? '') ?></span>
            <a href="logout.php">Logout</a>
        </div>
    </div>
    <div class="container">
