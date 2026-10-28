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
            <img src="assets/logo.png" alt="Freelance Client Hub" style="height:28px; width:auto; vertical-align:middle; margin-right:6px;">
            Freelance Client Hub
        </div>
        <nav>
            <a href="index.php" class="<?= $current === 'index.php' ? 'active' : '' ?>">Dashboard</a>
            <a href="clients.php" class="<?= str_starts_with($current, 'client') ? 'active' : '' ?>">Clients</a>
            <a href="projects.php" class="<?= str_starts_with($current, 'project') ? 'active' : '' ?>">Projects</a>
            <a href="hours.php" class="<?= $current === 'hours.php' ? 'active' : '' ?>">Hours</a>
            <a href="invoices.php" class="<?= str_starts_with($current, 'invoice') ? 'active' : '' ?>">Invoices</a>
        </nav>
        <div class="user">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" width="16" height="16">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
            <span><?= htmlspecialchars($user['username'] ?? '') ?></span>
            <a href="logout.php">Logout</a>
        </div>
    </div>
    <div class="container">
