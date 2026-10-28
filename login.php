<?php
require_once __DIR__ . '/includes/auth.php';

if (isLoggedIn()) {
    header('Location: /index.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCsrfToken($_POST['csrf'] ?? null)) {
        $error = 'Invalid request. Please try again.';
    } else {
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($username === '' || $password === '') {
            $error = 'Username and password are required.';
        } elseif (login($username, $password)) {
            header('Location: /index.php');
            exit;
        } else {
            $error = 'Invalid username or password.';
        }
    }
}

$csrf = generateCsrfToken();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Freelance Client Hub</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        body { display: flex; align-items: center; justify-content: center; min-height: 100vh; }
        .login-box {
            max-width: 420px;
            width: 100%;
            background: var(--card);
            padding: 2.5rem 2rem;
            border-radius: 16px;
            border: 1px solid var(--border);
            box-shadow: 0 10px 25px rgba(0,0,0,0.08);
            text-align: center;
        }
        .login-box h1 { font-size: 1.5rem; margin: 0.5rem 0 0.25rem; }
        .login-box h2 { font-size: 1.4rem; margin: 1.25rem 0 1.5rem; }
        .form-group { text-align: left; margin-bottom: 1.1rem; }
        button { width: 100%; margin-top: 0.5rem; }
    </style>
</head>
<body>
    <div class="login-box">
        <div style="font-size:2.5rem;">💼</div>
        <h1>Freelance Client Hub</h1>
        <div style="width:60px; height:3px; background:var(--primary); margin:0.75rem auto 0;"></div>
        <h2>Sign in</h2>

        <?php if ($error): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <input type="hidden" name="csrf" value="<?= htmlspecialchars($csrf) ?>">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" placeholder="Enter your username" required autofocus>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
            </div>
            <button type="submit" class="btn">Login</button>
        </form>

        <p style="margin-top:1.5rem; font-size:0.95rem; color:var(--muted);">
            First time? <a href="setup.php" style="color:var(--primary);">Create the admin account</a>
        </p>
    </div>
</body>
</html>
